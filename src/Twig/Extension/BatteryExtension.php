<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig_SimpleFilter;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Twig_SimpleFunction;

class BatteryExtension extends AbstractExtension {

    protected $em;

    public function __construct(RegistryInterface $em) {
        $this->em = $em->getManager();
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions() {
        return array(
            new Twig_SimpleFunction('battery_level', array($this, 'batteryLevel', 'is_safe' => array('html'))),
            new Twig_SimpleFunction('battery_class', array($this, 'batteryClass', 'is_safe' => array('html'))),
        );
    }

    public function batteryLevel($volt) {
        $qb = $this->em->createQueryBuilder('b')
                ->select("b")
                ->from('App:Batterystatus', 'b')
                ->where(':currentvolt between b.fromvolt and b.tovolt')
                ->setParameter('currentvolt', $volt)
                ->getQuery();
        $bsrows = $qb->getResult();
        if (count($bsrows) == 1) {
            return round($bsrows[0]->getPerc(),0);
        } else {
            //Carica %	99	90	80  	70	60	50	40	30	20	10
            //Tensione	12,91 12,80 V	12,66 12,52  12,38           12,06   12,06  11,90 V 11,70 V
            if ($volt >= 12.92)
                return 100; //12,91
            if ($volt >= 12.80 && $volt < 12.92) // 12,80
                return 90;
            if ($volt >= 12.66 && $volt < 12.80) //12,66
                return 80;
            if ($volt >= 12.52 && $volt < 12.66) //12,52
                return 70;
            if ($volt >= 12.38 && $volt < 12.52) //12,38
                return 60;
            if ($volt >= 12.17 && $volt < 12.38)
                return 50;
            if ($volt >= 12.06 && $volt < 12.17)
                return 40;
            if ($volt >= 11.90 && $volt < 12.06)
                return 30;
            if ($volt >= 11.80 && $volt < 11.90)
                return 20;
            if ($volt >= 11.70 && $volt < 11.80)
                return 10;
            if ($volt < 11.70)
                return 0;
        }
    }

    function batteryClass($volt) {
        if (($this->batteryLevel($volt)) >= 50) {
            return "";
        }
        if (($this->batteryLevel($volt)) >= 30 && $this->batteryLevel($volt) < 40) {
            return "batterywarn";
        }
        if (($this->batteryLevel($volt)) < 30) {
            return "batteryalert";
        }
    }

}
