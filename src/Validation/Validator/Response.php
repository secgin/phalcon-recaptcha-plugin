<?php

namespace YG\Phalcon\Recaptcha\Validation\Validator;

use Phalcon\Validation;
use Phalcon\Validation\AbstractValidator;
use YG\Phalcon\Recaptcha\Recaptcha as RecaptchaService;

class Response extends AbstractValidator
{
    public function validate(Validation $validation, $field): bool
    {
        $value = $validation->getValue('g-recaptcha-response');
        if (empty($value))
        {
            $validation->appendMessage($this->messageFactory($validation, $field));
            return false;
        }

        $recaptcha = new RecaptchaService($this->getOption('secretKey'), $this->getOption('apiUrl'));

        $result = $recaptcha->verify($value);
        if (!$result)
        {
            $validation->appendMessage($this->messageFactory($validation, $field));
            return false;
        }
        return true;
    }
}