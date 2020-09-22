<?php
namespace etc\components;
use etc\app\App;

class SwiftMailer
{
    private $server;
    private $login;
    private $password;
    private $enc;
    private $port;
    private $templatePath = __DIR__ . '/../../mail';
    private $mailer;

    public function __construct($config)
    {
        // var_dump($config['smtp']);
        if(isset($config['smtp'])){
            $this->server = $config['smtp'];
        }
        if(isset($config['login'])){
            $this->login = $config['login'];
        }
        if(isset($config['password'])){
            $this->password = $config['password'];
        }
        if(isset($config['encription'])){
            $this->enc = $config['encription'];
        }
        if(isset($config['port'])){
            $this->port = $config['port'];
        }
    }

    public function compose($view, $args = [])
    {
        $body = App::i()->ui->render($this->templatePath, $view, $args);
        $message = new \Swift_Message();
        $message->setBody($body, 'text/html');
        return $message;
    }

    public function send($message)
    {
        $transport = (new \Swift_SmtpTransport($this->server, $this->port, $this->enc))
                    ->setUsername($this->login)
                    ->setPassword($this->password);
        $mailer = new \Swift_Mailer($transport);
        return $mailer->send($message);
    }
}