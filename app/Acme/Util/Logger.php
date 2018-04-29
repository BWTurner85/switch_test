<?php
namespace Acme\Util;

/**
 * The simplest logger you ever will see, in a real world app we'd have something far more sophisticated
 * ... but in a real world app we also be highly unlikely to be writing our own brand new logger
 */
class Logger
{
    /**
     * @var bool Whether or not the logger is currently enabeld
     */
    protected static $enabled = true;

    /**
     * @var string The name of the log to write to. Log will be placed in system temp dir
     */
    protected static $logName = 'acme.log';

    /**
     * Turn the logger back on
     */
    public static function enable()
    {
        self::$enabled = true;
    }

    /**
     * Disable the logger - remove pointless logs in tests
     */
    public static function disable()
    {
        self::$enabled = false;
    }

    /**
     * @param string $name
     */
    public static function setName(string $name)
    {
        self::$logName = $name;
    }

    /**
     * @param string $message
     */
    public static function log(string $message)
    {
        if (self::$enabled) {
            error_log("$message", 3, sys_get_temp_dir() . DIRECTORY_SEPARATOR . self::$logName);
        }
    }
}
