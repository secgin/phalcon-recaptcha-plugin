# Phalcon Framework Google reCAPTCHA v2 Eklentisi

Phalcon Framework ile Google reCAPTCHA v2 kullanımı için geliştirilmiş bir eklentidir.

## Örnek Bir Login İşlemi İçin Recaptcha Kullanımı

### Ayarlar
Projenizin config dosyasına aşağıdaki ayarları ekleyin.

```php
<?php

return new Phalcon\Config([
    'recaptcha' => [
        'apiUrl' => 'https://www.google.com/recaptcha/api/siteverify', // Zorunlu değil varsayılan olarak bu değeri kullanır
        'siteKey' => '',
        'secretKey' => ''
    ]
]);
```

### Form Sınıfı
```php
<?php

class LoginForm extends Form
{
    public function initialize()
    {
        $username = new Text('username');
        $username->setLabel('Username');
        $username->addValidators([
            new PresenceOf(['message' => 'Username is required']),
            new StringLength(['min' => 3, 'messageMinimum' => 'Username is too short. Minimum 3 characters'])
        ]);
        $this->add($username);

        $password = new Password('password');
        $password->setLabel('Password');
        $password->addValidators([
            new PresenceOf(['message' => 'Password is required']),
            new StringLength(['min' => 8, 'messageMinimum' => 'Password is too short. Minimum 8 characters'])
        ]);
        $this->add($password);

        $recaptcha = new Captcha('recaptcha', $this->config->path('recaptcha.siteKey'));
        $recaptcha->setLabel('Recaptcha');
        $recaptcha->addValidators([
            new Response(
                [
                    'message' => 'Recaptcha is required',
                    'secretKey' => $this->config->path('recaptcha.secretKey'),
                    'apiUrl' => $this->config->path('recaptcha.apiUrl')
                ])
        ]);
        $this->add($recaptcha);


        $submit = new Submit('submit');
        $submit->setDefault('Login');
        $this->add($submit);
    }
}
```

### Controller Sınıfı
```php
<?php
class IndexController extends Controller
{
    public function indexAction()
    {
        $loginForm = new LoginForm();

        if ($this->request->isPost())
        {
            $isValid = $loginForm->isValid($this->request->getPost());

            $userMessage = '';
            if ($isValid)
                $userMessage = 'Login successful';
            else
            {
                foreach ($loginForm->getMessages() as $message)
                    $userMessage .= $message->getMessage() . '<br>';
            }
        }

        $this->view->setVars([
            'form' => new LoginForm(),
            'userMessage' => $userMessage ?? null
        ]);
    }
}
```