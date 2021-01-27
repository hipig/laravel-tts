<?php

namespace Hipig\LaravelTts;

use Hipig\LaravelTts\Contracts\GatewayInterface;
use Hipig\LaravelTts\Contracts\SpeechInterface;
use Hipig\LaravelTts\Contracts\StrategyInterface;
use Hipig\LaravelTts\Exceptions\InvalidArgumentException;
use Hipig\LaravelTts\Exceptions\NoGatewayAvailableException;
use Hipig\LaravelTts\Gateways\Gateway;
use Hipig\LaravelTts\Strategies\OrderStrategy;

/**
 * Class Tts
 */
class Tts
{
    const STATUS_SUCCESS = 'success';

    const STATUS_FAILURE = 'failure';

    /**
     * @var string
     */
    protected $defaultGateway;

    /**
     * @var array
     */
    protected $customGateways;

    /**
     * @var array
     */
    protected $gateways = [];

    /**
     * @var array
     */
    protected $strategies = [];

    public function to(string $text, $speech, array $gateways = [])
    {
        $speech = $this->buildSpeech($speech);
        $gateways = empty($gateways) ? $speech->getGateways() : $gateways;

        if (empty($gateways)) {
            $gateways = config('tts.default.gateways', []);
        }

        return $this->toSpeech($text, $speech, $this->buildGateways($gateways));
    }

    protected function toSpeech($text, SpeechInterface $speed, array $gateways = [])
    {
        $results = [];
        $isSuccessful = false;

        foreach ($gateways as $gateway => $config) {
            try {
                $results[$gateway] = [
                    'gateway' => $gateway,
                    'status' => self::STATUS_SUCCESS,
                    'result' => $this->gateway($gateway)->send($text, $speed, $config),
                ];
                $isSuccessful = true;

                break;
            } catch (\Exception $e) {
                $results[$gateway] = [
                    'gateway' => $gateway,
                    'status' => self::STATUS_FAILURE,
                    'exception' => $e,
                ];
            } catch (\Throwable $e) {
                $results[$gateway] = [
                    'gateway' => $gateway,
                    'status' => self::STATUS_FAILURE,
                    'exception' => $e,
                ];
            }
        }

        if (!$isSuccessful) {
            throw new NoGatewayAvailableException($results);
        }

        return $results;
    }

    public function gateway($name)
    {
        if (!isset($this->gateways[$name])) {
            $this->gateways[$name] = $this->createGateway($name);
        }

        return $this->gateways[$name];
    }

    public function strategy($strategy = null)
    {
        if (\is_null($strategy)) {
            $strategy = config('tts.default.strategy', OrderStrategy::class);
        }

        if (!\class_exists($strategy)) {
            $strategy = __NAMESPACE__.'\Strategies\\'.\ucfirst($strategy);
        }

        if (!\class_exists($strategy)) {
            throw new InvalidArgumentException("Unsupported strategy \"{$strategy}\"");
        }

        if (empty($this->strategies[$strategy]) || !($this->strategies[$strategy] instanceof StrategyInterface)) {
            $this->strategies[$strategy] = new $strategy($this);
        }

        return $this->strategies[$strategy];
    }

    protected function createGateway($name)
    {
        $config = config("tts.gateways.{$name}", []);

        if (!isset($config['timeout'])) {
            $config['timeout'] = config('tts.timeout', Gateway::DEFAULT_TIMEOUT);
        }

        $config['options'] = config('tts.options', []);

        if (isset($this->customGateways[$name])) {
            $gateway = $this->callCustomGateway($name, $config);
        } else {
            $className = $this->formatGatewayClassName($name);
            $gateway = $this->makeGateway($className, $config);
        }

        if (!($gateway instanceof GatewayInterface)) {
            throw new InvalidArgumentException(\sprintf('Gateway "%s" must implement interface %s.', $name, GatewayInterface::class));
        }

        return $gateway;
    }

    protected function makeGateway($gateway, $config)
    {
        if (!\class_exists($gateway) || !\in_array(GatewayInterface::class, \class_implements($gateway))) {
            throw new InvalidArgumentException(\sprintf('Class "%s" is a invalid laravel-tts gateway.', $gateway));
        }

        return new $gateway($config);
    }

    protected function formatGatewayClassName($name)
    {
        if (\class_exists($name) && \in_array(GatewayInterface::class, \class_implements($name))) {
            return $name;
        }

        $name = \ucfirst(\str_replace(['-', '_', ''], '', $name));

        return __NAMESPACE__."\\Gateways\\{$name}Gateway";
    }

    protected function callCustomGateway($gateway, $config)
    {
        return \call_user_func($this->customGateways[$gateway], $config);
    }

    protected function buildSpeech($speed)
    {
        if (!($speed instanceof SpeechInterface)) {
            $speed = new Speech($speed);
        }

        return $speed;
    }

    protected function buildGateways(array $gateways)
    {
        $formatted = [];

        foreach ($gateways as $gateway => $setting) {
            if (\is_int($gateway) && \is_string($setting)) {
                $gateway = $setting;
                $setting = [];
            }

            $formatted[$gateway] = $setting;
            $globalSettings = config("tts.gateways.{$gateway}", []);

            if (\is_string($gateway) && !empty($globalSettings) && \is_array($setting)) {
                $formatted[$gateway] = array_merge($globalSettings, $setting);
            }
        }

        $result = [];

        foreach ($this->strategy()->apply($formatted) as $name) {
            $result[$name] = $formatted[$name];
        }

        return $result;
    }
}