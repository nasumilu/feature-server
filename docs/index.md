# Requirements

## Table of Contents

1. [OGC API - Feature](#ogc-api-feature)
2. [Server API](#server-api)
   - [Drivers](#drivers)
   - [Datasources](#datasources)
3. Unit Tests

## OGC API Feature

The requirements for the OGC API Feature are found in the [OGC API - Features - Part 1: Core corrigendum](https://docs.opengeospatial.org/is/17-069r4/17-069r4.html)

## Server API

Request/Response schema use the OpenAPI 3.0 specification.


### Drivers

Drivers represent a vendor specific the database, accessed via an abstract layer interface. The availability of drivers depends on the extension install. 

| Requirement(s) | Description                                                                                                  | Status |
|----------------|--------------------------------------------------------------------------------------------------------------|--------|
| 2.1.A          | The server **SHALL** support the HTTP GET method at path `{root}/api/drivers`                                | 🟢     |
| 2.2.A          | The successful execution of the operation **SHALL** be reported as a response with an HTTP status code `200` | 🟢     |
| 2.2.B          | The response **SHALL** be an array of objects based upon schema, [driver.yaml](openapi/schemas/driver.yaml). | 🟢     |
| 2.3.A          | The response **MUST** only include drivers which are installed.                                              | 🟡     |

### Datasource(s)
| Requirement(s) | Description                                                                                                                                                                                                                                                                       | Status |
|----------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|--------|
| 2.4.A          | The server **SHALL** support the HTTP GET method at path `{root}/api/datasources`                                                                                                                                                                                                 | 🟢     |
| 2.5.A          | The successful execution of the operation, 2.4.A, **SHALL** be reported as a response with an HTTP status code `200`                                                                                                                                                              | 🟢     |
| 2.5.B          | The response body for, 2.5.A, **SHALL** be an object based upon schema, [datasource.yaml](openapi/schemas/datasource.yaml).</li>                                                                                                                                                  | 🟢     |
| 2.6.A          | The server **SHALL** support the HTTP GET method path for `{root}/api/datasources/{id<\d+>}`                                                                                                                                                                                      | 🟢     |
| 2.7.A          | The successful execution of the operation, 2.6.A, **SHALL** be reported as a response with an HTTP status code `200`                                                                                                                                                              | 🟢     |
| 2.7.B          | The response body for 2.7.A, **SHALL** be an object based on schema, [datasource.yaml](openapi/schemas/datasource.yaml)                                                                                                                                                           | 🟢     |
| 2.8.A          | The unsuccessful execution of the operation, 2.6.A, **SHALL** be reported as a response with an HTTP status code `404`, when the datasource by `{id}` is unknown                                                                                                                  | 🟢     |
| 2.8.B          | The response body for, 2.8.A, **SHALL** be an object based upon schema, [error.yaml](openapi/schemas/error.yaml)                                                                                                                                                                  | 🟢     |
| 2.9.A          | The server **SHALL** support the HTTP POST method at path `{root}/api/dataources/test-connection`                                                                                                                                                                                 | 🟢     |
| 2.10.A         | The successful execution of the operation, 2.9.A, **SHALL** be reported as a response with an HTTP status code `200`                                                                                                                                                              | 🟢     |
| 2.10.B         | The request body for, 2.10.A, **SHALL** be an object based upon schema, [datasource-configuration.yaml](openapi/parameters/datasource-configuration.yaml) and a response body **MUST** be an object based on schema, [test-connection.yaml](openapi/schemas/test-connection.yaml) | 🟢     |


## Unit Test

Unit testing is preformed using [PHPUnit](https://phpunit.de/). 

### Setup

The unit test utilize the [symonfy/http-client](https://symfony.com/doc/current/http_client.html) and requires that the
environment variable `API_URL` is set in the projects `phpunit.xml` configuration file. To accomplish this use the 
following guidelines:

```shell
$ cd [project directory]
$ cp phpunit.xml.dist phpunit.xml
```
Set the `API_URL` environment variable to suit your needs. The default setting assumes that the local environment is 
utilizing the development server provided by Symfony CLI. For more information on Symfony local web server can be found
[here](https://symfony.com/doc/current/setup/symfony_server.html).

```shell
$ symfony server:start -d
```
or to see the server log omit the `-d` option
```shell
$ symfony server:start
```
It is typically best to use the phpunit CLI bundled installed by composer. It is found in the `vendor/bin` directory.
Invoking the unit test as follows:

```shell
$ vendor/bin/phpunit
```

The expected output is:
```shell
PHPUnit 10.0.4 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.1.14
Configuration: /home/mlucas/Projects/feature-server/phpunit.xml

....                                                                4 / 4 (100%)

Time: 00:00.155, Memory: 4.00 MB

OK (4 tests, 107 assertions)
```
