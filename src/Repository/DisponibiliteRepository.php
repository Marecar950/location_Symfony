<?php

namespace App\Repository;

use App\Entity\Disponibilite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Disponibilite>
 */
class DisponibiliteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Disponibilite::class);
    }

    //    /**
    //     * @return Disponibilite[] Returns an array of Disponibilite objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Disponibilite
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findAvailableVehicules($dateDepart, $dateRetour, $prixMaxLocation)
    {
        $qb = $this->createQueryBuilder('d');
        
        $qb->select('d', '(DATE_DIFF(:dateRetour, :dateDepart) +1) * d.prixParJour AS prix_location')
           ->andWhere('d.statut = 1')
           ->andWhere('d.dateDebut <= :dateDepart')
           ->andWhere('d.dateFin >= :dateRetour')
           ->setParameter('dateDepart', $dateDepart)
           ->setParameter('dateRetour', $dateRetour);

        if ($prixMaxLocation !== null) {
            $qb->andWhere('(DATE_DIFF(:dateRetour, :dateDepart) +1) * d.prixParJour <= :prixMaxLocation')
               ->setParameter('prixMaxLocation', $prixMaxLocation);
        }
        
        $result = $qb->getQuery()->getResult();

        if (empty($result)) {
            $qb = $this->createQueryBuilder('d');

            $qb->select('d', '(DATE_DIFF(:dateRetour, :dateDepart) +1) * d.prixParJour AS prix_location')
               ->andWhere('(d.statut = 1 AND d.dateDebut >= DATE_SUB(:dateDepart, 1, \'DAY\') AND d.dateDebut <= DATE_ADD(:dateRetour, 1, \'DAY\'))')
               ->setParameter('dateDepart', $dateDepart)
               ->setParameter('dateRetour', $dateRetour);

            if ($prixMaxLocation !== null) {
                $qb->andWhere('(DATE_DIFF(:dateRetour, :dateDepart) +1) * d.prixParJour <= :prixMaxLocation')
                   ->setParameter('prixMaxLocation', $prixMaxLocation);
            }
            
            $result = $qb->getQuery()->getResult();
        }   
            return $result;
    }

}      