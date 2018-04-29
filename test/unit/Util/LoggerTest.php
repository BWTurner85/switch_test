<?php
namespace Acme\test\unit;

use Acme\Util\Logger;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{
    /**
     * @var string Full path of test log file
     */
    protected $log;

    /**
     * Ensure test log is clear before each test, and tell the logger to use said test log
     */
    public function setUp()
    {
        $this->log = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'acme_test.log';

        if (file_exists($this->log)) {
            unlink($this->log);
        }

        Logger::setName('acme_test.log');
    }

    public function testLoggingEnabled()
    {
        $this->assertFileNotExists($this->log);

        Logger::enable();
        Logger::log("Hello world");

        $this->assertFileExists($this->log);
        $this->assertEquals("Hello world", file_get_contents($this->log));

        unlink($this->log);
    }

    public function testLoggingDisabled()
    {
        $this->assertFileNotExists($this->log);

        Logger::disable();
        Logger::log("Hello world");

        $this->assertFileNotExists($this->log);
    }


}

