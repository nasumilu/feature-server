<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Driver unit test
 *
 * @link ../docs/index.md#drivers Driver Requirements
 */
class DriverTest extends TestCase
{

    /**
     * Test drivers requirement:
     *  - 2.1.A) The server SHALL support the HTTP GET method at path `{root}/api/drivers
     *  - 2.2.A) The successful execution of the operation SHALL be reported as a response with an HTTP status code 200
     *
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function testDriverSupportsGetRequest(): array {
        $client = HttpClient::createForBaseUri($_ENV['API_URL']);
        $response = $client->request('GET', '/api/drivers');
        $this->assertEquals(200, $response->getStatusCode());
        return $response->toArray();
    }

    /**
     * Test driver requirement:
     *  - 2.2.B) The response SHALL be an array of objects based upon the OpenAPI 3.0 schemas, driver.yaml.
     *
     * @link https://raw.githubusercontent.com/nasumilu/feature-server/main/docs/openapi/schema/driver.yaml driver.yaml
     * @depends testDriverSupportsGetRequest
     * @param array $drivers
     * @return void
     */
    public function testDriverResponseSchema(array $drivers): void {
        foreach($drivers as $driver) {
            Assert::assertIsDriver($driver);
        }
    }

}