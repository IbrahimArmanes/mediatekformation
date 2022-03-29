<?php
namespace App\Controller\admin;

use App\Repository\FormationRepository;
use App\Repository\NiveauRepository;
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
     * @var NiveauRepository
     */
    private $repositoryNiveau;

    /**
     * 
     * @param FormationRepository $repository
     */
    function __construct(NiveauRepository $repositoryNiveau) {
        $this->repositoryNiveau = $repositoryNiveau;
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

}
