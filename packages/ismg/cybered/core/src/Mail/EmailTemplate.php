<?php
namespace CyberEd\Core\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
  
class EmailTemplate extends Mailable
{
    use Queueable, SerializesModels;
  
    public $details;
  
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }
  
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->details['subject'];
        if($this->details['type'] =='reqister'){
        
            return $this->subject($subject)->view('view_page::emails.user_register');
        }
        if($this->details['type'] =='invite'){
            return $this->subject($subject)->view('view_page::emails.user_invite');
        }
        if($this->details['type'] =='forgot'){
            return $this->subject($subject)->view('view_page::emails.forgot_password');
        }
        if($this->details['type'] =='resetPassword'){
            return $this->subject($subject)->view('view_page::emails.reset_password');
        }
        
    }
}