<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Goal;
use AppBundle\View\AjaxResult;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\GoalFormType;

class GoalController extends Controller
{

    public function actualAction()
    {
        $em = $this->getDoctrine()->getManager();
        $goals = $em->getRepository('AppBundle:Goal')
            ->findAllActivGoalOrderByName();
        $goals = $this->get('app.dates_difference')->calcDiff($goals);

        return $this->render(
            'AppBundle:Goal:actual.html.twig',
            [
                'goals' => $goals,
            ]
        );
    }

    public function archiveAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $goals = $em->getRepository('AppBundle:Goal')
            ->findAllNotActivGoalOrderByDate()
            ->getQuery();

        // Пагинатор knpPaginatorBundle
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $goals,
            $request->query->get('page', 1)/*page number*/,
            15/*limit per page*/
        );

        return $this->render(
            'AppBundle:Goal:archive.html.twig',
            [
                'goals' => $pagination,
            ]
        );
    }

    public function detailAction($id)
    {
        //Запрос 1: Получаем назнавание, АктивенЛи, idТипаДанных для дальнейшего запроса 2
        $em = $this->getDoctrine()->getManager();
        $goal_info = $em->getRepository('AppBundle:Goal')
            ->findGoalByIdShort($id);

        //Запрос 2: Получаем заметки пользователя для выбранной цели в зависимости от idТипаДанных
        $goalNotesQB = $em->getRepository('AppBundle:GoalNote')
            ->findLimitNotesByGoalOrderByCreate($id, $goal_info['goalType'], null, 5);
        $goal_notes = $goalNotesQB->getQuery()->getArrayResult();

        //Запрос 3: $text_type = text.id, title, importance
//        $text_type = $em->getRepository('AppBundle:TextType')
//            ->findAllTextTypes();
//        dump($text_type);

        if (!$goal_info) {
            //throw $this->createNotFoundException('Сорри, искомая привычка не найдена');
            $this->addFlash('success', 'Искомая цель не найдена');

            //переход на список актуальных целей
            return $this->redirect(
                $this->generateUrl(
                    'goals_actual'
                )
            );
        }

        /* получаем мин и макс даты создания заметок */
        $notesDatesMinMax = $this->get('app.get_dates')->getDatesMinMax($goal_notes);

        /* получаем данные для построения графика */
        $goal_grapf = $this->get('app.get_datas_for_grapf')->getDatas($goal_notes);

        //лениво сочинять...
        $testNotes = [1, 2, 3, 4, 5, 6, 7];

        return $this->render(
//            'habits/detail.twig',
            'AppBundle:Goal:detail.html.twig',
            [
                'goal_id'        => $id,
                'goal_info'      => $goal_info,
                'goal_notes'     => $goal_notes,
                'goal_grapf'     => $goal_grapf,
                'notes_date_min' => $notesDatesMinMax['dateMin'],
                'notes_date_max' => $notesDatesMinMax['dateMax'],
                'test_notes'     => $testNotes,
            ]
        );
    }

    public function noteSaveAction(Request $request)
    {
        $result              = new AjaxResult();
        $result->contentName = 'inline';

        $states = [
            1 => 'Good',
            2 => 'Neutral',
            3 => 'Negative',
        ];

        $goalId = $request->get('goalId');
        $dayId  = $request->get('dayId');
        $note   = $request->get('note');
        $state  = $request->get('state');

        //что-то делаешь с этими значениями

        //$result->content = '---response---';
        $result->content = $this->render(
            'AppBundle:Goal/detail:new_comments_item_result.html.twig',
            [
                'goalId' => $goalId,
                'dayId'  => $dayId,
                'note'   => $note,
                'state'  => $states[$state],
            ]
        )->getContent();

        return new JsonResponse($result);
    }

    public function notesAction(Request $request, $id)
    {
        //Запрос 1: Получаем назнавание, АктивенЛи, idТипаДанных для дальнейшего запроса 2
        $em = $this->getDoctrine()->getManager();
        $goal_info = $em->getRepository('AppBundle:Goal')
            ->findGoalByIdShort($id);

        //Запрос 2: Получаем заметки пользователя для выбранной цели в зависимости от idТипаДанных
        $goalNotes = $em->getRepository('AppBundle:GoalNote')
            ->findLimitNotesByGoalOrderByCreate($id, $goal_info['goalType'], null, null)
            ->getQuery();

        // Пагинатор knpPaginatorBundle
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $goalNotes,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );

        /* получаем мин и макс даты создания заметок */
        $notesDatesMinMax = $this->get('app.get_dates')->getDatesMinMax($pagination->getItems());

        /* если нет записей, возвращаем на страницу с информ. о цели */
        if (count($pagination->getItems()) < 1) {
            //если нет заметок, то возвращаем на детальную страницу цели
            return $this->redirect(
                $this->generateUrl(
                    'goal_detail',
                    array('id' => $id)
                )
            );
        }

        return $this->render(
            'AppBundle:Goal:notes.html.twig',
            [
                'goal_info'  => $goal_info,
                'goal_notes' => $pagination,
                'date_min'   => $notesDatesMinMax['dateMin'],
                'date_max'   => $notesDatesMinMax['dateMax'],
            ]
        );
    }

    public function newAction(Request $request)
    {
        //задаем значение по-умолчанию + получаем объект юзверя
        $em = $this->getDoctrine()->getManager();
        //TODO Временно ручной ввод id пользователя
        $setUsers = $em->getRepository('AppBundle:User')->find(21);
        $setdefaultType = $em->getRepository('AppBundle:GoalType')->find(2);
        $setValuesGoal = new Goal();
//        $setValuesGoal->setTitle('Название по-умолчанию');
        $setValuesGoal->setCreator($setUsers);
        $setValuesGoal->setGoalType($setdefaultType);

        $form = $this->createForm(GoalFormType::class, $setValuesGoal)
            ->remove('isActive')
            ->remove('result');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $goal = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($goal);
            $em->flush();

            $this->addFlash('success', 'Новая цель добавлена. Все обязательно получится! =)');

            //Переход на общий список целей
            return $this->redirectToRoute('goals_actual');
        }

        return $this->render(
            'AppBundle:Goal:new.html.twig',
            [
                'form'     => $form->createView(),
                'linkBack' => 'goals_actual',
            ]
        );
    }

    public function editAction(Request $request, Goal $goal)
    {
        $form = $this->createForm(GoalFormType::class, $goal);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $goal = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($goal);
            $em->flush();

            $this->addFlash('success', 'Изменения внесены');

            //переход на детальную страницу привычки
            return $this->redirect(
                $this->generateUrl(
                    'goal_detail',
                    array('id' => $goal->getId())
                )
            );
        }

        return $this->render(
            'AppBundle:Goal:edit.html.twig',
            [
                'form'     => $form->createView(),
                'linkBack' => 'goal_detail',
                'id'       => $goal->getId(),
            ]
        );
    }
}
