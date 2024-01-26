<?php

namespace App\Service\Logic\Decorator;

use App\Service\Interface\UserServiceInterface;

abstract class ServiceDecorator implements UserServiceInterface
{

    protected UserServiceInterface $service;

    public function __construct(UserServiceInterface $service)
    {
        $this->service = $service;
    }
    
}