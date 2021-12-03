<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\Entity\Journal.
 *
 * @ORM\Entity()
 * @ORM\Table(name="Journal", indexes={@ORM\Index(name="Journal_Device_fk1_idx", columns={"device_id"})})
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"base": "BaseJournal", "extended": "Journal"})
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
     * @ORM\Column(type="datetime")
     */
    protected $dal;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $al;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
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
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    protected $avgvolt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $datarilevazione;

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
     * @param int $id
     *
     * @return \App\Entity\BaseJournal
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of device_id.
     *
     * @param int $device_id
     *
     * @return \App\Entity\BaseJournal
     */
    public function setDeviceId($device_id)
    {
        $this->device_id = $device_id;

        return $this;
    }

    /**
     * Get the value of device_id.
     *
     * @return int
     */
    public function getDeviceId()
    {
        return $this->device_id;
    }

    /**
     * Set the value of dal.
     *
     * @param \DateTime $dal
     *
     * @return \App\Entity\BaseJournal
     */
    public function setDal($dal)
    {
        $this->dal = $dal;

        return $this;
    }

    /**
     * Get the value of dal.
     *
     * @return \DateTime
     */
    public function getDal()
    {
        return $this->dal;
    }

    /**
     * Set the value of al.
     *
     * @param \DateTime $al
     *
     * @return \App\Entity\BaseJournal
     */
    public function setAl($al)
    {
        $this->al = $al;

        return $this;
    }

    /**
     * Get the value of al.
     *
     * @return \DateTime
     */
    public function getAl()
    {
        return $this->al;
    }

    /**
     * Set the value of volt.
     *
     * @param float $volt
     *
     * @return \App\Entity\BaseJournal
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
     *
     * @return \App\Entity\BaseJournal
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
     *
     * @return \App\Entity\BaseJournal
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
     * Set the value of avgvolt.
     *
     * @param float $avgvolt
     *
     * @return \App\Entity\BaseJournal
     */
    public function setAvgvolt($avgvolt)
    {
        $this->avgvolt = $avgvolt;

        return $this;
    }

    /**
     * Get the value of avgvolt.
     *
     * @return float
     */
    public function getAvgvolt()
    {
        return $this->avgvolt;
    }

    /**
     * Set the value of datarilevazione.
     *
     * @param \DateTime $datarilevazione
     *
     * @return \App\Entity\BaseJournal
     */
    public function setDatarilevazione($datarilevazione)
    {
        $this->datarilevazione = $datarilevazione;

        return $this;
    }

    /**
     * Get the value of datarilevazione.
     *
     * @return \DateTime
     */
    public function getDatarilevazione()
    {
        return $this->datarilevazione;
    }

    /**
     * Set Device entity (many to one).
     *
     * @param \App\Entity\Device $device
     *
     * @return \App\Entity\BaseJournal
     */
    public function setDevice(Device $device = null)
    {
        $this->device = $device;

        return $this;
    }

    /**
     * Get Device entity (many to one).
     *
     * @return \App\Entity\BaseDevice
     */
    public function getDevice()
    {
        return $this->device;
    }

    public function __sleep()
    {
        return ['id', 'device_id', 'dal', 'al', 'volt', 'temp', 'detectorperc', 'avgvolt', 'datarilevazione'];
    }
}
