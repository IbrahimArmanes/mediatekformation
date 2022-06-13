<?php
namespace App\Controller\admin;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use App\Repository\NiveauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminFormationsController
 *
 * @author ibay4
 */
class AdminFormationsController extends AbstractController{
    
    private const PAGEFORMATIONS = "admin/admin.formations.html.twig";

    /**
     *
     * @var FormationRepository
     */
    private $repository;
    
    /**
     * 
     * @var EntityManagerInterface
     */
    private $om;
    
    /**
     *
     * @var NiveauRepository
     */
    private $repositoryNiveau;

    /**
     * 
     * @param FormationRepository $repository
     */
    function __construct(FormationRepository $repository, NiveauRepository $repositoryNiveau, EntityManagerInterface $om) {
        $this->repository = $repository;
        $this->repositoryNiveau = $repositoryNiveau;
        $this->om = $om;
    }

    /**
     * @Route("/admin", name="admin.formations")
     * @return Response
     */
    public function index(): Response{
        $formations = $this->repository->findAll();
        $niveaux = $this->repositoryNiveau->findAllOrderBy('id','ASC');
        return $this->render(self::PAGEFORMATIONS, [
            'formations' => $formations ,
            'niveaux' => $niveaux
            
        ]);
    }
    /**
     * Ajout d'une nouvelle formation grâce à un formulaire
     * @Route("/admin/ajout", name="admin.formation.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response{
        $formation = new Formation();
        $formFormation = $this->createForm(FormationType::class, $formation);
        
        $formFormation->handleRequest($request);
        if($formFormation->isSubmitted() && $formFormation->isValid()){
            $this->om->persist($formation);
            $this->om->flush();
            return $this->redirectToRoute('admin.formations');
        }
        return $this->render("admin/admin.formation.ajout.html.twig",[
            'formation' => $formation,
            'formformation' => $formFormation->createView()
        ]);
    }
    
    /**
     * Suppression d'une formation
     * @Route("/admin/suppr/{id}", name="admin.formations.suppr")
     * @param Formation $formation
     * @return Response
     */
    public function suppr(Formation $formation): Response{
        $this->om->remove($formation);
        $this->om->flush();
        return $this->redirectToRoute('admin.formations');
    }
    
    /**
     * Modification d'une formation
     * @Route("/admin/edit/{id}", name="admin.formation.edit")
     * @param Formation $formation
     * @param Request $request
     * @return Response
     */
    public function edit(Formation $formation, Request $request): Response{
        $formFormation = $this->createForm(FormationType::class, $formation);
        
        $formFormation->handleRequest($request);
        if($formFormation->isSubmitted() && $formFormation->isValid()){
            $this->om->flush();
            return $this->redirectToRoute('admin.formations');
        }
        
        return $this->render("admin/admin.formation.edit.html.twig",[
            'formation' => $formation,
            'formformation' => $formFormation->createView()
        ]);
    }
    
    
   
    
    
    /**
     * @Route("/admin/tri/{champ}/{ordre}", name="admin.formations.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    public function sort($champ, $ordre): Response{
        $formations = $this->repository->findAllOrderBy($champ, $ordre);
        $niveaux = $this->repositoryNiveau->findAllOrderBy('id','ASC');
        return $this->render(self::PAGEFORMATIONS, [
           'formations' => $formations,
            'niveaux' => $niveaux
        ]);
    }   
        
    /**
     * @Route("/admin/recherche/{champ}", name="admin.formations.findallcontain")
     * @param type $champ
     * @param Request $request
     * @return Response
     */
    public function findAllContain($champ, Request $request): Response{
        if($this->isCsrfTokenValid('filtre_'.$champ, $request->get('_token'))){
            $valeur = $request->get("recherche");
            $formations = $this->repository->findByContainValue($champ, $valeur);
            $niveaux = $this->repositoryNiveau->findAllOrderBy('id','ASC');
            return $this->render(self::PAGEFORMATIONS, [
                'formations' => $formations,
                'niveaux' => $niveaux
            ]);
        }
        return $this->redirectToRoute("admin");
    }
    
    /**
     * @Route("/admin/filter/{champ}", name="admin.formations.findallvalue")
     * @param type $champ
     * @param Request $request
     * @return Response
     */
    public function findAllValue($champ, Request $request): Response{
        if($this->isCsrfTokenValid('filtre_'.$champ, $request->get('_token'))){
            $valeur = $request->get("filtrer");
            $formations = $this->repository->findByValue($champ, $valeur);
            $niveaux = $this->repositoryNiveau->findAllOrderBy('id','ASC');
            return $this->render(self::PAGEFORMATIONS, [
                'formations' => $formations,
                'niveaux' => $niveaux
            ]);
        }
        return $this->redirectToRoute("admin");
    }
    

    
    /**
     * @Route("/admin/formation/{id}", name="admin.formations.showone")
     * @param type $id
     * @return Response
     */
    public function showOne($id): Response{
        $formation = $this->repository->find($id);
        $niveaux = $this->repositoryNiveau->findAllOrderBy('id','ASC');
        return $this->render("pages/formation.html.twig", [
            'formation' => $formation,
            'niveaux' => $niveaux
        ]);        
    }
}     