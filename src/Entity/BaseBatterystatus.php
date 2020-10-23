<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\Entity\Batterystatus.
 *
 * @ORM\Entity()
 * @ORM\Table(name="Batterystatus")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"base": "BaseBatterystatus", "extended": "Batterystatus"})
 */
class BaseBatterystatus
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    protected $fromvolt;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    protected $tovolt;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    protected $perc;

    public function __construct()
    {
    }

    /**
     * Set the value of id.
     *
     * @param int $id
     *
     * @return \App\Entity\Batterystatus
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
     * Set the value of fromvolt.
     *
     * @param float $fromvolt
     *
     * @return \App\Entity\Batterystatus
     */
    public function setFromvolt($fromvolt)
    {
        $this->fromvolt = $fromvolt;

        return $this;
    }

    /**
     * Get the value of fromvolt.
     *
     * @return float
     */
    public function getFromvolt()
    {
        return $this->fromvolt;
    }

    /**
     * Set the value of tovolt.
     *
     * @param float $tovolt
     *
     * @return \App\Entity\Batterystatus
     */
    public function setTovolt($tovolt)
    {
        $this->tovolt = $tovolt;

        return $this;
    }

    /**
     * Get the value of tovolt.
     *
     * @return float
     */
    public function getTovolt()
    {
        return $this->tovolt;
    }

    /**
     * Set the value of perc.
     *
     * @param float $perc
     *
     * @return \App\Entity\Batterystatus
     */
    public function setPerc($perc)
    {
        $this->perc = $perc;

        return $this;
    }

    /**
     * Get the value of perc.
     *
     * @return float
     */
    public function getPerc()
    {
        return $this->perc;
    }

    public function __sleep()
    {
        return ['id', 'fromvolt', 'tovolt', 'perc'];
    }
}
