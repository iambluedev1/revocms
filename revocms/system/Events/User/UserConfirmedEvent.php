<?php
namespace Events\User;

use Event\Event;

/**
 * Class UserConfirmedEvent
 * @package Events\User
 * @author iambluedev
 * @copyright RevoCMS.fr | 2017
 */

class UserConfirmedEvent extends Event {

    /**
     * User Name
     * @var string
     */
    private $name;

    /**
     * User Email
     * @var string
     */
    private $email;

    /**
     * UserConfirmedEvent constructor.
     * @param string $name
     * @param string $email
     */

    public function __construct(string $name,  string $email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * Get User Name
     *
     * @return string
     */

    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Get User Email
     *
     * @return string
     */

    public function getEmail() : string
    {
        return $this->email;
    }
}