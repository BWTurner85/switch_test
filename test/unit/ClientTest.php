<?php
namespace Acme\test\unit;

use Acme\Client;
use PHPUnit\Exception;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    /**
     * @expectedException Exception
     * @expectedExceptionMessage Time is not in a recognisable format
     */
    public function testExceptionThrownWhenTimeIsInvalid()
    {
        $client = new Client();
        $client->getRecommendations('Drama', 'never');
    }
}
