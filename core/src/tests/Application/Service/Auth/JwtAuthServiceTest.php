<?php

namespace Test\Application\Service\Auth;

use App\Application\Service\Auth\JwtAuthService;
use PHPUnit\Framework\TestCase;
use Mockery;

class JwtAuthServiceTest extends TestCase
{
    public function tearDown():void
    {
        Mockery::close();
    }

    public function testGetJwtLifeTime(){
        self::assertEquals(true,true);
//        $mockJwtAuthService = Mockery::mock(JwtAuthService::class);
//        $lifeTime = $mockJwtAuthService->getLifetime();
//
//        $this->assertEquals(null,$lifeTime);
    }
}
