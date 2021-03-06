<?php
/**  
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package SES 
 */  

namespace Soul\Mail\Provider;

use Phalcon\Mvc\View;
use Soul\Module;

use Swift_Image;
use Swift_Mailer;
use Swift_Message as Message;
use Swift_SmtpTransport as Smtp;

/**
 * Class Swift
 *
 * @package Soul\Mail\Provider
 */
class Swift extends AbstractProvider
{
    protected $transport;

    /**
     * Sends e-mails via Swift based on predefined templates
     *
     * @param array $to
     * @param string $subject
     * @param string $templateName
     * @param array $templateParams
     *
     * @return bool|int
     */
    public function send(array $to, $subject, $templateName, array $templateParams)
    {

        // Settings
        $mailSettings = $this->config->mail;

        $message = Message::newInstance();
        $template = $this->getTemplate($templateName, $templateParams);

        // Create the message
        $message
            ->setSubject($subject)
            ->setTo($to)
            ->setFrom([$mailSettings->fromEmail => $mailSettings->fromName]);


        if (preg_match_all('/\[embed\](.*)\[\/embed\]/i', $template, $matches)) {

            foreach ($matches as $match) {

                if (strpos($match[0], 'embed') === false) {
                    $replace = $message->embed(Swift_Image::fromPath($match[0]));
                    $escapedImageUrl = str_replace('/', '\/', $match[0]);

                    $template = preg_replace(
                        '/\[embed\]'.$escapedImageUrl.'\[\/embed\]/i',
                        $replace,
                        $template
                    );
                }
            }
        }

        $message->setBody($template, 'text/html');

        $this->transport = Smtp::newInstance(
            $mailSettings->smtp->server,
            $mailSettings->smtp->port,
            $mailSettings->smtp->security
        )->setUsername($mailSettings->smtp->username)
         ->setPassword($mailSettings->smtp->password);


        // Create the Mailer using your created Transport
        $mailer = Swift_Mailer::newInstance($this->transport);

        return $mailer->send($message);

    }

}