<?php

namespace YG\Recaptcha;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

final class RecaptchaProvider implements ServiceProviderInterface
{
    public function register(DiInterface $di): void
    {
        $di->setShared('recaptchaService', function () {
            $config = $this->get('config');
            return new Recaptcha($config->path('recaptcha.secretKey'), $config->path('recaptcha.apiUrl'));
        });
    }
}