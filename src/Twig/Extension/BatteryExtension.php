<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BatteryExtension extends AbstractExtension
{
    protected $em;

    public function __construct(\Doctrine\ORM\EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('battery_level', [$this, 'batteryLevel', 'is_safe' => ['html']]),
            new TwigFunction('battery_class', [$this, 'batteryClass', 'is_safe' => ['html']]),
            new TwigFunction('battery_color', [$this, 'batteryColor', 'is_safe' => ['html']]),
        ];
    }

    public function batteryLevel($volt)
    {
        $qb = $this->em->createQueryBuilder('b')
                ->select('b')
                ->from('App:Batterystatus', 'b')
                ->where(':currentvolt between b.fromvolt and b.tovolt')
                ->setParameter('currentvolt', $volt)
                ->getQuery();
        $bsrows = $qb->getResult();
        if (1 == count($bsrows)) {
            return round($bsrows[0]->getPerc(), 0);
        } else {
            //Carica %  99  90  80      70  60  50  40  30  20  10
            //Tensione  12,91 12,80 V   12,66 12,52  12,38           12,06   12,06  11,90 V 11,70 V
            if ($volt >= 12.91) {
                return 100; //12,91
            }
            if ($volt >= 12.80 && $volt < 12.91) { // 12,80
                return 90;
            }
            if ($volt >= 12.66 && $volt < 12.80) { //12,66
                return 80;
            }
            if ($volt >= 12.52 && $volt < 12.66) { //12,52
                return 70;
            }
            if ($volt >= 12.38 && $volt < 12.52) { //12,38
                return 60;
            }
            if ($volt >= 12.22 && $volt < 12.38) {
                return 50;
            }
            if ($volt >= 12.06 && $volt < 12.22) {
                return 40;
            }
            if ($volt >= 11.90 && $volt < 12.06) {
                return 30;
            }
            if ($volt >= 11.70 && $volt < 11.90) {
                return 20;
            }
            if ($volt >= 11.42 && $volt < 11.70) {
                return 10;
            }
            if ($volt < 11.42) {
                return 0;
            }
        }
    }

    public function batteryClass($volt)
    {
        if (($this->batteryLevel($volt)) >= 40) {
            return '';
        }
        if (($this->batteryLevel($volt)) >= 30 && $this->batteryLevel($volt) < 40) {
            return 'batterywarn';
        }
        if (($this->batteryLevel($volt)) < 30) {
            return 'batteryalert';
        }
    }

    public function batteryColor($volt)
    {
        if (($this->batteryLevel($volt)) >= 40) {
            return 'battery-normal-color';
        }
        if (($this->batteryLevel($volt)) >= 30 && $this->batteryLevel($volt) < 40) {
            return 'battery-warning-color';
        }
        if (($this->batteryLevel($volt)) < 30) {
            return 'battery-alert-color';
        }
    }
}
