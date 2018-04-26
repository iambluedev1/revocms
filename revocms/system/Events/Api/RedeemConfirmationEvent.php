<?php
namespace Events\Api;

use Event\Event;

/**
 * Class RedeemConfirmationEvent
 * @package Events\Api
 * @author iambluedev
 * @copyright RevoCMS.fr | 2017
 */

class RedeemConfirmationEvent extends Event {

    /**
     * Username
     * @var string
     */
    private $name;

    /**
     * User id
     * @var string
     */
    private $id;

    /**
     * User token
     * @var string
     */
    private $token;

    /**
     * User email
     * @var string
     */
    private $email;

    /**
     * RedeemConfirmationEvent constructor.
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
     * Get User Id
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