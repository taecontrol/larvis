<?php

namespace Taecontrol\Larvis\Handlers;

use Throwable;
use Illuminate\Support\Facades\Http;
use Taecontrol\Larvis\ValueObjects\ExceptionData;

class ExceptionHandler
{
    public function handle(Throwable $exception): void
    {
        $exceptionLoggerUrl = config('larvis.larastats.domain') . config('larvis.larastats.exception_logger.endpoint');
        $exceptionData = ExceptionData::from($exception);

        $data = array_merge(
            $exceptionData->toArray(),
            ['api_token' => config('larvis.site.api_token')],
        );

        Http::post($exceptionLoggerUrl, $data);
    }
}
