<?php

namespace App\Lib;

use Phalcon\Mvc\User\Component;

class UserAgent extends Component
{
    public $mobilePhone = array(
        'nokia' => 'Nokia',
        'iphone' => 'iPhone',
        'ipod' => 'iPod',
        'symbian' => 'Symbian',
        'android' => 'Android',
        'htc' => 'HTC',
        'SonyEricsson' => 'SonyEricsson',
        'Sony' => 'Sony',
        'motorola' => 'Motorola',
        'mobileexplorer' => 'Mobile Explorer',
        'openwave' => 'Open Wave',
        'opera mini' => 'Opera Mini',
        'operamini' => 'Opera Mini',
        'elaine' => 'Palm',
        'palmsource' => 'Palm',
        'digital paths' => 'Palm',
        'avantgo' => 'Avantgo',
        'xiino' => 'Xiino',
        'palmscape' => 'Palmscape',
        'ericsson' => 'Ericsson',
        'blackBerry' => 'BlackBerry',
        'SmartPhone' => 'SmartPhone',
        'WindowsCE' => 'WindowsCE',
        'Mobile' => 'Unknown Mobile',
    );
        
    public $pad = array(
        'ipad' => 'iPad',
        'tablet' => 'Tablet',
    );

    public function isWeixin()
    {
        $key = 'MicroMessenger';
        $userAgent = strtolower($this->request->getUserAgent());
        $key = strtolower($key);
        return strpos($userAgent, $key) !== FALSE;
    }

    public function isMobile()
    {
        $userAgent = strtolower($this->request->getUserAgent());

        foreach ($this->mobilePhone as $key => $value) {
            $key = strtolower($key);
            if (strpos($userAgent, $key) !== FALSE)
                return $value;
            else
                continue;
        }

        return FALSE;
    }

    public function isPad()
    {
        $userAgent = strtolower($this->request->getUserAgent());

        foreach ($this->pad as $key => $value) {
            $key = strtolower($key);
            if (strpos($userAgent, $key) !== FALSE)
                return $value;
            else
                continue;
        }

        return FALSE;
    }
}