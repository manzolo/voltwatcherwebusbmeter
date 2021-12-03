<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\Entity\Log.
 *
 * @ORM\Entity()
 * @ORM\Table(name="Log", indexes={@ORM\Index(name="Log_Device_fk_idx", columns={"device_id"})})
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"base": "BaseLog", "extended": "Log"})
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
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
     */
    protected $detectorperc;

    /**
     * @ORM\Column(type="decimal", precision=13, scale=10, nullable=true)
     */
    protected $longitude;

    /**
     * @ORM\Column(type="decimal", precision=13, scale=10, nullable=true)
     */
    protected $latitude;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $weather;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
     */
    protected $externaltemp;

    /**
     * @ORM\Column(name="`location`", type="string", length=255, nullable=true)
     */
    protected $location;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
     */
    protected $cloudiness;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    protected $weathericon;

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
     * @param int $id
     *
     * @return \App\Entity\BaseLog
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
     * @return \App\Entity\BaseLog
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
     * Set the value of data.
     *
     * @param \DateTime $data
     *
     * @return \App\Entity\BaseLog
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
     *
     * @return \App\Entity\BaseLog
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
     * @return \App\Entity\BaseLog
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
     * @return \App\Entity\BaseLog
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
     * Set the value of longitude.
     *
     * @param float $longitude
     *
     * @return \App\Entity\BaseLog
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get the value of longitude.
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set the value of latitude.
     *
     * @param float $latitude
     *
     * @return \App\Entity\BaseLog
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get the value of latitude.
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set the value of weather.
     *
     * @param string $weather
     *
     * @return \App\Entity\BaseLog
     */
    public function setWeather($weather)
    {
        $this->weather = $weather;

        return $this;
    }

    /**
     * Get the value of weather.
     *
     * @return string
     */
    public function getWeather()
    {
        return $this->weather;
    }

    /**
     * Set the value of externaltemp.
     *
     * @param float $externaltemp
     *
     * @return \App\Entity\BaseLog
     */
    public function setExternaltemp($externaltemp)
    {
        $this->externaltemp = $externaltemp;

        return $this;
    }

    /**
     * Get the value of externaltemp.
     *
     * @return float
     */
    public function getExternaltemp()
    {
        return $this->externaltemp;
    }

    /**
     * Set the value of location.
     *
     * @param string $location
     *
     * @return \App\Entity\BaseLog
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get the value of location.
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set the value of cloudiness.
     *
     * @param float $cloudiness
     *
     * @return \App\Entity\BaseLog
     */
    public function setCloudiness($cloudiness)
    {
        $this->cloudiness = $cloudiness;

        return $this;
    }

    /**
     * Get the value of cloudiness.
     *
     * @return float
     */
    public function getCloudiness()
    {
        return $this->cloudiness;
    }

    /**
     * Set the value of weathericon.
     *
     * @param string $weathericon
     *
     * @return \App\Entity\BaseLog
     */
    public function setWeathericon($weathericon)
    {
        $this->weathericon = $weathericon;

        return $this;
    }

    /**
     * Get the value of weathericon.
     *
     * @return string
     */
    public function getWeathericon()
    {
        return $this->weathericon;
    }

    /**
     * Set Device entity (many to one).
     *
     * @param \App\Entity\Device $device
     *
     * @return \App\Entity\BaseLog
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
        return ['id', 'device_id', 'data', 'volt', 'temp', 'detectorperc', 'longitude', 'latitude', 'weather', 'externaltemp', 'location', 'cloudiness', 'weathericon'];
    }
}
