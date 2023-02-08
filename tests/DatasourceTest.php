<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class DatasourceTest extends TestCase
{

    /**
     * Test datasource(s) requirements:
     *  - 2.4.A) The server **SHALL** support the HTTP GET method at path `{root}/api/datasources`
     *  - 2.5.A) The successful execution of the operation **SHALL** be reported as a response with an HTTP status code
     *           `200`
     *
     * @return array
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws DecodingExceptionInterface
     */
    public function testDatasourceSupportsGetRequest(): array
    {
        $client = HttpClient::createForBaseUri($_ENV['API_URL']);
        $response = $client->request('GET', '/api/datasources');
        $this->assertEquals(200, $response->getStatusCode());
        return $response->toArray();
    }

    /**
     * Test datasource(s) requirements:
     * <ul>
     *  <li> 2.5.B) The response <strong>SHALL</strong> be an objects based upon the OpenAPI 3.0 schemas with the
     *       following properties:
     *           <ol>
     *              <li>
     *                  <code>datasources</code> which <strong>MUST</strong> be an array of objects based upon the
     *                  OpenAPI 3.0 schemas, datasource.yaml.
     *              </li>
     *              <li>
     *                  <code>links</code> which <strong>MUST</strong> be an array of object base upon the OpenAPI 3.0
     *                  schemas link.yaml
     *              </li>
     *          </ol>
     *  </li>
     * </ul>
     * @depends testDatasourceSupportsGetRequest
     * @link https://raw.githubusercontent.com/nasumilu/feature-server/main/docs/openapi/schema/datasource.yaml datasource.yaml
     * @link https://raw.githubusercontent.com/nasumilu/feature-server/main/docs/openapi/schema/link.yaml link.yaml
     * @param array $datasources
     * @return void
     */
    public function testDatasourcesResponseSchema(array $datasources): void
    {
        $this->assertArrayHasKey('datasources', $datasources);
        $this->assertArrayHasKey('links', $datasources);
        foreach ($datasources['datasources'] as $datasource) {
            Assert::assertIsDatasource($datasource);
            foreach ($datasource['links'] as $link) {
                Assert::assertIsLink($link);
            }
        }
        foreach ($datasources['links'] as $link) {
            Assert::assertIsLink($link);
        }
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testDatasourceResponseSchema(): void
    {
        $client = HttpClient::createForBaseUri($_ENV['API_URL']);
        $response = $client->request('GET', '/api/datasources/2');
        $this->assertEquals(200, $response->getStatusCode());
        Assert::assertIsDatasource($response->toArray());
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testDatasourceNotFoundResponse(): array
    {
        $client = HttpClient::createForBaseUri($_ENV['API_URL']);
        $response = $client->request('GET', '/api/datasources/200');
        $this->assertEquals(404, $response->getStatusCode());
        return $response->toArray(false);
    }

    /**
     * @depends testDatasourceNotFoundResponse
     * @param array $error
     * @return void
     */
    public function testDatasourceNotFoundResponseSchema(array $error): void
    {
        Assert::assertIsError($error);
    }

    /**
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function testConnectionResponse(): array
    {
        $params = json_decode($_ENV['TEST_CONNECTION']);
        $client = HttpClient::createForBaseUri($_ENV['API_URL']);
        $response = $client->request('POST', '/api/datasources/test-connection', [
            'json' => $params
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        return $response->toArray();
    }

    /**
     * @depends testConnectionResponse
     * @param array $data
     * @return void
     */
    public function testConnectionResponseSchema($data): void
    {
        $this->assertIsArray($data);
        $this->assertArrayHasKey('success', $data);
        $this->assertIsBool($data['success']);
        $this->assertArrayHasKey('message', $data);
        $this->assertIsString($data['message']);
    }

}