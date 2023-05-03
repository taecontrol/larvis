<?php

namespace Taecontrol\Larvis\Handlers;

use Illuminate\Support\Facades\Http;
use Taecontrol\Larvis\ValueObjects\AppData;
use Taecontrol\Larvis\ValueObjects\Backtrace;
use Taecontrol\Larvis\ValueObjects\MessageData;

class MessageHandler
{
    protected const BACKTRACE_LIMIT = 5;

    public function handle(mixed $args): void
    {
        $backtrace = Backtrace::from(
            debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, self::BACKTRACE_LIMIT)[2]
        );

        $messageData = MessageData::from($args, $backtrace);

        $appData = AppData::generate();

        $url = config('larvis.krater.url');
        $endpoint = config('larvis.krater.api.message');

        $data = [
            'message' => $messageData->toArray(),
            'app' => $appData->toArray(),
        ];

        Http::withHeaders(
            ['Content-Type' => 'application/json; charset=utf-8']
        )->post($url . $endpoint, $data)->throw();
    }
}
