<?php

/*
 * @author Radko Lyutskanov
 */
class EmailService extends SingletonClass {
    
    public function send($template, $templateContext, $messageConfig = array()) {
        $ci = &get_instance();
        $emailContent = $ci->load->view("emailtemplates/".$template, $templateContext, TRUE);
        
        if(!is_array($messageConfig['to'])) {
            $messageConfig['to'] = array($messageConfig['to']);
        }
        
        foreach($messageConfig['to'] as $receiverEmail) {
            $emailData = array (
                    'message' => $emailContent,
                    'subject' => $messageConfig["subject"],
                    'from' => $messageConfig["from"],
                    'to' => $receiverEmail
            );
            $this->_sendInternal($emailData);
        }
    }
    
    private function _sendInternal($data) {
        $ci = &get_instance();
        $ci->load->library("email");
        $ci->email->from($data['from'],$data['from']);
        $ci->email->to($data['to']);
        $ci->email->subject($data['subject']);
        $ci->email->message($data['message']);
        return $ci->email->send();
    }
    
}
