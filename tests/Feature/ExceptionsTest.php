<?php

namespace Taecontrol\Larvis\Tests;

use Exception;
use Taecontrol\Larvis\Larvis;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Taecontrol\Larvis\ValueObjects\ExceptionData;

class ExceptionsTest extends TestCase
{
    /** @test */
    public function it_check_if_handler_function_post_extra_data()
    {
        $larvis = app(Larvis::class);
        $exception = new Exception('exception');
        $data = [
            'name' => 'test',
            'key' => 'akdflasjdfl',
        ];

        config()->set('larvis.larastats.domain', 'https://larastats.test');

        Http::fake([
            'https://larastats.test/*' => Http::response([], 200, []),
        ]);

        $larvis->captureException($exception, $data);

        Http::assertSent(function (Request $request) use ($data) {
            return $request['name'] == $data['name'] &&
                   $request['key'] == $data['key'];
        });
    }

    /** @test */
    public function check_if_exception_data_exists()
    {
        $larvis = app(Larvis::class);
        $exception = new Exception('test exception');
        $exceptionData = ExceptionData::from($exception)->toArray();

        config()->set('larvis.larastats.domain', 'https://larastats.test');

        Http::fake([
            'https://larastats.test/*' => Http::response([], 200, []),
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
}
