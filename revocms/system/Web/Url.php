<?php
namespace Web;

use Session\Session;
use Web\Inflector;
use RevoCMS\RevoCMS;

/**
 * Class Url
 * @package Web
 * @author iambluedev
 * @copyright RevoCMS.fr | 2017
 */

class Url
{

    /**
     * Redirect
     *
     * @param string|null $url
     * @param bool $fullpath
     * @param int $code
     */
    public static function redirect(string $url = null, bool $fullpath = false, int $code = 200)
    {
        $rcms = RevoCMS::getInstance();
        $url     = ($fullpath === false) ? $rcms->configs->config->get("SITEURL") . "/" .$url : $url;
        
        if ($code == 200) {
            header('Location: //' . $url);
        } //$code == 200
        else {
            header('Location: //' . $url, true, $code);
        }
        exit;
    }

    /**
     * Get Main Url
     *
     * @return string
     */

    public static function getMainUrl() : string {
        return BASE_URL;
        exit;
    }

    /**
     * Redirect to Main Page
     */

    public static function mainPage()
    {
        header('Location: /');
        exit;
    }

    /**
     * Detect Uri
     *
     * @param bool $domain
     * @return string
     */

    public static function detectUri(bool $domain = false) : string
    {
		$requestUri = (!$domain) ? $_SERVER['REQUEST_URI'] : $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $scriptName = $_SERVER['SCRIPT_NAME'];
        
        $pathName = dirname($scriptName);
        
        if (strpos($requestUri, $scriptName) === 0) {
            $requestUri = substr($requestUri, strlen($scriptName));
        } //strpos($requestUri, $scriptName) === 0
        else if (strpos($requestUri, $pathName) === 0) {
            $requestUri = substr($requestUri, strlen($pathName));
        } //strpos($requestUri, $pathName) === 0
        
        $uri = parse_url(ltrim($requestUri, '/'), PHP_URL_PATH);
        
        if (!empty($uri)) {
            return str_replace(array(
                '//',
                '../'
            ), '/', $uri);
        } //!empty($uri)
        
        return '/';
    }

    /**
     * Get a File in a Template Path
     *
     * @param string $file
     * @return string
     */

    public static function templatePath(string $file = "") : string
    {
        $rcms = RevoCMS::getInstance();
        $custom = $rcms->themes->selected->slug;
        return '/assets/' . $custom . "/" . $file;
    }

    /**
     * Get a File in a Relative Template Path
     *
     * @param string $custom
     * @param string $folder
     * @return string
     */

    public static function relativeTemplatePath(string $custom = "", string $folder = "/") : string
    {
        $rcms = RevoCMS::getInstance();
        if ($custom == "") {
            $custom = $rcms->themes->selected->slug;
        } //$custom == ""
        return '/assets/' . $custom . $folder;
    }

    /**
     * Redirect To Previous Visited Page
     */
    public static function previous()
    {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    /**
     * Get Segmented Uri
     *
     * @return array
     */

    public static function segments() : array
    {
        return explode('/', $_SERVER['REQUEST_URI']);
    }

    /**
     * Get Segment
     *
     * @param array $segments
     * @param string $id
     * @return mixed
     */

    public static function getSegment(array $segments, string $id)
    {
        if (array_key_exists($id, $segments)) {
            return $segments[$id];
        } //array_key_exists($id, $segments)
    }

    /**
     * Get Last Segment
     *
     * @param array $segments
     * @return mixed
     */

    public static function lastSegment(array $segments)
    {
        return end($segments);
    }

    /**
     * Get First Segment
     *
     * @param array $segments
     * @return mixed
     */

    public static function firstSegment(array $segments)
    {
        return $segments[0];
    }

    /**
     * Get Url
     *
     * @param string $url
     * @param bool $useMainDomain
     * @return string
     */

    public static function getUrl(string $url, bool $useMainDomain = true) : string
    {
        $rcms = RevoCMS::getInstance();
        return (($useMainDomain) ? "//" .$rcms->configs->config->get("SITEURL") : "") . BASE_URL . trim($url, '/');
    }
}
