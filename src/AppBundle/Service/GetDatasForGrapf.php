<?php
namespace AppBundle\Service;

/** INFO
 *   Сервис. получает список заметок и возвращает массив
 *   с данными для построения графика
 *   по Y - даты создания notes
 *   по Х - числовые значения введенные пользователем
 *   или аналог (если текстовый тип данных)
 */
class GetDatasForGrapf
{
    private $notes;
    private $valType;
    private $valHigh;
    private $valLow;
    private $valX = array();
    private $valY = array();
    private $date_now;
    private $min_days;

    /**
     * формируем значения по-умолчанию
     */
    public function __construct()
    {
        $this->date_now = new \DateTime('now');

        $this->valType = "value";
        $this->valX    = [];
        $this->valY    = [];
        $this->valHigh = "5";
        $this->valLow  = "-5";
        $this->min_days = 5;
        $this->add_valX($this->min_days);
    }

    /**
     * @param array $notes
     *
     * @return array
     */
    public function getDatas(array $notes)
    {
        if (count($notes) > 0) {
            $this->notes   = $notes;
            $this->valType = $this->choose_type();

            $this->read_data();
            $this->valHigh = $this->calc_high();
            $this->valLow  = $this->calc_low();
        }
        $datas = array(
            'valX'    => $this->valX,
            'valY'    => $this->valY,
            'valHigh' => $this->valHigh,
            'valLow'  => $this->valLow,
        );

        return $datas;
    }

    /**
     * Считываем и заполняем X и Y
     */
    private function read_data()
    {
        $this->valX = array();
        foreach ($this->notes as $note) {
            $this->valX[] = (string)$note['createAt']->format('d.m');
            $this->valY[] = (integer)$note[$this->valType];
        }

        $this->check_enough_days($this->min_days);
        return true;
    }

    /**
     * если в массиве есть 'importance' значит используется "текстовый" тип данных
     * и значения для графика лежат в 'importance'
     *
     * @return string
     */
    private function choose_type()
    {
        if (array_key_exists('importance', $this->notes[0])) {
            return 'importance';
        }

        return 'value';
    }

    /**если малое кол-во заполненных дней, то для красоты добавляем еще пару
     *
     * @param $enough
     */
    private function check_enough_days($enough)
    {
        $diff = $enough - count ($this->valX);
        if($diff > 0)
        {
            $this->add_valX($diff);
        }
        return true;
    }

    /**
     * введен коэфициент, для увеличения границ графика
     *
     * @return int
     */
    private function calc_high()
    {
        if ($this->valType == 'importance') {
            return (integer)5;
        }

        return (integer)ceil(max($this->valY) * '1.1');
    }

    /**
     * @return int
     */
    private function calc_low()
    {
        if ($this->valType == 'importance') {
            return (integer)'-5';
        }

        return (integer)floor(min($this->valY) * '0.9');
    }

    /**
     * по-умолчанию на графике показываем сегодня и несколько дней вперед
     * @param int $days
     */
    private function add_valX($days = 1)
    {
        for ($i = 1; $i <= $days; $i++) {
            $this->valX[] = (string) $this->date_now->modify('+1 day')->format('d.m');
        }
        return true;
    }

}