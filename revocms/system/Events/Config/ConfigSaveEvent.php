<?php
namespace Events\Config;

use Event\Event;

/**
 * Class ConfigSaveEvent
 * @package Events\Config
 * @author iambluedev
 * @copyright RevoCMS.fr | 2017
 */

class ConfigSaveEvent extends Event {

    /**
     * File Path
     * @var string
     */
	private $filePath;

    /**
     * Config Datas
     * @var array
     */
	private $ini;

    /**
     * ConfigSaveEvent constructor.
     * @param string $filePath
     * @param array $ini
     */
	public function __construct(string $filePath, array $ini){
		$this->filePath = $filePath;
		$this->ini = $ini;
	}

    /**
     * Get File Path
     *
     * @return string
     */
	public function getFilePath() : string {
		return $this->filePath;
	}

    /**
     * Get Config Datas
     *
     * @return array
     */
	public function getIni() : array {
		return $this->ini;
	}
	
}

