<?php
namespace Events\User;

use Event\Event;

/**
 * Class UserActivateSecurityEvent
 * @package Events\User
 * @author iambluedev
 * @copyright RevoCMS.fr | 2017
 */

class UserActivateSecurityEvent extends Event {

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
     * Security Name
     * @var string
     */
    private $security_name;

    /**
     * UserActivateSecurityEvent constructor.
     * @param string $name
     * @param string $email
     * @param string $security_name
     */

    public function __construct(string $name,  string $email, string $security_name)
    {
        $this->name = $name;
        $this->email = $email;
        $this->security_name = $security_name;
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

    /**
     * Get Security Name
     *
     * @return string
     */

    public function getSecurityName() : string
    {
        return $this->security_name;
    }

}