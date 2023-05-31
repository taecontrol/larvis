<?php

namespace Taecontrol\Larvis\Handlers;

use Taecontrol\Larvis\Larvis;
use Illuminate\Support\Facades\Http;
use Taecontrol\Larvis\Support\Formatter;
use Taecontrol\Larvis\ValueObjects\Backtrace;
use Taecontrol\Larvis\ValueObjects\Data\MessageData;

class MessageHandler
{
    protected const BACKTRACE_LIMIT = 5;

    public function handle(mixed $args): void
    {
        /** @var Larvis */
        $larvis = app(Larvis::class);

        $backtrace = Backtrace::from(
            debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, self::BACKTRACE_LIMIT)[2]
        );

        $formatter = (new Formatter())->format($args);

        $messageData = MessageData::from($formatter->toJson(), $formatter->kind, $backtrace);

        $appData = $larvis->getAppData();

        $url = config('larvis.debug.url') . config('larvis.debug.api.message');

        $data = [
            'message' => $messageData->debugFormat(),
            'app' => $appData->toArray(),
        ];

        Http::withHeaders(
            ['Content-Type' => 'application/json; charset=utf-8']
        )->post($url, $data);
    }
}
