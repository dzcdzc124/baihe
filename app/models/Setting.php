<?php

namespace App\Models;


class Setting extends ModelBase
{
    public $name;

    protected $value;

    public $updated;

    public function getValue()
    {
        try {
            $value = unserialize($this->value);
        } catch (\Exception $e) {
            $value = null;
        }

        return $value;
    }

    public function setValue($value)
    {
        $this->value = serialize($value);
    }

    public static function fetchOrCreate($name)
    {
        $setting = self::findFirstByName($name);
        if (empty($setting)) {
            $setting = new Setting;
            $setting->name = $name;
            $setting->save();
        }

        return $setting;
    }

    public static function saveIt($name, $value)
    {
        $setting = self::fetchOrCreate($name);
        $setting->setValue($value);
        return $setting->save();
    }

    public static function deleteIt($name)
    {
        $setting = self::findFirstByName($name);
        if ($setting)
            $setting->delete();
    }
}