<?php

namespace YG\Recaptcha;

class Recaptcha implements RecaptchaInterface
{
    private string $apiUrl;

    private string $secretKey;

    public function __construct(string $secretKey, ?string $apiUrl)
    {
        $this->apiUrl = $apiUrl == ''
            ? 'https://www.google.com/recaptcha/api/siteverify'
            : $apiUrl;
        $this->secretKey = $secretKey;
    }

    public function verify(string $response, string $remoteIp = null): bool
    {
        $options = [
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ["Content-Type" => "application/json"],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => [
                'secret' => $this->secretKey,
                'response' => $response
            ]
        ];

        $ch = curl_init($this->apiUrl);
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);

        $requestResult = false;
        if ($result !== false)
        {
            $result = json_decode($result);
            $requestResult = $result->success;
        }

        curl_close($ch);
        return $requestResult;
    }
}