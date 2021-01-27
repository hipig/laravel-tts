<?php

namespace Hipig\LaravelTts\Contracts;

/**
 * Interface GatewayInterface
 */
interface GatewayInterface
{
    /**
     * Get gateway name.
     *
     * @return string
     */
    public function getName() :string;

    /**
     * Text to speech.
     *
     * @param string $text
     * @param SpeechInterface $speech
     * @param array $config
     * @return array
     */
    public function to(string $text, SpeechInterface $speech, array $config) :array;
}