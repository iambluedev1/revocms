<?php
namespace Events\Plugin;

use Event\Event;

/**
 * Class PluginLoadEvent
 * @package Events\Plugin
 * @author iambluedev
 * @copyright RevoCMS.fr | 2017
 */

class PluginLoadEvent extends Event {

    /**
     * File Path
     * @var string
     */
    private $filePath;

    /**
     * Config
     * @var object
     */
    private $content;

    /**
     * Slug
     * @var string
     */
	private $slug;

    /**
     * PluginLoadEvent constructor.
     * @param string $filePath
     * @param object $content
     * @param string $slug
     */

    public function __construct(string $filePath, object $content, string $slug){
        $this->filePath = $filePath;
        $this->content = $content;
		$this->slug = $slug;
    }

    /**
     * Get File Path
     *
     * @return string
     */

    public function getFilePath() : string{
        return $this->filePath;
    }

    /**
     * Get Config
     *
     * @return object
     */

    public function getConfig() : object{
        return $this->content;
    }

    /**
     * Get Slug
     *
     * @return string
     */

	public function getSlug() : string{
        return $this->slug;
    }
}

