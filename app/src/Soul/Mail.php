<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package Mail
 */

namespace Soul;
use Phalcon\Crypt;
use Phalcon\Validation\Validator\Email;
use Soul\Mail\Exception;
use Soul\Mail\Provider\ProviderInterface;
use Soul\Model\User;


/**
 * Class Mail
 *
 * @package Soul
 */
class Mail extends Module
{

    /**
     * @var Mail\Provider\ProviderInterface
     */
    protected $interface;

    /**
     * @param ProviderInterface $provider E-mail provider
     *
     * @throws Mail\Exception
     */
    public function __construct(ProviderInterface $provider)
    {
        if (is_null($provider)) {
            throw new Exception('No provider passed to mailer');
        }

        $this->interface = $provider;
    }

    /**
     * Send email to a user
     *
     * @param Model\User $user
     * @param string $subject
     * @param string $templateName
     * @param array  $templateParams
     *
     * @throws Mail\Exception
     * @return mixed
     */
    public function sendToUser(User $user, $subject, $templateName, $templateParams = [])
    {
        if (is_null($user)) {
            throw new Exception('Please set the user before sending e-mails');
        }

        $unsubscribeLink = Util::encodeUrlSafe($user->email);

        $templateParams = array_merge(compact('user', 'unsubscribeLink'), $templateParams);
        $to = [ $user->email => $user->realName ];

        return $this->interface->send($to, $subject, $templateName, $templateParams);
    }

    /**
     * @param array  $to
     * @param string $subject
     * @param string $templateName
     * @param array  $templateParams
     * @return mixed
     */
    public function send(array $to, $subject, $templateName, array $templateParams = [])
    {
        $templateParams = array_merge(compact($subject), $templateParams);
        return $this->interface->send($to, $subject, $templateName, $templateParams);
    }
}
