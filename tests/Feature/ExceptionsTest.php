<?php

namespace Taecontrol\Larvis\Tests;

use Exception;
use Taecontrol\Larvis\Larvis;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Taecontrol\Larvis\ValueObjects\AppData;
use Taecontrol\Larvis\ValueObjects\ExceptionData;

class ExceptionsTest extends TestCase
{
    /** @test */
    public function it_check_if_handler_function_post_extra_data_to_moonguard()
    {
        $larvis = app(Larvis::class);
        $exception = new Exception('exception');
        $data = [
            'name' => 'test',
            'key' => 'akdflasjdfl',
        ];

        config()->set('larvis.moonguard.domain', 'https://moonguard.test');
        config()->set('larvis.debug.enabled', false);

        Http::fake([
            'https://moonguard.test/*' => Http::response([], 200, []),
        ]);

        $larvis->captureException($exception, $data);

        Http::assertSent(function (Request $request) use ($data) {
            return $request['name'] == $data['name'] &&
                   $request['key'] == $data['key'];
        });
    }

    /** @test */
    public function check_if_exception_data_exists_in_request_to_moonguard()
    {
        $larvis = app(Larvis::class);
        $exception = new Exception('test exception');
        $exceptionData = ExceptionData::from($exception)->toArray();

        config()->set('larvis.moonguard.domain', 'https://moonguard.test');
        config()->set('larvis.debug.enabled', false);

        Http::fake([
            'https://moonguard.test/*' => Http::response([], 200, []),
        ]);

        $larvis->captureException($exception, []);

        Http::assertSent(function (Request $request) use ($exceptionData) {
            return $request['message'] == $exceptionData['message'] &&
                   $request['type'] == $exceptionData['type'] &&
                   $request['file'] == $exceptionData['file'] &&
                   $request['line'] == $exceptionData['line'] &&
                   $request['trace'] == $exceptionData['trace'] &&
                   $request['request'] == $exceptionData['request'];
        });
    }

    /** @test */
    public function it_verifies_that_exception_data_and_app_data_exists_in_request_to_debug_client()
    {
        $larvis = app(Larvis::class);
        $exception = new Exception('test exception');
        $exceptionData = ExceptionData::from($exception);

        config()->set('larvis.debug.url', 'http://localhost:55555');

        Http::fake([
            'http://localhost:55555/*' => Http::response([], 200, []),
        ]);

        $larvis->captureException($exception, []);

        Http::assertSent(function (Request $request) use ($exceptionData) {
            $exception = $request['exception'];

            /** @var AppData */
            $appData = AppData::fromArray($request['app']);

            $isExceptionPresent = $exception['message'] === $exceptionData->message &&
            $exception['type'] === $exceptionData->type &&
            $exception['line'] === $exceptionData->line &&
            $exception['trace'] === json_encode($exceptionData->trace) &&
            $exception['request'] === json_encode($exceptionData->request);

            $isAppDataPresent = $appData->name === env('APP_NAME') &&
            $appData->framework === 'Laravel' &&
            $appData->frameworkVersion === app()->version() &&
            $appData->language === 'PHP' &&
            $appData->languageVersion === PHP_VERSION;

            return $isExceptionPresent && $isAppDataPresent;
        });
    }
}
