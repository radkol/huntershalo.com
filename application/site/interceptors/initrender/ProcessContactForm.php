<?php

class ProcessContactForm implements CmsInterceptor {

    public function priority() {
        return 5;
    }

    public function run() {
        
        $ci = &get_instance();
        //Get if there is a button submission
        $requestService = RequestService::instance();
        $sessionService = SessionService::instance();
        
        $contactsubmit = $requestService->getParam("contactformsubmit");
        $currentUri = $requestService->getUri();
        $contactPageUri = $requestService->getAttribute("contactPage")->url;
        
       
        if ($currentUri == $contactPageUri && $contactsubmit) {
             
            //Load library and set validation rules for the form
            $this->setValidationRules();
            if ($ci->form_validation->run() !== FALSE) {

                // Send notification for valid form submission
                $this->sendNotifications();
                
                $sessionService->setFlashAttribute("contactSuccess", TRUE);
                // Show success message on the next page request.
                redirect($currentUri);
            }
        }
    }

    private function setValidationRules() {
        $ci = &get_instance();
        $ci->load->library('form_validation');
        //s$ci->form_validation->set_error_delimiters('<p>', '</p>');
        $ci->form_validation->set_rules("fullname", "Full Name", "required|min_length[5]");
        $ci->form_validation->set_rules("ordernumber", "Order Number", "");
        $ci->form_validation->set_rules("phone", "Phone", "required|min_length[3]");
        $ci->form_validation->set_rules("subject", "Subject", "required|min_length[3]");
        $ci->form_validation->set_rules("email", "Email", "required|valid_email");
        $ci->form_validation->set_rules("message", "Message", "required|min_length[10]");
        //$ci->form_validation->set_rules("captcha", "Captcha", "callback_checkCaptcha");
    }

    private function sendNotifications() {
        $ci = &get_instance();

        $senderConfig = getEmailSenderConfig();
        $emailSubjects = getEmailSubjects();

        $emailContext = array(
            "fullname" => $ci->input->post('fullname'),
            "ordernumber" => $ci->input->post('ordernumber'),
            "subject" => $ci->input->post('subject'),
            "phone" => $ci->input->post('phone'),
            "email" => $ci->input->post('email'),
            "message" => $ci->input->post('message'),
        );

        $messageConfig = array(
            'subject' => $emailSubjects['contactform'],
            'from' => $senderConfig['email'],
            'to' => getInternalEmailAddress()
        );
        
        EmailService::instance()->send("contactform", $emailContext, $messageConfig);
    }

}
