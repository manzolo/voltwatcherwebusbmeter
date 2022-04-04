<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Batterystatus;

/**
 * This will suppress all the PMD warnings in
 * this class.
 *
 * @SuppressWarnings(PHPMD)
 */
class Battery
{

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function batteryLevel(float $volt): float
    {
        $qb = $this->em->createQueryBuilder()
                ->select('b')
                ->from(Batterystatus::class, 'b')
                ->where(':currentvolt between b.fromvolt and b.tovolt')
                ->setParameter('currentvolt', $volt)
                ->getQuery();
        $bsrows = $qb->getOneOrNullResult();

        if ($bsrows) {
            return round($bsrows->getPerc(), 0);
        } else {
            //Carica %  99 90 80 70 60 50 40 30 20 10
            //Tensione  12,91 12,80 12,66 12,52  12,38 12,06 12,06 11,90 11,70
            return $this->batteryPercent($volt);
        }
    }

    private function batteryPercent(float $volt): float
    {
        if ($volt > 12.92) {
            return 100; //12,91
        }
        if ($volt >= 12.80 && $volt < 12.91) { // 12,80
            return 90;
        }
        if ($volt >= 12.66 && $volt < 12.79) { //12,66
            return 80;
        }
        if ($volt >= 12.52 && $volt < 12.65) { //12,52
            return 70;
        }
        if ($volt >= 12.38 && $volt < 12.51) { //12,38
            return 60;
        }
        if ($volt >= 12.22 && $volt < 12.37) {
            return 50;
        }
        if ($volt >= 12.06 && $volt < 12.21) {
            return 40;
        }
        if ($volt >= 11.90 && $volt < 12.05) {
            return 30;
        }
        if ($volt >= 11.70 && $volt < 11.89) {
            return 20;
        }
        if ($volt >= 11.42 && $volt < 11.69) {
            return 10;
        }
        if ($volt < 11.41) {
            return 0;
        }
        return 0;
    }
}
