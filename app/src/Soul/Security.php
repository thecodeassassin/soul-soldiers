<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Phalcon Hosting
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Licensed under the Apache license V2
 * @namespace Soul
 */
namespace Soul;

use Phalcon\DI;
use Phalcon\Events\Event,
    Phalcon\Mvc\Dispatcher,
    Phalcon\Mvc\User\Plugin,
    Phalcon\Acl;

/**
 * Class Security
 *
 * @package Soul
 */
class Security extends Module
{

    /**
     * Make sure the user does not reach any page he/she is not authenticated to see
     *
     * @param Event      $event      Incoming event
     * @param Dispatcher $dispatcher Dispatcher
     *
     * @return bool
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {



        //Check whether the "auth" variable exists in session to define the active role
        $auth = $this->getAuthService()->getAuthData();

        if (!$auth) {
            $role = AclBuilder::ROLE_GUEST;

        } else {
            $role = AclBuilder::ROLE_USER;


            if ($auth->getUserType() == AclBuilder::ROLE_ADMIN) {
                $role = AclBuilder::ROLE_ADMIN;
            }
        }


        //Take the active controller/action from the dispatcher
        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();

        //Obtain the ACL list
        $acl = $this->getACL();


        //Check if the Role have access to the controller (resource)
        $allowed = $acl->isAllowed($role, $controller, $action);

        if ($allowed != Acl::ALLOW) {

            // if the user is allowed to perform the action, but simply needs to be logged in,
            // save the page and let the user login
            if (!$auth && $acl->isAllowed(AclBuilder::ROLE_USER, $controller, $action)) {

                $this->session->set('referer', Util::getCurrentUrl());
                $this->response->redirect('login');

                return false;
            }

            //If he doesn't have access forward him to the index controller
            $this->response->redirect('error/notauthenticated')->send();

            return false;

        }

        return true;

    }

    /**
     * @return \Phalcon\Acl\Adapter\Memory
     */
    protected function getACL()
    {
        return $this->di->get('acl');

    }

}
