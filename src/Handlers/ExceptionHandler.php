<?php

namespace Taecontrol\Larvis\Handlers;

use Throwable;
use Taecontrol\Larvis\Larvis;
use Illuminate\Support\Facades\Http;
use Taecontrol\Larvis\ValueObjects\ExceptionData;

class ExceptionHandler
{
    public function handle(Throwable $exception, array $extraData = []): void
    {
        /** @var Larvis */
        $larvis = app(Larvis::class);

        $exceptionData = ExceptionData::from($exception);

        $appData = $larvis->getAppData();

        if ($larvis->isLocalDebugEnabled()) {
            $exceptionLoggerUrl = config('larvis.debug.url') . config('larvis.debug.api.exception');

            $data = [
                'exception' => $exceptionData->debugFormat(),
                'app' => $appData->toArray(),
            ];
        } else {
            $exceptionLoggerUrl = config('larvis.moonguard.domain') . config('larvis.moonguard.exception_logger.endpoint');

            $data = array_merge(
                $exceptionData->toArray(),
                ['api_token' => config('larvis.site.api_token')],
                $extraData,
            );
        }

        Http::post($exceptionLoggerUrl, $data);
    }
}
