<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\Entity\Device.
 *
 * @ORM\Entity()
 */
class Device extends BaseDevice
{
    public function __toString()
    {
        return $this->getName() ? $this->getName() : $this->getAddress();
    }
}
