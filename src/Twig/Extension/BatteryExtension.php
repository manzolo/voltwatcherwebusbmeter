<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use \Doctrine\ORM\EntityManagerInterface;
use App\Entity\Batterystatus;
use App\Service\Battery;

class BatteryExtension extends AbstractExtension
{

    protected EntityManagerInterface $em;
    protected Battery $battery;

    public function __construct(EntityManagerInterface $em, Battery $battery)
    {
        $this->em = $em;
        $this->battery = $battery;
    }
    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('battery_level', [$this, 'batteryLevel']),
            new TwigFunction('battery_class', [$this, 'batteryClass']),
            new TwigFunction('battery_color', [$this, 'batteryColor']),
        ];
    }
    public function batteryLevel(float $volt): float
    {
        return $this->battery->batteryLevel($volt);
    }
    public function batteryClass(float $volt): string
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
        return '';
    }
    public function batteryColor(float $volt): string
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
        return '';
    }
}
