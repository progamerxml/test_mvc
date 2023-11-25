<?php

declare(strict_types=1);

namespace PRGANYAR\MVC\TEST\App;

use PHPUnit\Framework\TestCase;

class ViewTest extends TestCase
{
    public function testView()
    {
        View::view('Error/404', [
            "rA kEteMU ! EmpaTnOlemPAt"
        ]);

        self::expectOutputRegex('[rA kEteMU ! EmpaTnOlemPAt]');
        self::expectOutputRegex('[404 Not Found]');
        self::expectOutputRegex('[html]');
        self::expectOutputRegex('[body]');
    }
}
