<?php

class SessionService extends SingletonClass {

    const CART_KEY = "cart_contents";

    public function getAttribute($name) {
        return $this->ci->session->userdata($name);
    }

    public function setAttribute($name, $value) {
        $this->ci->session->set_userdata($name, $value);
    }

    public function removeAttribute($name) {
        $this->ci->session->unset_userdata($name);
    }

    public function getAllAttributes() {
        return $this->ci->session->all_userdata();
    }

    public function getFlashAttribute($name) {
        return $this->ci->session->flashdata($name);
    }

    public function setFlashAttribute($name, $value) {
        $this->ci->session->set_flashdata($name, $value);
    }

    public function clearSession() {
        $this->ci->session->sess_destroy();
    }

}
