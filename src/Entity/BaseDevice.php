<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * App\Entity\Device.
 *
 * @ORM\Entity()
 * @ORM\Table(name="Device")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"base": "BaseDevice", "extended": "Device"})
 */
class BaseDevice
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $address;

    /**
     * @ORM\Column(name="`name`", type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2, nullable=true)
     */
    protected $threshold;

    /**
     * @ORM\OneToMany(targetEntity="Journal", mappedBy="device")
     * @ORM\JoinColumn(name="id", referencedColumnName="device_id", nullable=false)
     */
    protected $journals;

    /**
     * @ORM\OneToMany(targetEntity="Log", mappedBy="device")
     * @ORM\JoinColumn(name="id", referencedColumnName="device_id", nullable=false)
     */
    protected $logs;

    public function __construct()
    {
        $this->journals = new ArrayCollection();
        $this->logs = new ArrayCollection();
    }

    /**
     * Set the value of id.
     *
     * @param int $id
     *
     * @return \App\Entity\Device
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
     * Set the value of address.
     *
     * @param string $address
     *
     * @return \App\Entity\Device
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get the value of address.
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set the value of name.
     *
     * @param string $name
     *
     * @return \App\Entity\Device
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of threshold.
     *
     * @param float $threshold
     *
     * @return \App\Entity\Device
     */
    public function setThreshold($threshold)
    {
        $this->threshold = $threshold;

        return $this;
    }

    /**
     * Get the value of threshold.
     *
     * @return float
     */
    public function getThreshold()
    {
        return $this->threshold;
    }

    /**
     * Add Journal entity to collection (one to many).
     *
     * @param \App\Entity\Journal $journal
     *
     * @return \App\Entity\Device
     */
    public function addJournal(Journal $journal)
    {
        $this->journals[] = $journal;

        return $this;
    }

    /**
     * Remove Journal entity from collection (one to many).
     *
     * @param \App\Entity\Journal $journal
     *
     * @return \App\Entity\Device
     */
    public function removeJournal(Journal $journal)
    {
        $this->journals->removeElement($journal);

        return $this;
    }

    /**
     * Get Journal entity collection (one to many).
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getJournals()
    {
        return $this->journals;
    }

    /**
     * Add Log entity to collection (one to many).
     *
     * @param \App\Entity\Log $log
     *
     * @return \App\Entity\Device
     */
    public function addLog(Log $log)
    {
        $this->logs[] = $log;

        return $this;
    }

    /**
     * Remove Log entity from collection (one to many).
     *
     * @param \App\Entity\Log $log
     *
     * @return \App\Entity\Device
     */
    public function removeLog(Log $log)
    {
        $this->logs->removeElement($log);

        return $this;
    }

    /**
     * Get Log entity collection (one to many).
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLogs()
    {
        return $this->logs;
    }

    public function __sleep()
    {
        return ['id', 'address', 'name', 'threshold'];
    }
}
