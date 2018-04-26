<?php
namespace Events\Api;

use Event\Event;

/**
 * Class NewsletterRemovedEvent
 * @package Events\Api
 * @author iambluedev
 * @copyright RevoCMS.fr | 2017
 */

class NewsletterRemovedEvent extends Event {

    /**
     * User Email
     * @var string
     */
    private $email;

    /**
     * NewsletterRemovedEvent constructor.
     * @param string $email
     */
    public function __construct(string $email){
        $this->email = $email;
    }

    /**
     * Get User Email
     *
     * @return string
     */
    public function getEmail() : string {
        return $this->email;
    }

}