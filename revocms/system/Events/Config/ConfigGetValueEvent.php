<?php
namespace Events\Config;

use Event\Event;

/**
 * Class ConfigGetValueEvent
 * @package Events\Config
 * @author iambluedev
 * @copyright RevoCMS.fr | 2017
 */

class ConfigGetValueEvent extends Event {

    /**
     * File Path
     * @var string
     */
	private $filePath;

    /**
     * Key
     * @var string
     */
	private $key;

    /**
     * Value
     * @var string
     */
	private $value;

    /**
     * ConfigGetValueEvent constructor.
     * @param string $filePath
     * @param string $key
     * @param string $value
     */

	public function __construct( string $filePath, string $key, string $value){
		$this->filePath = $filePath;
		$this->key = $key;
		$this->value = $value;
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
     * Get Key
     *
     * @return string
     */

	public function getKey() : string {
		return $this->key;
	}

    /**
     * Get Value
     *
     * @return string
     */

	public function getValue() : string {
		return $this->value;
	}
	
}

