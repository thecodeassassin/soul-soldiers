<?php
/**  
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
*/

namespace Soul\Mail\Provider;


/**
 * Interface ProviderInterface
 *
 * @package Soul\Mail\Provider
 */
interface ProviderInterface
{

    /**
     * Send the email
     *
     * @param array  $to             Recipient (email, name)
     * @param string $subject        Subject
     * @param string $templateName   Template name
     * @param array  $templateParams Template parameters
     */
    public function send(array $to, $subject, $templateName, array $templateParams);

} 