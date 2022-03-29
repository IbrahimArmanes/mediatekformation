<?php

namespace App\Repository;

use App\Entity\Formation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Formation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formation[]    findAll()
 * @method Formation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formation::class);
    }

    /**
     * Retourne toutes les formations triées sur un champ
     * @param type $champ
     * @param type $ordre
     * @return Formation[]
     */
    public function findAllOrderBy($champ, $ordre): array{
            return $this->createQueryBuilder('f')
                    ->orderBy('f.'.$champ, $ordre)
                    ->getQuery()
                    ->getResult();
    }

    /**
     * Enregistrements dont un champ contientune valeur
     * ou tous les enregistrements si la valeur est vide
     * @param type $champ
     * @param type $valeur
     * @return Formation[]
     */
    public function findByContainValue($champ, $valeur): array{
        if($valeur==""){
            return $this->createQueryBuilder('f')
                    ->orderBy('f.'.$champ, 'ASC')
                    ->getQuery()
                    ->getResult();
        }else{
            return $this->createQueryBuilder('f')
                    ->where('f.'.$champ.' LIKE :valeur')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->orderBy('f.publishedAt', 'DESC')
                    ->getQuery()
                    ->getResult();            
        }
    }
    
    /**
     * Enregistrements dont un champ est exactement une valeur
     * ou tous les enregistrements si la valeur est vide
     * @param type $champ
     * @param type $valeur
     * @return Formation[]
     */
    public function findByValue($champ, $valeur): array{
        if($valeur==""){
            return $this->createQueryBuilder('f')
                    ->orderBy('f.'.$champ, 'ASC')
                    ->getQuery()
                    ->getResult();
        }else{
            return $this->createQueryBuilder('f')
                    ->where('f.'.$champ.'= :valeur')
                    ->setParameter('valeur', $valeur)
                    ->orderBy('f.publishedAt', 'DESC')
                    ->getQuery()
                    ->getResult();            
        }
    }
    
        
    /**
     * Retourne les n formations les plus récentes
     * @param type $nb
     * @return Formation[]
     */
    public function findAllLasted($nb) : array {
        return $this->createQueryBuilder('f')
           ->orderBy('f.publishedAt', 'DESC')
           ->setMaxResults($nb)     
           ->getQuery()
           ->getResult();
    }
    /**
     * 
     * @param type $id
     * @return string
     */
    public function findByNiveauId(): array {
        return $this->createQueryBuilder('q')
                ->join('q.niveau', "n")
                ->groupBy('n.nom')
                ->orderBy('q.id','ASC')
                ->getQuery()
                ->getResult();
            
    }
    
    public function findAllExist($id): array{
        return $this->createQueryBuilder('f')
                ->where("f.niveau = :valeur")
                ->setParameter('valeur', $id)
                ->getQuery()
                ->getResult();
    }
  
}
/**
'SELECT nom 
            FROM formation
            JOIN niveau ON formation.id = niveau.id
            GROUP BY niveau.nom
            ORDER BY formation.id'