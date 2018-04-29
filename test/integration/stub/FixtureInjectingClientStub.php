<?php
namespace Acme\test\integration;

use Acme\Client;

/**
 * Class that allows for injecting a static fixture to be used an API calls
 * in order to test client functionality without live curl calls
 */
class FixtureInjectingClientStub extends Client
{
    /**
     * @var string
     */
    protected $apiResponse;

    /**
     * @param string $response
     */
    public function setApiResponse(string $response)
    {
        $this->apiResponse = $response;
    }

    /**
     * @return string
     */
    public function callApi()
    {
        return $this->apiResponse;
    }


}
