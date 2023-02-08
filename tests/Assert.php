<?php

namespace App\Tests;

use PHPUnit\Framework\Assert as BaseAssert;


abstract class Assert
{

    public static function assertIsDatasource($datasource): void {
        BaseAssert::assertIsArray($datasource);
        BaseAssert::assertArrayHasKey('id', $datasource);
        BaseAssert::assertIsInt($datasource['id']);
        BaseAssert::assertArrayHasKey('name', $datasource);
        BaseAssert::assertIsString($datasource['name']);
        BaseAssert::assertArrayHasKey('dbname', $datasource);
        BaseAssert::assertIsString($datasource['dbname']);
        BaseAssert::assertArrayHasKey('driver', $datasource);
        BaseAssert::assertIsString($datasource['driver']);
        BaseAssert::assertArrayHasKey('host', $datasource);
        BaseAssert::assertIsString($datasource['host']);
        BaseAssert::assertArrayHasKey('port', $datasource);
        BaseAssert::assertIsInt($datasource['port']);
        BaseAssert::assertArrayHasKey('username', $datasource);
        BaseAssert::assertIsString($datasource['username']);
        BaseAssert::assertArrayHasKey('links', $datasource);
        BaseAssert::assertIsArray($datasource['links']);
    }

    public static function assertIsDriver($driver): void {
        BaseAssert::assertIsArray($driver);
        BaseAssert::assertArrayHasKey('label', $driver);
        BaseAssert::assertIsString($driver['label']);
        BaseAssert::assertArrayHasKey('driver', $driver);
        BaseAssert::assertIsString($driver['driver']);
    }

    public static function assertIsLink($link): void {
        BaseAssert::assertIsArray($link);
        BaseAssert::assertArrayHasKey('href', $link);
        BaseAssert::assertIsString($link['href']);
        BaseAssert::assertArrayHasKey('rel', $link);
        BaseAssert::assertIsString($link['rel']);
    }

    public static function assertIsError($error): void {
        BaseAssert::assertIsArray($error);
        BaseAssert::assertArrayHasKey('type', $error);
        BaseAssert::assertIsString($error['type']);
        BaseAssert::assertArrayHasKey('title', $error);
        BaseAssert::assertIsString($error['title']);
        BaseAssert::assertArrayHasKey('status', $error);
        BaseAssert::assertIsInt($error['status']);
        BaseAssert::assertArrayHasKey('detail', $error);
        BaseAssert::assertIsString($error['detail']);
    }

}