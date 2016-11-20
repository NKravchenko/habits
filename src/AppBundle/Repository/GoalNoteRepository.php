<?php
namespace AppBundle\Repository;

use AppBundle\Entity\GoalNote;
use Doctrine\ORM\EntityRepository;


class GoalNoteRepository extends EntityRepository
{
    /**
     * Для страницы detail_habit.
     *
     * @param $goalId
     * @param $goalType
     *
     * @return GoalNote[]
     */
    public function findLimitNotesByGoalOrderByCreate($goalId, $goalType, $offset = null, $limit = null )
    {
        $qb =  $this->createQueryBuilder('gn')
            ->select('goal.id as goalId, gn.id, gn.createAt, gn.resultText')
            ->leftJoin('gn.valueTextType', 'tt')
            ->leftJoin('gn.goal', 'goal')
            ->andWhere('goal.id = :goalId')
            ->setParameter('goalId',$goalId)
            ->orderBy('gn.createAt', 'DESC')
            ->setFirstResult( $offset )
            ->setMaxResults( $limit );
        //в зависимости от типа данных целей, выбирается нужная колонка с данными
        switch ($goalType) {
            case 1:
                $qb->addSelect('gn.valueNumber as value');
                break;
            case 2:
                $qb->addSelect('tt.id as textTypetId, tt.title as value, tt.importance');
                break;
            case 3:
                $qb->addSelect('gn.valueTime as value');
                break;
        };
        return  $qb;
    }

}