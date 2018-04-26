<?php
namespace Events\Theme;

use Event\Event;

/**
 * Class ThemeLoadEvent
 * @package Events\Theme
 * @author iambluedev
 * @copyright RevoCMS.fr | 2017
 */

class ThemeLoadEvent extends Event {

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
     * ThemeLoadEvent constructor.
     * @param string $filePath
     * @param object $content
     */

    public function __construct(string $filePath, object $content){
        $this->filePath = $filePath;
        $this->content = $content;
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
     * Get Config
     *
     * @return object
     */

    public function getConfig() : object {
        return $this->content;
    }

}

