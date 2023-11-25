<?php

declare(strict_types=1);

namespace PRGANYAR\MVC\TEST\Config;

use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    public function testDbConnec()
    {
        $conn = Database::getConnection();
        self::assertNotNull($conn);
    }

    public function testDbConnSingleTon()
    {
        $conn = Database::getConnection();
        $conn2 = Database::getConnection();
        self::assertSame($conn, $conn2);
    }
}
