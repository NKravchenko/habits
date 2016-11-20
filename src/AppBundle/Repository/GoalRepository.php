<?php
namespace AppBundle\Repository;

use AppBundle\Entity\Goal;
use Doctrine\ORM\EntityRepository;


class GoalRepository extends EntityRepository
{

    /**
     * @param $isActive
     *
     * @return Goal[]
     */
    public function findAllActivGoalOrderByName()
    {
        $qb =  $this->createQueryBuilder('goal')
            ->select('goal.id, goal.title, goal.isActive, goal.inWeekend, MAX(notes.createAt) as updateAt')
            ->leftJoin('goal.notes', 'notes')
            ->andWhere('goal.isActive = :isActive')
            ->setParameter('isActive', true)
            ->groupBy('goal.id')
            ->orderBy('goal.dateAdd', 'DESC')
            ->getQuery()
//            ->execute();
            ->getResult();
        return $qb;
    }

    /**
     * @param $isActive
     *
     * @return Goal[]
     */
    public function findAllNotActivGoalOrderByDate()
    {
        $qb =  $this->createQueryBuilder('goal')
            ->select('goal.id, goal.title, goal.isActive, goal.dateAdd, goal.dateStop, goal.result')
            ->andWhere('goal.isActive = :isActive')
            ->setParameter('isActive', false)
            ->orderBy('goal.dateAdd', 'DESC');
//            ->getQuery()
//            ->getResult();
        return $qb;
    }
    /**
     * Для страницы detail_habit.
     * запрашиваем goalType для определения, из какой колонки таблицы GoalNote читать результаты
     *
     * @param $id
     *
     * @return array
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findGoalByIdShort($id)
    {

        $qb =  $this->createQueryBuilder('goal')
            ->select('goal.id, goal.title, goal.isActive, type.id as goalType')
            ->join('goal.goalType', 'type')
            ->andWhere('goal.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
        return $qb;
    }
}