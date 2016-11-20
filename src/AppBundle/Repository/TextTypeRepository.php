<?php
namespace AppBundle\Repository;

use AppBundle\Entity\TextType;
use Doctrine\ORM\EntityRepository;


class TextTypeRepository extends EntityRepository
{
    /**
     * @return TextType[]
     */
    public function findAllTextTypes()
    {
        $qb =  $this->createQueryBuilder('tt')
            ->select('tt.id, tt.title')
            ->getQuery()
            ->getArrayResult();
        return  $qb;
    }

}