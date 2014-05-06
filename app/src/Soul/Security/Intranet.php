<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Security;

use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Soul\Security;
use Soul\Util;

/**
 * Class Intranet
 *
 * @package Soul\Security
 */
class Intranet extends Security
{
    /**
     * @param Event      $event
     * @param Dispatcher $dispatcher
     *
     * @return bool|void
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {

        if (!$this->getAuthService()->isLoggedIn() && !preg_match('/(\/login|\/static)/', Util::getCurrentUrl())) {
            return $this->response->redirect('login');
        }

        parent::beforeDispatch($event, $dispatcher);
    }
}