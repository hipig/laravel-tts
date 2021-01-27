<?php

namespace Hipig\LaravelTts;

use Hipig\LaravelTts\Contracts\SpeechInterface;

class Speech implements SpeechInterface
{
    /**
     * @var array
     */
    protected $gateways = [];

    /**
     * @var string
     */
    protected $spd;

    /**
     * @var string
     */
    protected $pit;

    /**
     * @var string
     */
    protected $vol;

    /**
     * @var string
     */
    protected $per;

    /**
     * @var string
     */
    protected $aue;

    /**
     * Speech constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }

    /**
     * Get speed.
     *
     * @return string
     */
    public function getSpd(): string
    {
        return $this->spd;
    }

    /**
     * Get pitch.
     *
     * @return string
     */
    public function getPit(): string
    {
        return $this->pit;
    }

    /**
     * Get volume.
     *
     * @return string
     */
    public function getVol(): string
    {
        return $this->vol;
    }

    /**
     * Get per.
     *
     * @return string
     */
    public function getPer(): string
    {
        return $this->per;
    }

    /**
     * Get aue.
     *
     * @return string
     */
    public function getAue(): string
    {
        return $this->aue;
    }

    /**
     * Set Speed.
     *
     * @param string $spd
     * @return $this
     */
    public function setSpd(string $spd)
    {
        $this->spd = $spd;

        return $this;
    }

    /**
     * Set pitch.
     *
     * @param string $pit
     * @return $this
     */
    public function setPit(string $pit)
    {
        $this->pit = $pit;

        return $this;
    }

    /**
     * Set volume.
     *
     * @param string $vol
     * @return $this
     */
    public function setVol(string $vol)
    {
        $this->vol = $vol;

        return $this;
    }

    /**
     * Set per.
     *
     * @param string $per
     * @return $this
     */
    public function setPer(string $per)
    {
        $this->per = $per;

        return $this;
    }

    /**
     * Set aue.
     * @param string $aue
     * @return $this
     */
    public function setAue(string $aue)
    {
        $this->aue = $aue;

        return $this;
    }

    /**
     * Get gateways.
     *
     * @return array
     */
    public function getGateways()
    {
        return $this->gateways;
    }

    /**
     * Set gateways.
     *
     * @param array $gateways
     * @return $this
     */
    public function setGateways(array $gateways)
    {
        $this->gateways = $gateways;

        return $this;
    }

    /**
     * @param $property
     *
     * @return string
     */
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }
}