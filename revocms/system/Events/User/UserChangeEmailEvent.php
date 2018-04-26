<?php
namespace Events\User;

use Event\Event;

/**
 * Class UserChangeEmailEvent
 * @package Events\User
 * @author iambluedev
 * @copyright RevoCMS.fr | 2017
 */

class UserChangeEmailEvent extends Event {

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
     * User Old Email
     * @var string
     */
    private $old_email;

    /**
     * User New Email
     * @var string
     */
    private $new_email;

    /**
     * UserChangeEmailEvent constructor.
     * @param string $name
     * @param string $id
     * @param string $token
     * @param string $old_email
     * @param string $new_email
     */

    public function __construct(string $name, string $id, string $token, string $old_email, string $new_email)
    {
        $this->name = $name;
        $this->id = $id;
        $this->token = $token;
        $this->old_email = $old_email;
        $this->new_email = $new_email;
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
     * Get USer Token
     *
     * @return string
     */

    public function getToken() : string
    {
        return $this->token;
    }

    /**
     * Get New User Email
     *
     * @return string
     */

    public function getNewEmail() : string
    {
        return $this->new_email;
    }

    /**
     * Get Old User Email
     *
     * @return string
     */

    public function getOldEmail() : string
    {
        return $this->old_email;
    }
}