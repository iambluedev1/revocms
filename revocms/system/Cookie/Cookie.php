<?php
namespace Cookie;

/**
 * Class Cookie
 *
 * @author iambluedev
 * @package Cookie
 * @copyright RevoCMS.fr | 2017
 */

class Cookie
{

    /**
     * Four years in seconds
     *
     * @const long
     */
    private const FOURYEARS = 126144000;

    /**
     * Use Cookie
     *
     * @var bool
     */
	private static $usage = true;

    /**
     * Check if the key exist
     *
     * @param string $key
     * @return bool
     */
    public static function exists(string $key) : bool
    {
        if (isset($_COOKIE[$key])) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Set Cookie
     *
     * @param string $key
     * @param string $value
     * @param int $expiry
     * @param string $path
     * @param bool $domain
     * @return bool
     */
    public static function set(string $key, string $value, $expiry = self::FOURYEARS, string $path = "/", bool $domain = false) : bool
    {
		self::$usage = true;
        $retval = false;
        
        if (!headers_sent()) {
            if ($domain === false) {
                $domain = $_SERVER['HTTP_HOST'];
            }
            
            if ($expiry === -1) {
                $expiry = 1893456000;
            }
            else if (is_numeric($expiry)) {
                $expiry += time();
            }
            else {
                $expiry = strtotime($expiry);
            }
            
            $retval = @setcookie($key, $value, $expiry, $path, $domain);
            
            if ($retval) {
                $_COOKIE[$key] = $value;
            }
        }
        
        return $retval;
    }

    /**
     * Get Value
     *
     * @param string $key
     * @param string $default
     * @return string
     */
    public static function get(string $key, string $default = '') : string
    {
		self::$usage = true;
        return (isset($_COOKIE[$key]) ? $_COOKIE[$key] : $default);
    }

    /**
     * Return Cookies
     *
     * @return array
     */
    public static function display() : array
    {
        return $_COOKIE;
    }

    /**
     * Destroy cookie
     *
     * @param string $key
     * @param string $path
     * @param string $domain
     */
    public static function destroy(string $key, string $path = "/", string $domain = "")
    {
        unset($_COOKIE[$key]);
        setcookie($key, '', time() - 3600, $path, $domain);
    }

    /**
     * Is Cookie Activate
     *
     * @return bool
     */
	public static function isActivate() : bool
	{
		return self::$usage;
	}
}
