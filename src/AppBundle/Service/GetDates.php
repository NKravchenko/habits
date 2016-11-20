<?php
namespace AppBundle\Service;

class GetDates
{
    private $notes = null;
    private $dates = array();
    private $dateMin = null;
    private $dateMax = null;

    /**
     * Получаем наименьшую и наибольшую дату из входящего массива
     *
     * @param array $notes
     *
     * @return array
     */
    public function getDatesMinMax(array $notes)
    {
        if (count($notes) > 0) {
            $this->notes = $notes;
            $this->read_data();
        } else {
            $this->dates[] =  new \DateTime('now');
        }

        $this->dateMin = min($this->dates);
        $this->dateMax = max($this->dates);

        return array(
            'dateMin' => (string) $this->dateMin->format('d.m'),
            'dateMax' => (string) $this->dateMax->format('d.m'),
        );
    }

    /**
     * Считываем все даты и заполняем их в массив $this->$dates
     */
    private function read_data()
    {
        foreach ($this->notes as $note) {
            $this->dates[] = $note['createAt'];
        }

        return true;
    }
}