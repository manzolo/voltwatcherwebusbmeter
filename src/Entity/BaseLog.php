<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\Entity\Log
 *
 * @ORM\Entity()
 * @ORM\Table(name="Log", indexes={@ORM\Index(name="Log_Device_fk_idx", columns={"device_id"})})
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"base":"BaseLog", "extended":"Log"})
 */
class BaseLog
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $device_id;

    /**
     * @ORM\Column(name="`data`", type="datetime")
     */
    protected $data;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    protected $volt;

    /**
     * @ORM\Column(name="`temp`", type="decimal", precision=5, scale=2)
     */
    protected $temp;

    /**
     * @ORM\ManyToOne(targetEntity="Device", inversedBy="logs")
     * @ORM\JoinColumn(name="device_id", referencedColumnName="id", nullable=false)
     */
    protected $device;

    public function __construct()
    {
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return \App\Entity\Log
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of device_id.
     *
     * @param integer $device_id
     * @return \App\Entity\Log
     */
    public function setDeviceId($device_id)
    {
        $this->device_id = $device_id;

        return $this;
    }

    /**
     * Get the value of device_id.
     *
     * @return integer
     */
    public function getDeviceId()
    {
        return $this->device_id;
    }

    /**
     * Set the value of data.
     *
     * @param \DateTime $data
     * @return \App\Entity\Log
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get the value of data.
     *
     * @return \DateTime
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the value of volt.
     *
     * @param float $volt
     * @return \App\Entity\Log
     */
    public function setVolt($volt)
    {
        $this->volt = $volt;

        return $this;
    }

    /**
     * Get the value of volt.
     *
     * @return float
     */
    public function getVolt()
    {
        return $this->volt;
    }

    /**
     * Set the value of temp.
     *
     * @param float $temp
     * @return \App\Entity\Log
     */
    public function setTemp($temp)
    {
        $this->temp = $temp;

        return $this;
    }

    /**
     * Get the value of temp.
     *
     * @return float
     */
    public function getTemp()
    {
        return $this->temp;
    }

    /**
     * Set Device entity (many to one).
     *
     * @param \App\Entity\Device $device
     * @return \App\Entity\Log
     */
    public function setDevice(Device $device = null)
    {
        $this->device = $device;

        return $this;
    }

    /**
     * Get Device entity (many to one).
     *
     * @return \App\Entity\Device
     */
    public function getDevice()
    {
        return $this->device;
    }

    public function __sleep()
    {
        return array('id', 'device_id', 'data', 'volt', 'temp');
    }
}