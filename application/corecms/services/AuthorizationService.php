<?php

/*
 * @author Radko Lyutskanov
 */

class AuthorizationService extends SingletonClass {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Check if admin is logged in
     */
    public function isAdminLoggedIn() {
        $user = $this->getUser();
        return $user != NULL && $user->adminRights == 1;
    }

    /**
     * Set user in the session
     */
    public function setUser($user) {
        $this->ci->session->set_userdata("user", $user);
        $this->ci->session->set_userdata("adminRights", $user->adminRights);
    }

    /**
     * Remove user from the session
     */
    public function unsetUser() {
        $this->ci->session->unset_userdata("user");
        $this->ci->session->unset_userdata("adminRights");
    }

    /**
     * Get logged in user
     */
    public function getUser() {
        return $this->ci->session->userdata("user");
    }

}
