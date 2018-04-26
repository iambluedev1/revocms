<?php
namespace Core;

use RevoCMS\RevoCMS;
use Usage\Arr;

/**
 * Class Translate
 * @package Core
 * @author iambluedev
 * @copyright RevoCMS.fr | 2017
 */

class Translate {

    /**
     * Defined the Language to be use
     * @var string
     */
	private $language;

    /**
     * Xml Datas of the xml file
     * @var \SimpleXMLElement
     */
	private $xml;

    /**
     * Default Template Name
     * @var string
     */
	private $template;

    /**
     * Translate constructor.
     *
     * @param string|null $file
     */

	public function __construct(string $file = null) {
        $rcms = RevoCMS::getInstance();
		$this->language = $rcms->configs->config->get("LANGUAGE_CODE");
		$this->template = $rcms->configs->config->get("TEMPLATE");
		if(file_exists(APPDIR . "Translate" . DS . $this->language . DS . $file . ".xml")){
			$this->xml = simplexml_load_file(APPDIR . "Translate" . DS . $this->language . DS . $file . ".xml");
		}
	}

    /**
     * Load Xml File
     *
     * @param array $args
     */
	public function load(array $args){
		$template = (@!is_null($args["template"])) ? $args["template"] : false;
		$plugin = (@!is_null($args["plugin"])) ? true : false;
		$file = $args["file"];
		
		if(@is_null($file)){
			echo "nope";
			die();
		}
		
		if($template){
			$this->xml = @simplexml_load_file(APPDIR . "Template" . DS . $this->template . DS . "Translate" . DS . $this->language . DS . $file . ".xml");
		}else if($plugin){
			$this->xml = @simplexml_load_file(APPDIR . "Plugin" . DS . $args["plugin"] . DS . "Translate" . DS . $this->language . DS . $file . ".xml");
		}else{
			$this->xml = @simplexml_load_file(APPDIR . "Translate" . DS . $this->language . DS . $file . ".xml");
		}
	}

    /**
     * Get Data(s)
     *
     * @param string[] ...$a
     * @return array|mixed|null|string|string[]
     */

	public function element(string ...$a){
		$xml = json_decode(json_encode((array)$this->xml), TRUE);
		$path = "";
		if(is_array($a)){
			for($i = 0; $i < count($a); $i++){
				if($i + 1 == count($a)){
					$path .= $a[$i];
				}else{
					$path .= $a[$i].".";
				}
			}
		}else{
			$path = $a;
		}
		return (Arr::get($xml, $path) != null) ? Arr::get($xml, $path) : $path;
	}
	
}