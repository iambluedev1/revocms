<?php
namespace Core;

use App\Controller\ErrorController;
use RevoCMS\RevoCMS;
use Session\Session;
use Web\Url;
use Core\Translate;
use Usage\Arr;
use Usage\Flash;

/**
 * Class View
 * @package Core
 * @author iambluedev
 * @copyright RevoCMS.fr | 2017
 */

class View
{

    /**
     * Used Headers
     * @var array
     */
    private static $headers = array();

    /**
     * Datas
     * @var array
     */
    private static $data = array();

    /**
     * Is in AutoRendering Mode
     * @var bool
     */
    private static $autoRender = true;

    /**
     * Set a Custom Template
     *
     * @param string $name
     */
    public static function setCustomTemplate(string $name){
        $rcms = RevoCMS::getInstance();
        if(@!is_null($rcms->themes->available->$name)){
            $rcms->themes->selected = $rcms->themes->available->$name;
        }
    }

    /**
     * Render files
     *
     * @param array $array
     */

    public static function render(array $array)
    {
        $rcms = RevoCMS::getInstance();
        if(@!is_null($array["plugin"])){
            $path = APPDIR . "Plugin" . DS . $array["plugin"] . DS . "View" . DS . @$rcms->themes->selected->slug . DS . $array["file"] . ".php";
        }else{
            $path = APPDIR . "View" . DS . $array["folder"] . DS . @$rcms->themes->selected->slug . DS . $array["file"] . ".php";
        }
        if(!file_exists($path)) self::displayErrorPage($array["file"], $array["folder"]);

        if(self::$autoRender){
            self::renderTemplate("header");
            self::toRender($path);
            self::renderTemplate("footer");
        }else{
            self::toRender($path);
        }
    }

    /**
     * Render file
     *
     * @param string $path
     */

    private static function toRender(string $path){
        self::sendHeaders();
        $rcms = RevoCMS::getInstance();
        $translate = new Translate();
        $flash = Flash::getInstance();
        $xmlfile = "";
        $data = self::$data;
        if($data != null){
            foreach ($data as $name => $value) {
                ${$name} = $value;
            }

            if(array_key_exists("xml", $data)){
                $xmlfile = $data["xml"];
            }
        }

        if($xmlfile == ""){
            $segment = explode("/", $path);
            $xmlfile = $segment[count($segment)-2];
        }

		if($xmlfile != null){
			if(is_array($xmlfile)){
				$translate->load($xmlfile);
			}else{
				$translate->load(["file" => $xmlfile]);
			}
		}

        if(file_exists($path)){
            require $path;
        }else{
            die("File " . $path  . " not found !");
        }
    }

    /**
     * Set or Add Data(s)
     *
     * @param array $data
     * @param bool $reset
     */

    public static function set(array $data, bool $reset = false){
        if($reset){
            self::$data = $data;
        }else{
            self::$data = Arr::merge(self::$data, $data);
        }
    }

    /**
     * Set or Add Css File(s)
     *
     * @param array|string $data
     */

    public static function css($data){
        if(is_array($data)){
            self::$data["css"] = Arr::merge((!array_key_exists("css", self::$data)) ? array() : self::$data["css"], $data);
        }else{
            self::$data["css"][] = $data;
        }
    }

    /**
     * Set or Add Js File(s)
     *
     * @param array|string $data
     */

    public static function js($data){
        if(is_array($data)){
            self::$data["js"] = Arr::merge((!array_key_exists("js", self::$data)) ? array() : self::$data["js"], $data);
        }else{
            self::$data["js"][] = $data;
        }
    }

    /**
     * Specify the file path for translation
     *
     * @param string $args
     */

    public static function translate(string $args){
		self::$data["xml"] = $args;
    }

    /**
     * Set AutoRender State
     *
     * @param bool $state
     */

    public static function autoRender(bool $state){
        self::$autoRender = $state;
    }

    /**
     * Render a Template File
     *
     * @param string $path
     * @param string $custom
     */

    public static function renderTemplate(string $path, string $custom = "")
    {
        $rcms = RevoCMS::getInstance();
        $lang = $rcms->configs->config->get("LANGUAGE_CODE");
        $translate = new Translate();
        if(@!is_null($rcms->themes->selected->translate->supported->$lang) && @!is_null($rcms->themes->selected->translate->parts->$path)){
            $translate->load(["file" => strtolower($rcms->themes->selected->translate->parts->$path), "template" => true]);
        }
        if ($custom == "") {
            $custom = @$rcms->themes->selected->slug;
        } //$custom == ""
        self::sendHeaders();
        $data = self::$data;
        foreach ($data as $name => $value) {
            ${$name} = $value;
        } //$data as $name => $value
        $path = APPDIR . "Template/$custom/Parts/$path.php";
        if(file_exists($path)){
            require $path;
        }
    }

    /**
     * Add an Header
     *
     * @param string $header
     */

    public function addHeader(string $header)
    {
        self::$headers[] = $header;
    }

    /**
     * Add Headers
     *
     * @param array $headers
     */
    public function addHeaders(array $headers = array())
    {
        self::$headers = Arr::merge(self::$headers, $headers);
    }

    /**
     * Send Headers Method
     */
    public static function sendHeaders()
    {
        if (!headers_sent()) {
            foreach (self::$headers as $header) {
               header($header, true);
            } //self::$headers as $header
        } //!headers_sent()
    }

    /**
     * Display The Error Page
     *
     * @param string $file
     * @param string $folder
     */

    private static function displayErrorPage(string $file, string $folder){
        $router = Router::getInstance();
        $router->invokeObject("App\Controller\ErrorController@index", ["error" => "La vue '" . $file . "' n'existe pas dans le dossier '" . $folder . "'!"]);
        exit();
    }
}
