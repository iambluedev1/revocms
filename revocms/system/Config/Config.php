<?php
namespace Config;

use RevoCMS\RevoCMS;
use Events\Config\ConfigSaveEvent;
use Events\Config\ConfigLoadEvent;
use Cache\Cache;

/**
 * Class Config
 *
 * @author iambluedev
 * @package Config
 * @copyright RevoCMS.fr | 2017
 */

class Config
{
    /**
     * The name of the config file
     *
     * @var string
     */
	private $_iniFilename = "";

    /**
     *
     * @var array
     */
	private $_iniParsedArray = array();

    /**
     * Config constructor.
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $rcms = RevoCMS::getInstance();
        switch ($filename) {
            case "database":
                $filename = ROOTDIR . "config" . DS . "database.ini";
                break;
            case "config":
                $filename = ROOTDIR . "config" . DS . "config.ini";
                break;
        } //$filename
        $this->_iniFilename = $filename;

        $cache_name = "config_" . $filename;
        $cache = new Cache(["name" => $cache_name, "path" => TMPDIR . "cache/configs/", "extension" => ".cache"]);
        if (!$cache->isCached($cache_name)) {
            $cache->store($cache_name, parse_ini_file($filename, true), 60*5);
        }
        $this->_iniParsedArray = $cache->retrieve($cache_name);

        $rcms->events->raise(new ConfigLoadEvent($this->_iniFilename, $this->_iniParsedArray));
    }

    /**
     * Get Data
     *
     * @param string $key
     * @return string
     */
    public function get(string $key) : string
    {
        return $this->_iniParsedArray[$key];
    }

    /**
     * Set Data
     *
     * @param string $key
     * @param mixed|NULL $value
     * @return bool
     */
	public function set(string $key, mixed $value = NULL) : bool
    {
        return $this->_iniParsedArray[$key] = $value;
    }

    /**
     * Remove Data
     *
     * @param string $key
     */

    public function remove(string $key)
    {
        unset($this->_iniParsedArray[$key]);
    }

    /**
     * Save Datas
     *
     * @param string|null $filename
     * @return bool
     */

    public function save(string $filename = null) : bool
    {
        $rcms = RevoCMS::getInstance();
        if ($filename == null)
            $filename = $this->_iniFilename;
        if (is_writeable($filename)) {
            $SFfdescriptor = fopen($filename, "w");
            foreach ($this->_iniParsedArray as $key => $value) {
                fwrite($SFfdescriptor, "$key = $value\n");
            }
            fclose($SFfdescriptor);
            $rcms->events->raise(new ConfigSaveEvent($filename, $this->_iniParsedArray));
            return true;
        }
        else {
            return false;
        }
    } 
	
}
?>