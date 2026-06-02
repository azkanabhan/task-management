<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (class_exists(\Resend\Laravel\Facades\Resend::class)) {
            $resendMock = \Mockery::mock('Resend\Client');
            $emailsMock = \Mockery::mock();
            $emailsMock->shouldReceive('send')->andReturn((object) ['id' => '123']);
            $resendMock->shouldReceive('emails')->andReturn($emailsMock);

            \Resend\Laravel\Facades\Resend::swap($resendMock);
        }
    }
}
