<?php

namespace App\Repository;

use App\Entity\Property;
use App\Entity\PropertySearch;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
/**
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Property::class);
    }

    /**
     * @param PaginatorInterface $paginatorInterface
     * @param integer $page
     * @return SlidingPagination
     */
    public function findAllVisible(PaginatorInterface $paginatorInterface,  int $page = 1, PropertySearch $propertySearch = null):SlidingPagination
    {
        $query = $this->createQueryBuilder('p')
            ->where('p.sold = false');
        if ($propertySearch) {
            if (!empty($propertySearch->getTitle())) {
                $query->andWhere('p.title LIKE :research')->setParameter('research', '%'.$propertySearch->getTitle().'%');
            }

            if (!empty($propertySearch->getMaxPrice())) {
                $query->andWhere('p.price <= :price')->setParameter('price', $propertySearch->getMaxPrice());
            }

            if (!empty($propertySearch->getMinSurface())) {
                $query->andWhere('p.surface >= :surface')->setParameter('surface', $propertySearch->getMinSurface());
            }
        }
        return $paginatorInterface->paginate(
            $query->getQuery(),
            $page,
            10
        );
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function findLatest():array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.sold = false')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Property[] Returns an array of Property objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Property
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
