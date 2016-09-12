<?php

namespace App\Models;


class PrizeRate extends ModelBase
{
    private $_data;

    public $dateStr;

    protected $data;

    public $updated;

    public static function saveAll($data)
    {
        $data = (array) $data;

        foreach ($data as $id => $item) {
            $dateStr = trim(array_element($item, 'dateStr'));
            $rateData = array_element($item, 'data', []);

            if (preg_match('/^[\\d]{4}\-[\\d]{2}\-[\\d]{2}$/', $dateStr) && $rateData) {
                $model = self::findFirstByDateStr($dateStr);
                if (empty($model)) {
                    $model = new self;
                    $model->dateStr = $dateStr;
                }

                $model->setData($rateData);
                @$model->save();
            }
        }
    }

    public function getData($name = null)
    {
        if ( ! isset($this->_data)) {
            if (empty($this->data)) {
                $this->_data = [];
                return null;
            }

            $this->_data = @unserialize($this->data);
        }

        if (is_null($name))
            return $this->_data;

        return array_element($this->_data, $name);
    }

    public function setData($value)
    {
        $this->data = serialize($value);
    }
}