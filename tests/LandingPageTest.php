<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Unit test for https://docs.opengeospatial.org/is/17-069r4/17-069r4.html#_landing_page_root
 */
class LandingPageTest extends WebTestCase
{
    /**
     * ## Abstract Test 3
     * ### Purpose
     * 1. Validate that a landing page can be retrieved from the expected location.
     *
     * ### Requirement
     * 1. The server SHALL support the HTTP GET operation at the path /.
     *
     * ### Test Method
     * 1. Issue an HTTP GEt request to the URL `{root}/`
     * 2. Validate that a document was returned with a status code 200
     * 3. Validate the contents of the returned document using {@see LandingPageTest::testLandingPageCompliesWithRequiredStructure()}
     *
     * @link https://docs.opengeospatial.org/is/17-069r4/17-069r4.html#_landing_page_root Abstract Unit Test A.2.2
     * @link https://docs.opengeospatial.org/is/17-069r4/17-069r4.html#_api_landing_page Landing Page Requirements
     * @return void
     */
    public function testLandingPageCanBeRetrieved(): void
    {
        $client = static::createClient();
        $crawler = $client->xmlHttpRequest('GET', '/');
        $this->assertResponseStatusCodeSame(200);
    }

    /*public function testLandingPageCompliesWithRequiredStructure(): void {

    }*/
}
