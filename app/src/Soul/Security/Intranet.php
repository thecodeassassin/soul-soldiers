<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Security;

use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\View;
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

        if (!$this->getAuthService()->isLoggedIn() && !preg_match('/(\/login|\/static|\/forgot-password)/', Util::getCurrentUrl())) {
            $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
            return $this->response->redirect('login');
        }



        return parent::beforeDispatch($event, $dispatcher);
    }
}