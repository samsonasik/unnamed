<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.23
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application\Controller\Plugin;

use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\Controller\Plugin\FlashMessenger;

final class Mailing extends AbstractPlugin
{
    /**
     * @var FlashMessenger
     */
    private $flashMessenger;

    /**
     * @var SystemSettings
     */
    private $settings;

    /**
     * @param FlashMessenger $flashMessenger
     * @param SystemSettings $settings
     */
    public function __construct(FlashMessenger $flashMessenger, SystemSettings $settings)
    {
        $this->flashMessenger = $flashMessenger;
        $this->settings = $settings;
    }

    /**
     * @param string $to
     * @param string $toName
     * @param string $subject
     * @param string $message
     * @param string $from
     * @param string $fromName
     *
     * @return bool|FlashMessenger
     */
    public function sendMail($to, $toName, $subject, $message, $from, $fromName)
    {
        $transport = new SmtpTransport();
        $options = new SmtpOptions(
            [
            'host'              => $this->settings->__invoke('mail', 'host'),
            'name'              => $this->settings->__invoke('mail', 'name'),
            'connection_class'  => $this->settings->__invoke('mail', 'connection_class'),
            'connection_config' => [
                'username' => $this->settings->__invoke('mail', 'username'),
                'password' => $this->settings->__invoke('mail', 'password'),
                'ssl'      => $this->settings->__invoke('mail', 'ssl'),
            ],
            'port' => $this->settings->__invoke('mail', 'port'),
            ]
        );
        $htmlPart = new MimePart($message);
        $htmlPart->type = 'text/html';

        $body = new MimeMessage();
        $body->setParts([$htmlPart]);

        $mail = new Message();
        $mail->setFrom($from, $fromName);
        $mail->addTo($to, $toName);
        $mail->setSubject($subject);
        $mail->setEncoding('UTF-8');
        $mail->setBody($body);
        $mail->getHeaders()->addHeaderLine('MIME-Version: 1.0');
        $mail->getHeaders()->addHeaderLine('Content-Type', 'text/html; charset=UTF-8');

        try {
            $transport->setOptions($options);
            $transport->send($mail);

            return true;
        } catch (\Exception $e) {
            return $this->flashMessenger->addMessage('Email not send', 'error');
        }
    }
}
