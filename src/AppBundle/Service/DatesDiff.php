<?php
namespace AppBundle\Service;

class DatesDiff
{
    private $date_now;

/*
 * + получить двухмерный массив $habits
 * + в каждой строке прочитать [inWeekend] (true, false)
 * + в каждой строке прочитать [createAt] (string '2016-05-18 09:50:29')
 * + вычислить разницу дат в зависимости от $inWeekend
 * + записать добавить ячейку в массив [dates_diff]
 * + выдать новый массив с доп ячейкой [dates_diff] в каждой строке
 */

    public function __construct()
    {
        $this->date_now =  new \DateTime('now');
    }

    /**
     * @param array $habits
     *
     * @return array
     */
    public function calcDiff(array $habits)
    {
        return $this->array_modify($habits);
    }

    /**
     * Метод добавляет в массив ячейку с кол-вом дней разницы дат
     *
     * @param array $habits
     *
     * @return array
     */
    private function array_modify($habits)
    {

        foreach ($habits as $key => $item)
        {
            $habits[$key]['dates_diff'] = $this -> getDatesInterval($item['updateAt']);
            if ( !$habits[$key]['inWeekend'] && $habits[$key]['dates_diff'] > 0)
            {
                $habits[$key]['dates_diff'] = $this->theNumberOfDaysOff($item['updateAt'], $habits[$key]['dates_diff']);
            }
            unset($item);
        };

        return $habits;
    }

    /**
     * Метод считет общую разницу дат с учетом выходных дней
     *
     * @param string $date_start
     *
     * @return int
     */
    private function getDatesInterval($date_start = 'now')
    {
        $date_start = new \DateTime($date_start);

        //%R на случай, если дата обновления будет больше текущей
        return (integer) $date_start->diff($this->date_now) -> format('%R%a');
    }

    /**
     * Считает сколько прошло раб дней между двумя датами
     * В основу взят код тут:
     * http://stackoverflow.com/questions/336127/calculate-business-days
     *
     * @param string $date_start
     * @param int $days
     *
     * @return int
     */
    private function theNumberOfDaysOff($date_start, $days)
    {
        $days = (int) $days;
        $date_start = new \DateTime($date_start);

        //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
        //We add one to inlude both dates in the interval.
        $no_full_weeks = floor($days / 7);
        $no_remaining_days = fmod($days, 7);

        //It will return 1 if it's Monday,.. ,7 for Sunday
        $the_first_day_of_week = $date_start -> format('N');
        $the_last_day_of_week = $this -> date_now -> format('N');

        //---->The two can be equal in leap years when february has 29 days, the equal sign is added here
        //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
        if ($the_first_day_of_week <= $the_last_day_of_week) {
            if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
            if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
        }
        else {
            // (edit by Tokes to fix an edge case where the start day was a Sunday
            // and the end day was NOT a Saturday)
            // the day of the week for start is later than the day of the week for end
            if ($the_first_day_of_week == 7) {
                // if the start date is a Sunday, then we definitely subtract 1 day
                $no_remaining_days--;

                if ($the_last_day_of_week == 6) {
                    // if the end date is a Saturday, then we subtract another day
                    $no_remaining_days--;
                }
            }
            else {
                // the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
                // so we skip an entire weekend and subtract 2 days
                $no_remaining_days -= 2;
            }
        }
        //The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
        //---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
        $workingDays = $no_full_weeks * 5;
        if ($no_remaining_days > 0 )
        {
            $workingDays += $no_remaining_days;
        }

        return (integer) $workingDays;
    }

    /**
     * @return string
     */
    private function getDateNow()
    {
        return $this->date_now->format('Y-m-d');
    }
}

/**
 * Мануалы по DateTime
 * http://uk.php.net/manual/en/datetime.createfromformat.php
 * http://stackoverflow.com/questions/12750050/how-to-create-datetime-object-from-string-in-symfony2-php
 *
 * php.net/manual/ru/datetime.diff.php
 * php.ru/manual/datetime.diff.html
 *
 * $this->date_now =  new \DateTime('now');
 *  $this->date_now =  \DateTime::createFromFormat("Y-m-d H:i:s",'2013-05-01 00:22:35');
 * $this->date_now = new \DateTime('2013-05-01 00:22:35');
 *
 */