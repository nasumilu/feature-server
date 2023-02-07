<?php

namespace App\Tests;

use PHPUnit\Framework\Assert as BaseAssert;


abstract class Assert
{

    public static function assertIsDatasource($datasource): void {
        BaseAssert::assertIsArray($datasource);
        BaseAssert::assertArrayHasKey('id', $datasource);
        BaseAssert::assertNotNull($datasource['id']);
        BaseAssert::assertArrayHasKey('name', $datasource);
        BaseAssert::assertNotNull($datasource['name']);
        BaseAssert::assertArrayHasKey('dbname', $datasource);
        BaseAssert::assertNotNull($datasource['dbname']);
        BaseAssert::assertArrayHasKey('driver', $datasource);
        BaseAssert::assertNotNull($datasource['driver']);
        BaseAssert::assertArrayHasKey('host', $datasource);
        BaseAssert::assertNotNull($datasource['host']);
        BaseAssert::assertArrayHasKey('port', $datasource);
        BaseAssert::assertNotNull($datasource['port']);
        BaseAssert::assertIsInt($datasource['port']);
        BaseAssert::assertArrayHasKey('username', $datasource);
        BaseAssert::assertNotNull($datasource['username']);
        BaseAssert::assertArrayHasKey('links', $datasource);
        BaseAssert::assertIsArray($datasource['links']);
    }

    public static function assertIsDriver($driver): void {
        BaseAssert::assertIsArray($driver);
        BaseAssert::assertArrayHasKey('label', $driver);
        BaseAssert::assertArrayHasKey('driver', $driver);
    }

    public static function assertIsLink($link): void {
        BaseAssert::assertIsArray($link);
        BaseAssert::assertArrayHasKey('href', $link);
        BaseAssert::assertNotNull($link['href']);
        BaseAssert::assertArrayHasKey('rel', $link);
        BaseAssert::assertNotNull($link['rel']);
    }

    public static function assertIsError($error): void {
        BaseAssert::assertIsArray($error);
        BaseAssert::assertArrayHasKey('type', $error);
        BaseAssert::assertNotNull($error['type']);
        BaseAssert::assertArrayHasKey('title', $error);
        BaseAssert::assertNotNull($error['title']);
        BaseAssert::assertArrayHasKey('status', $error);
        BaseAssert::assertNotNull($error['status']);
        BaseAssert::assertArrayHasKey('detail', $error);
        BaseAssert::assertNotNull($error['detail']);
    }

}