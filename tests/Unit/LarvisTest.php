<?php

namespace Taecontrol\Larvis\Tests\Feature\Handlers;

use Exception;
use Taecontrol\Larvis\Larvis;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Taecontrol\Larvis\Tests\TestCase;
use Taecontrol\Larvis\Watchers\ExceptionWatcher;
use Taecontrol\Larvis\ValueObjects\Data\ExceptionData;

class LarvisTest extends TestCase
{
    protected Larvis $larvis;

    public function setUp(): void
    {
        parent::setup();
        $this->larvis = app(Larvis::class);

        config()->set('larvis.moonguard.domain', 'https://moonguard.test');        
        config()->set('larvis.krater.enabled', false);
    }

    /** @test */
    public function it_asserts_that_users_can_send_exceptions_to_moonguard()
    {
        app(ExceptionWatcher::class)->enable();

        Http::fake([
            'https://moonguard.test/*' => Http::response([], 200, []),
        ]);

        $exception = new Exception('Exception');
        $exceptionData = ExceptionData::from($exception)->toArray();

        $this->larvis->sendException($exception);

        Http::assertSent(function (Request $request) use ($exceptionData) {
            return $request['message'] == $exceptionData['message'] &&
            $request['type'] == $exceptionData['type'] &&
            $request['file'] == $exceptionData['file'] &&
            $request['line'] == $exceptionData['line'] &&
            $request['trace'] == $exceptionData['trace'] &&
            $request['request'] == $exceptionData['request'];
        });

        app(ExceptionWatcher::class)->disable();
    }
}
