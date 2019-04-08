<?php
namespace Events\User;

use Event\Event;

/**
 * Class UserCreateAccountEvent
 * @package Events\User
 * @author iambluedev
 * @copyright RevoCMS.fr | 2017
 */

class UserCreateAccountEvent extends Event {

    /**
     * User Name
     * @var string
     */
    private $name;

    /**
     * User ID
     * @var string
     */
    private $id;

    /**
     * User Token
     * @var string
     */
    private $token;

    /**
     * User Email
     * @var string
     */
    private $email;

    /**
     * UserCreateAccountEvent constructor.
     * @param string $name
     * @param string $id
     * @param string $token
     * @param string $email
     */

    public function __construct(string $name, string $id, string $token, string $email)
    {
        $this->name = $name;
        $this->id = $id;
        $this->token = $token;
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
     * Get User ID
     *
     * @return string
     */

    public function getId() : string
    {
        return $this->id;
    }

    /**
     * Get User Token
     *
     * @return string
     */

    public function getToken() : string
    {
        return $this->token;
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