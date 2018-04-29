<?php
namespace Acme\test\integration;

use Acme\Client;

/**
 * Stub class that empties the API URL to force API calls to fail and test the result
 */
class FailingApiUrlClientStub extends Client
{
    const API_URI = '';

}
