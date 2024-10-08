<?php

namespace Taecontrol\Larvis\Tests\Feature\Handlers;

use Exception;
use Taecontrol\Larvis\Larvis;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Taecontrol\Larvis\Tests\TestCase;
use Illuminate\Log\Events\MessageLogged;
use Taecontrol\Larvis\ValueObjects\Data\AppData;
use Taecontrol\Larvis\Watchers\ExceptionWatcher;
use Taecontrol\Larvis\ValueObjects\Data\ExceptionData;

class ExceptionWatcherTest extends TestCase
{
    protected Larvis $larvis;

    public function setUp(): void
    {
        parent::setup();
        $this->larvis = app(Larvis::class);

        config()->set('larvis.moonguard.domain', 'https://moonguard.test');
        config()->set('larvis.krater.url', 'http://localhost:58673');
    }

    /** @test */
    public function it_asserts_that_exception_handler_send_exception_data_to_moonguard()
    {
        app(ExceptionWatcher::class)->enable();
        config()->set('larvis.krater.enabled', false);

        Http::fake([
            'https://moonguard.test/*' => Http::response([], 200, []),
        ]);

        $exception = new Exception('Exception');
        $exceptionData = ExceptionData::from($exception)->toArray();

        event(new MessageLogged('warning', 'test', ['exception' => $exception]));

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

    /** @test */
    public function it_asserts_that_exception_handler_send_exception_data_to_debug_client()
    {
        app(ExceptionWatcher::class)->enable();
        config()->set('larvis.krater.enabled', true);

        Http::fake([
            'http://localhost:58673/*' => Http::response([], 200, []),
        ]);

        $exception = new Exception('Exception');
        $exceptionData = ExceptionData::from($exception);

        event(new MessageLogged('warning', 'test', ['exception' => $exception]));

        Http::assertSent(function (Request $request) use ($exceptionData) {
            $exception = $request['exception'];

            /** @var AppData */
            $appData = AppData::fromArray($request['app']);

            $isExceptionPresent = $exception['message'] === $exceptionData->message &&
            $exception['kind'] === $exceptionData->type &&
            $exception['line'] === $exceptionData->line &&
            $exception['trace'] === json_encode($exceptionData->trace);

            $this->assertJson($exception['request']);

            $exceptionRequest = json_decode($exception['request']);

            $isExceptionDataAvailable = $exceptionRequest->url === 'http://localhost' &&
            $exceptionRequest->params === [] &&
            $exceptionRequest->query === [];

            $isAppDataPresent = $appData->name === config('app.name') &&
            $appData->framework === 'Laravel' &&
            $appData->frameworkVersion === app()->version() &&
            $appData->language === 'PHP' &&
            $appData->languageVersion === PHP_VERSION;

            $this->assertStringContainsString('larvis', $appData->directory);

            return $isExceptionPresent && $isAppDataPresent && $isExceptionDataAvailable;
        });

        app(ExceptionWatcher::class)->disable();
    }
}
