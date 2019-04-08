<?php
namespace Core;

use Database\Database as Database;
use RevoCMS\RevoCMS;

/**
 * Class Model
 * @package Core
 * @author iambluedev
 * @copyright RevoCMS.fr | 2017
 */

abstract class Model
{

    /**
     * Return Database Class
     *
     * @var Database
     */
    protected $db;

    /**
     * Prefix for mysql tables
     *
     * @var string
     */
    protected $prefix;

    /**
     * Model constructor.
     */
    public function __construct()
    {
        $this->db = Database::get();
        $rcms = RevoCMS::getInstance();
        $this->prefix = $rcms->configs->database->get("DB_PREFIX");
    }
}
