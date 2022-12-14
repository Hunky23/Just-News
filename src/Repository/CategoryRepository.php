<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function save(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function isAvailabilityToFindCriteria(array $criteria): bool
    {
        $classPropertyNames = $this->_em->getClassMetadata(Category::class)->getColumnNames();

        if (empty($criteria) || empty($classPropertyNames)) {
            return false;
        }

        $criteriaKeys = array_keys($criteria);
        $criteriaDiff = array_diff($criteriaKeys, $classPropertyNames);

        if (! empty($criteriaDiff)) {
            return false;
        }

        return true;
    }

    public function findAncestorsBy(array $criteria): array
    {
        if (! $this->isAvailabilityToFindCriteria($criteria)) {
            return [];
        }

        $whereStr = '';

        foreach ($criteria as $criterionKey => $criterionValue) {
            $whereStr = $whereStr . ' ' . $criterionKey . ' = "' . $criterionValue . '"';
        }

        $sql = sprintf('WITH RECURSIVE CTE AS (
                    SELECT *
                    FROM category
                    WHERE %s
    
                    UNION
    
                    SELECT c.*
                    FROM category AS c, CTE
                    WHERE c.id = CTE.parent_id
                )
                SELECT * FROM CTE;', $whereStr);

        $rsm = new ResultSetMappingBuilder($this->_em);
        $rsm->addRootEntityFromClassMetadata(Category::class, 'c');

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        return $query->getResult();
    }

    public function findDescendantsBy(array $criteria): array
    {
        if (! $this->isAvailabilityToFindCriteria($criteria)) {
            return [];
        }

        $whereStr = '';

        foreach ($criteria as $criterionKey => $criterionValue) {
            $whereStr = $whereStr . ' ' . $criterionKey . ' = "' . $criterionValue . '"';
        }

        $sql = sprintf('WITH RECURSIVE CTE AS (
                    SELECT *
                    FROM category
                    WHERE %s
    
                    UNION
    
                    SELECT c.*
                    FROM category AS c, CTE
                    WHERE c.parent_id = CTE.id
                )
                SELECT * FROM CTE;', $whereStr);

        $rsm = new ResultSetMappingBuilder($this->_em);
        $rsm->addRootEntityFromClassMetadata(Category::class, 'c');

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        return $query->getResult();
    }
}
