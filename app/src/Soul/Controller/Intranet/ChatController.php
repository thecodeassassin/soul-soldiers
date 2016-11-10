<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package AccountController
 */

namespace Soul\Controller\Intranet;

use Phalcon\Mvc\View;
use Soul\Controller\Base;
use Soul\Model\User;
use Soul\Auth\Data as AuthData;
use Soul\Util;

/**
 * Class Account
 * @package Soul\Controller
 */
class ChatController extends Base
{


    public function indexAction()
    {
        
        /** @var $userdata AuthData **/
        $userData = $this->authService->getAuthData();
        
        // $this->view->pubnub = $this->config->pubnub;
        // $this->view->chatUser = sprintf('%s (%s)', $userData->nickName, $userData->realName);
        $this->view->chatImageHash = md5(strtolower($userData->email));
        
        $baseUrl = parse_url(BASE_URL);
        $this->view->chatHost = 'ws://' . $baseUrl['host'] . ':8081';
        
        $this->assets->collection('scripts')->addJs('js/intranet/pubnub.js');
        $this->assets->collection('scripts')->addJs('js/intranet/chat.js');
        
    }

}