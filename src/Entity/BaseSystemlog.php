<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\Entity\Systemlog.
 *
 * @ORM\Entity()
 * @ORM\Table(name="Systemlog")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"base": "BaseSystemlog", "extended": "Systemlog"})
 */
class BaseSystemlog
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $datelog;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    protected $typelog;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $agent;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    protected $ip;

    /**
     * @ORM\Column(type="string", length=4000, nullable=true)
     */
    protected $message;

    public function __construct()
    {
    }

    /**
     * Set the value of id.
     *
     * @param int $id
     *
     * @return \App\Entity\BaseSystemlog
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
     * Set the value of datelog.
     *
     * @param \DateTime $datelog
     *
     * @return \App\Entity\BaseSystemlog
     */
    public function setDatelog($datelog)
    {
        $this->datelog = $datelog;

        return $this;
    }

    /**
     * Get the value of datelog.
     *
     * @return \DateTime
     */
    public function getDatelog()
    {
        return $this->datelog;
    }

    /**
     * Set the value of typelog.
     *
     * @param string $typelog
     *
     * @return \App\Entity\BaseSystemlog
     */
    public function setTypelog($typelog)
    {
        $this->typelog = $typelog;

        return $this;
    }

    /**
     * Get the value of typelog.
     *
     * @return string
     */
    public function getTypelog()
    {
        return $this->typelog;
    }

    /**
     * Set the value of agent.
     *
     * @param string $agent
     *
     * @return \App\Entity\BaseSystemlog
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;

        return $this;
    }

    /**
     * Get the value of agent.
     *
     * @return string
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * Set the value of ip.
     *
     * @param string $ip
     *
     * @return \App\Entity\BaseSystemlog
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get the value of ip.
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set the value of message.
     *
     * @param string $message
     *
     * @return \App\Entity\BaseSystemlog
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the value of message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    public function __sleep()
    {
        return ['id', 'datelog', 'typelog', 'agent', 'ip', 'message'];
    }
}
