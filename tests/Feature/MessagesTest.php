<?php

namespace Taecontrol\Larvis\Tests;

use Taecontrol\Larvis\Larvis;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Taecontrol\Larvis\ValueObjects\AppData;
use Taecontrol\Larvis\ValueObjects\MessageData;

class MessagesTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        config()->set('larvis.debug.url', 'http://localhost:55555');
        config()->set('larvis.debug.api.message', '/api/messages');
    }

    /** @test */
    public function it_check_if_message_handler_post_message_data(): void
    {
        /** @var Larvis */
        app(Larvis::class);

        $data = 'Hi from Larvis';

        Http::fake([
            'http://localhost:55555/*' => Http::response(null, 201, []),
        ]);

        larvis($data);

        Http::assertSent(function (Request $request) use ($data) {
            /** @var MessageData */
            $messageData = MessageData::fromArray($request['message']);

            return $messageData->data === $data;
        });
    }

    /** @test */
    public function ìt_check_if_message_and_app_data_exists_on_message_post(): void
    {
        /** @var Larvis */
        app(Larvis::class);
        config()->set('larvis.debug.url', 'http://localhost:55555');
        config()->set('larvis.debug.api.message', '/api/messages');

        $data = 'Hi from Larvis';

        Http::fake([
            'http://localhost:55555/*' => Http::response(null, 201, []),
        ]);

        larvis($data);

        Http::assertSent(function (Request $request) use ($data) {
            /** @var MessageData */
            $messageData = MessageData::fromArray($request['message']);

            /** @var AppData */
            $appData = AppData::fromArray($request['app']);

            $isMessageDataPresent = $messageData->data === $data &&
            $messageData->kind === 'string' &&
            $messageData->line === 57 &&
            $messageData->file === __FILE__;

            $isAppDataPresent = $appData->name === env('APP_NAME') &&
            $appData->framework === 'Laravel' &&
            $appData->frameworkVersion === app()->version() &&
            $appData->language === 'PHP' &&
            $appData->languageVersion === PHP_VERSION;

            return $isMessageDataPresent && $isAppDataPresent;
        });
    }
}
