<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

class BatteryExtension extends AbstractExtension {

    /**
     * {@inheritdoc}
     */
    public function getFunctions() {
        return array(
            new Twig_SimpleFunction('battery_level', array($this, 'batteryLevel', 'is_safe' => array('html'))),
            new Twig_SimpleFunction('battery_class', array($this, 'batteryClass', 'is_safe' => array('html'))),
        );
    }

    public function batteryLevel($x) {
        //Carica %	99	90	80  	70	60	50	40	30	20	10
        //Tensione	12,91 12,80 V	12,66 12,52  12,38           12,06   12,06  11,90 V 11,70 V
        if ($x >= 12.92)
            return 100; //12,91
        if ($x >= 12.80 && $x < 12.92) // 12,80
            return 90;
        if ($x >= 12.66 && $x < 12.80) //12,66
            return 80;
        if ($x >= 12.52 && $x < 12.66) //12,52
            return 70;
        if ($x >= 12.38 && $x < 12.52) //12,38
            return 60;
        if ($x >= 12.17 && $x < 12.38)
            return 50;
        if ($x >= 12.06 && $x < 12.17)
            return 40;
        if ($x >= 11.90 && $x < 12.06)
            return 30;
        if ($x >= 11.80 && $x < 11.90)
            return 20;
        if ($x >= 11.70 && $x < 11.80)
            return 10;
        if ($x < 11.70)
            return 0;
    }

    public function batteryClass($x) {

        if (($this->batteryLevel($x)) >= 50) {
            return "";
        }
        if (($this->batteryLevel($x)) >= 30 && $this->batteryLevel($x) < 40) {
            return "batterywarn";
        }
        if (($this->batteryLevel($x)) < 30) {
            return "batteryalert";
        }
    }

}
