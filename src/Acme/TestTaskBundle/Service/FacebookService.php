<?php

namespace Acme\TestTaskBundle\Service;

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequestException;
use Facebook\FacebookRequest;
/**
 * Description of FacebookProvider
 *
 * @author bilal
 */

class FacebookService {
    private $router;
    
    function __construct($app_id, $app_secret, $router) {
        FacebookSession::setDefaultApplication($app_id, $app_secret);
        $this->router = $router;
    }
    
    public function getLoginUrl() {
        $route = $this->router->generate('facebook_login', array(), true);
        $helper = new FacebookRedirectLoginHelper($route);
        
        return $helper->getLoginUrl(array('user_friends, user_about_me'));
    }
    
    public function getFriendsData($token) {
        $session = new FacebookSession($token);

        $request = new FacebookRequest(
            $session,
            'GET',
            '/me/friends'
        );   
        
        $response = $request->execute();
        $friends = $response->getGraphObject()->asArray();
        //$name = $friends['data'][0]->name;
        return $friends['data'];        
    }
    
}
