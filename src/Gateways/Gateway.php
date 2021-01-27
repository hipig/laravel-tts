<?php

namespace Hipig\LaravelTts\Gateways;

use Hipig\LaravelTts\Contracts\GatewayInterface;

abstract class Gateway implements GatewayInterface
{
    const DEFAULT_TIMEOUT = 5.0;
}