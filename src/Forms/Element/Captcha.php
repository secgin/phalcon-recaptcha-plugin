<?php

namespace YG\Phalcon\Recaptcha\Forms\Element;

use Phalcon\Forms\Element\AbstractElement;

class Captcha extends AbstractElement
{
    private string $siteKey;

    public function __construct($name, string $siteKey, array $attributes = [])
    {
        parent::__construct($name, $attributes);
        $this->siteKey = $siteKey;
    }

    public function render(array $attributes = []): string
    {
        return '<div class="g-recaptcha" data-sitekey="' . $this->siteKey . '"></div>';
    }
}