<?php

namespace CyberEd\Core\Helpers;
use Mail;
use CyberEd\Core\Mail\EmailTemplate;
class MailHelper
{
    public static function sendMail($to,$details){

        
		
        try {
            $resp = Mail::to($to)->send(new EmailTemplate($details));
            return 1;

		
		} catch (Exception $e) {
			return $e;
		}
    }
}