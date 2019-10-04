<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\Entity\Journal
 *
 * @ORM\Entity()
 * @ORM\Table(name="Journal", indexes={@ORM\Index(name="Journal_Device_fk1_idx", columns={"device_id"})})
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"base":"BaseJournal", "extended":"Journal"})
 */
class BaseJournal
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
     * @ORM\Column(name="`temp`", type="decimal", precision=5, scale=2, nullable=true)
     */
    protected $temp;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
     */
    protected $detectorperc;

    /**
     * @ORM\ManyToOne(targetEntity="Device", inversedBy="journals")
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
     * @return \App\Entity\Journal
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
     * @return \App\Entity\Journal
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
     * @return \App\Entity\Journal
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
     * @return \App\Entity\Journal
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
     * @return \App\Entity\Journal
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
     * Set the value of detectorperc.
     *
     * @param float $detectorperc
     * @return \App\Entity\Journal
     */
    public function setDetectorperc($detectorperc)
    {
        $this->detectorperc = $detectorperc;

        return $this;
    }

    /**
     * Get the value of detectorperc.
     *
     * @return float
     */
    public function getDetectorperc()
    {
        return $this->detectorperc;
    }

    /**
     * Set Device entity (many to one).
     *
     * @param \App\Entity\Device $device
     * @return \App\Entity\Journal
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
        return array('id', 'device_id', 'data', 'volt', 'temp', 'detectorperc');
    }
}