<?php
namespace Security;

use RevoCMS\RevoCMS;

/**
 * Class Password
 * @package Security
 * @author iambluedev
 * @copyright RevoCMS.fr | 2017
 */

class Password {

    /**
     * Make a hashed Password
     *
     * @param string $password
     * @param bool|string $algo
     * @return string
     */

	public static function make(string $password, $algo = false) : string {
	    if($algo){
            return hash($algo, $password);
        }else{
            return hash(self::getEncryptAlgo(), $password);
        }
    }

    /**
     * Verify a couple of password
     *
     * @param string $password
     * @param string $hash
     * @param bool|string $algo
     * @return string
     */

    public static function verify(string $password, string $hash, $algo = false) : string{
        if($algo){
            return (hash($algo, $password) == $hash);
        }else{
            return (hash(self::getEncryptAlgo(), $password) == $hash);
        }
    }

    /**
     * Get the Encryption Algorithm
     *
     * @return string
     */

	private static function getEncryptAlgo() : string {
        $rcms = RevoCMS::getInstance();
		return $rcms->configs->security->get("ENCRYPT_ALGO");
	}
}
