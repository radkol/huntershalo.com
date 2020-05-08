<?php

/*
 * @author Radko Lyutskanov
 */
class CaptchaService extends SingletonClass {
    
    /**
     * Generate new captcha image. 
     * Set the generated image in the session.
     * This can be use to check if form is submitted properly.
     */
    public function generateCaptchaCode() {
        $this->ci->load->helper("captcha");
        $adminStaticPath = adminStaticPath();
        $adminStaticPath .= "captcha/";
        
        $vals = array(
            'word' => $this->generateRandomWord(),
            'img_path' => $adminStaticPath."images/",
            'img_url' => adminResourcePath('',"captcha/images/"),
            'font_path' => $adminStaticPath."captcha.ttf",
            'img_width' => 120,
            'img_height' => 50,
            'expiration' => 60
            );
        
        $cap = create_captcha($vals);
        $cap["image"] = adminResourcePath($cap["time"].".jpg","captcha/images");
        $this->ci->session->set_userdata("captcha",$cap["word"]);
        
        return $cap;
        
    }   
    
    private function generateRandomWord() {
        return substr(md5(microtime()),rand(0,26),5);
    }
    
}
