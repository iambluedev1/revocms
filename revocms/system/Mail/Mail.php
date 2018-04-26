<?php
namespace Mail;

use RevoCMS\RevoCMS;
use Mail\PHPMailer\PhpMailer;

/**
 * Class Mail
 * @package Mail
 * @author iambluedev
 * @copyright RevoCMS.fr | 2017
 */

class Mail extends PhpMailer
{

    /**
     * From Email
     *
     * @var string
     */

    public $From = 'noreply@vulkan-mc.fr';
    // public $FromName;
    // public $Host     = 'smtp.gmail.com';
    // public $Mailer   = 'smtp';
    // public $SMTPAuth = true;
    // public $Username = 'email';
    // public $Password = 'password';
    // public $SMTPSecure = 'tls';
    public $WordWrap = 75;

    /**
     * Mail constructor.
     */

    public function __construct()
    {
        parent::__construct();
        $revocms = RevoCMS::getInstance();
        $this->FromName = $revocms->configs->config->get("SITETITLE");
    }

    /**
     * Set The Subject
     *
     * @param string $subject
     */
    
    public function subject(string $subject)
    {
        $this->Subject = $subject;
    }

    /**
     * Set The Body
     *
     * @param string $body
     */

    public function body(string $body)
    {
        $this->Body = $body;
    }

    /**
     * Send Email
     *
     * @return bool
     */

    public function send() : bool
    {
		$this->CharSet = 'UTF-8';
        $this->AltBody = strip_tags(stripslashes($this->Body)) . "\n\n";
        $this->AltBody = str_replace("&nbsp;", "\n\n", $this->AltBody);
        return parent::send();
    }
}
