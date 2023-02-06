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
    public function testDatasourceSupportsGetRequest(): array {
        $client = HttpClient::createForBaseUri($_ENV['API_URL']);
        $response = $client->request('GET', '/api/datasources');
        $this->assertEquals(200, $response->getStatusCode());
        return $response->toArray();
    }

    /**
     * Test datasource(s) requirements:
     * <ul>
     *  <li> 2.5.B) The response <strong>SHALL</strong> be an objects based upon the OpenAPI 3.0 schema with the
     *       following properties:
     *           <ol>
     *              <li>
     *                  <code>datasources</code> which <strong>MUST</strong> be an array of objects based upon the
     *                  OpenAPI 3.0 schema, datasource.yaml.
     *              </li>
     *              <li>
     *                  <code>links</code> which <strong>MUST</strong> be an array of object base upon the OpenAPI 3.0
     *                  schema link.yaml
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
    public function testDatasourceResponseSchema(array $datasources): void {
        $this->assertArrayHasKey('datasources', $datasources);
        $this->assertArrayHasKey('links', $datasources);
        foreach($datasources['datasources'] as $datasource) {
            Assert::assertIsDatasource($datasource);
            foreach($datasource['links'] as $link) {
                Assert::assertIsLink($link);
            }
        }
        foreach($datasources['links'] as $link) {
            Assert::assertIsLink($link);
        }

    }


}