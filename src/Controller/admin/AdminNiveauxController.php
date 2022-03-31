<?php
namespace App\Controller\admin;

use App\Entity\Niveau;
use App\Repository\FormationRepository;
use App\Repository\NiveauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminNiveauxController
 *
 * @author ibay4
 */
class AdminNiveauxController extends AbstractController{
    
    private const PAGENIVEAUX = "admin/admin.niveaux.html.twig";
    
    /**
     * 
     * @var FormationRepository
     */
    private $repository;
    
    /**
     *
     * @var NiveauRepository
     */
    private $repositoryNiveau;
    
    /**
     * 
     * @param EntityManagerInterface
     */
    private $om;

    /**
     * 
     * @param FormationRepository $repository
     */
    function __construct(FormationRepository $repository, NiveauRepository $repositoryNiveau, EntityManagerInterface $om) {
        $this->repositoryNiveau = $repositoryNiveau;
        $this->repository = $repository;
        $this->om = $om;
    }
    
    /**
     * @Route("/admin/niveaux", name="admin.niveaux")
     * @return Response
     */
    public function index(): Response{
        $niveaux = $this->repositoryNiveau->findAll();
        return $this->render(self::PAGENIVEAUX, [
            'niveaux' => $niveaux
        ]);
    }
    
    /**
     * @Route("/admin/niveau/suppr/{id}", name="admin.niveaux.suppr")
     * @param Niveau $niveau
     * @return Response
     */
    public function suppr(Niveau $niveau): Response{
        $formations = $this->repository->findAllExist($niveau->getId());
        if($formations == []){
            $this->om->remove($niveau);
            $this->om->flush();
            return $this->redirectToRoute("admin.niveaux");
        }
        $this->addFlash('notice', 'Impossible de supprimer ce niveau. Il est utilisé pour (au moins) une des formations.');
        return $this->redirectToRoute("admin.niveaux");
        
    }
    /**
     * @Route("/admin/niveau/ajout", name="admin.niveau.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response{
        $nomNiveau = $request->get("nom");
        $niveau = new Niveau();
        $niveau->setNom($nomNiveau);
        $this->om->persist($niveau);
        $this->om->flush();
        return $this->redirectToRoute('admin.niveaux');
        
    }
}