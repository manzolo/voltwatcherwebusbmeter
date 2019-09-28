<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\BaseDevice;

/**
 * App\Entity\Device
 *
 * @ORM\Entity()
 */
class Device extends BaseDevice {

    public function __toString() {
        return $this->getName();
    }

}
