<?php

namespace Hipig\LaravelTts\Contracts;

/**
 * Interface SpeechInterface
 */
interface SpeechInterface
{
    /**
     * Get speed.
     *
     * @return string
     */
    public function getSpd() :string;

    /**
     * Get pitch.
     *
     * @return string
     */
    public function getPit() :string;

    /**
     * Get volume.
     *
     * @return string
     */
    public function getVol() :string;

    /**
     * Get per.
     *
     * @return string
     */
    public function getPer() :string;

    /**
     * Get aue.
     *
     * @return string
     */
    public function getAue() :string;

    /**
     * Set speed.
     *
     * @param string $spd
     * @return $this
     */
    public function setSpd(string $spd);

    /**
     * Set pitch.
     *
     * @param string $pit
     * @return $this
     */
    public function setPit(string $pit);

    /**
     * Set volume.
     *
     * @param string $vol
     * @return $this
     */
    public function setVol(string $vol);

    /**
     * Set per.
     *
     * @param string $per
     * @return $this
     */
    public function setPer(string $per);

    /**
     * Set aue.
     *
     * @param string $aue
     * @return $this
     */
    public function setAue(string $aue);

    /**
     * Return speech supported gateways.
     *
     * @return array
     */
    public function getGateways();
}