<?php
/**  
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package SES 
 */  

namespace Soul\Mail\Provider;

use Phalcon\Mvc\View;
use Soul\Module;

use Swift_Mailer;
use Swift_Message as Message;
use Swift_SmtpTransport as Smtp;

/**
 * Class SES
 *
 * @package Soul\Mail\Provider
 */
class SES extends AbstractProvider
{
    protected $transport;

    protected $amazonSes;

    protected $directSmtp = true;

    /**
     * Send a raw e-mail via AmazonSES
     *
     * @param string $raw
     *
     * @throws Exception
     * @return bool
     */
    private function amazonSESSend($raw)
    {
        if ($this->amazonSes == null) {
            $this->amazonSes = new \AmazonSES([
                    'key' => $this->config->amazon->AWSAccessKeyId,
                    'secret' => $this->config->amazon->AWSSecretKey
                ]
            );
//            $this->amazonSes->disable_ssl_verification();
        }

        $response = $this->amazonSes->send_raw_email([
            'Data' => base64_encode($raw)
        ],[
            'curlopts' => array(
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0
            )
        ]);

        if (!$response->isOK()) {
            throw new Exception('Error sending email from AWS SES: ' . $response->body->asXML());
        }

        return true;
    }



    /**
     * Sends e-mails via AmazonSES based on predefined templates
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

        $template = $this->getTemplate($templateName, $templateParams);

        // Create the message
        $message = Message::newInstance()
            ->setSubject($subject)
            ->setTo($to)
            ->setFrom([$mailSettings->fromEmail => $mailSettings->fromName])
            ->setBody($template, 'text/html');

        if ($this->directSmtp) {

            if (!$this->transport) {
                $this->transport = Smtp::newInstance(
                    $mailSettings->smtp->server,
                    $mailSettings->smtp->port,
                    $mailSettings->smtp->security
                )->setUsername($mailSettings->smtp->username)
                 ->setPassword($mailSettings->smtp->password);
            }

            // Create the Mailer using your created Transport
            $mailer = Swift_Mailer::newInstance($this->transport);

            return $mailer->send($message);
        } else {
            return $this->amazonSESSend($message->toString());
        }
    }
} 