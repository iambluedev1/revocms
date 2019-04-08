<?php
namespace Logs;

/**
 * Class Logging
 * @package Logs
 * @author iambluedev
 * @copyright RevoCMS.fr | 2017
 */

class Logging
{
    /**
     * Log File
     * @var string
     */
    private $log_file;

    /**
     * File Stream
     * @var bool|resource
     */
    private $fp;

    /**
     * Specify Log File Path
     *
     * @param string $path
     */
    public function lfile(string $path)
    {
        $this->log_file = $path;
    }

    /**
     * Write a String
     *
     * @param string $message
     */
    public function lwrite(string $message)
    {
        $time = @date('[d/M/Y:H:i:s]');
        fwrite($this->fp, "$time $message" . PHP_EOL);
    }

    /**
     * Close Stream Method
     */
    public function lclose()
    {
        fclose($this->fp);
    }

    /**
     * Open Stream Method
     */
    public function lopen()
    {
        $lfile = $this->log_file;
        $this->fp = fopen($lfile, 'a') or exit("Can't open $lfile!");
    }
}
?>