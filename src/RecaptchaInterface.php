<?php

namespace YG\Phalcon\Recaptcha;

interface RecaptchaInterface
{
    public function verify(string $response, string $remoteIp = null): bool;
}