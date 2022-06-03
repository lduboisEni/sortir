<?php

namespace App\Repository;

use App\Entity\Trip;
use App\Form\model\Search;
use App\Form\SearchType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trip>
 *
 * @method Trip|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trip|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trip[]    findAll()
 * @method Trip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

    public function add(Trip $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Trip $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }



//    /**
//     * @return Trip[] Returns an array of Trip objects
//     */
    public function filterBy($search, $user, StateRepository $stateRepository): array
    {
        $qb = $this->createQueryBuilder('t');
        if ($search->getCampus()) {
            $qb->andWhere('t.campus = :campus')
                ->setParameter('campus', $search->getCampus()->getId());
        }
        if ($search->getBegindate()) {
            $qb->andWhere('t.startTime > :begindate')
                ->setParameter('begindate', $search->getBegindate());
        }
        if ($search->getEnddate()) {
            $qb->andWhere('t.startTime < :enddate')
                ->setParameter('enddate', $search->getEnddate());
        }
        if ($search->getNameContain()) {
            $qb->andWhere('t.name like :nameContain ')
                ->setParameter('nameContain', '%' . $search->getNameContain() . '%');
        }
        if ($search->isOrganiser()) {
            dump('ça passe par ici');
            $qb->andWhere('t.organiser = :isOrganiser')
                ->setParameter('isOrganiser',  $user);
        }
        if ($search->isRegistered()) {
            dump('ça passe par ici');
            $qb->andWhere(':isRegistered MEMBER OF t.users')
                ->setParameter('isRegistered',  $user);
        }
        if ($search->isNotRegistered()) {
            dump('ça passe par ici');
            $qb->andWhere(':isNotRegistered not MEMBER t.users')
                ->setParameter('isNotRegistered',  $user);
        }
        if ($search->isPassed()) {
            dump('ça passe par ici');
            $qb->andWhere('t.state = :isPassed')
                ->setParameter('isPassed', $stateRepository->findBy(array('description' => "Passée")));
        }

        return $qb->getQuery()
            ->getResult();
    }


//    public function findOneBySomeField($value): ?Trip
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
