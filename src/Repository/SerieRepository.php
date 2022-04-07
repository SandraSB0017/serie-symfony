<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Serie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Serie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Serie[]    findAll()
 * @method Serie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
    }

    public function findBestSeries ()
    {
      /*  //en DQL
        $entityManager = $this->getEntityManager();
        $dql = "
        SELECT s 
        FROM App\Entity\Serie s
        WHERE s.popularity > 100
        AND s.vote > 8
        ORDER BY s.popularity DESC
        ";
        $query = $entityManager->createQuery($dql);*/




        //version QueryBuilder

        $queryBuilder = $this->createQueryBuilder('s');
        // jointure. 1er argument: la propriete ds serie que je veux recupérer .2ème argument: l'alias pour seasons
        $queryBuilder->leftJoin('s.seasons', 'seas')-> addSelect('seas');

        $queryBuilder->andWhere('s.popularity>100');
        $queryBuilder->andWhere('s.vote > 8');
        $queryBuilder->addOrderBy('s.popularity', 'DESC');
        $query = $queryBuilder->getQuery();


        // pour les 2

        $query->setMaxResults(50);

        $paginator = new Paginator($query);


        //$results =$query->getResult();
        //return $results;

        return $paginator;

    }



    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Serie $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Serie $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


}
