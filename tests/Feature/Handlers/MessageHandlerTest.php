<?php

namespace Taecontrol\Larvis\Tests\Feature\Handlers;

use Taecontrol\Larvis\Larvis;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Taecontrol\Larvis\Tests\TestCase;
use Taecontrol\Larvis\ValueObjects\Data\AppData;
use Taecontrol\Larvis\ValueObjects\Data\MessageData;

class MessageHandlerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        config()->set('larvis.krater.url', 'http://localhost:58673');
        config()->set('larvis.krater.api.messages', '/api/messages');
    }

    /** @test */
    public function message_handler_only_works_on_local_with_krater(): void
    {
        Http::fake();

        app()->detectEnvironment(function () {
            return 'production';
        });

        larvis('hola');

        Http::assertNothingSent();
    }

    /** @test */
    public function it_sends_multiple_messages(): void
    {
        Http::fake();

        larvis(1, 2, 3, 4);

        Http::assertSentCount(4);
    }

    /** @test */
    public function it_check_if_message_handler_post_message_data(): void
    {
        /** @var Larvis */
        app(Larvis::class);

        $data = 'Hi from Larvis';

        Http::fake([
            'http://localhost:58673/*' => Http::response(null, 201, []),
        ]);

        larvis($data);

        Http::assertSent(function (Request $request) use ($data) {
            /** @var MessageData */
            $messageData = MessageData::fromArray($request['message']);

            return $messageData->data === '"Hi from Larvis"';
        });
    }

    /** @test */
    public function ìt_check_if_message_and_app_data_exists_on_message_post(): void
    {
        /** @var Larvis */
        app(Larvis::class);
        config()->set('larvis.krater.url', 'http://localhost:58673');
        config()->set('larvis.krater.api.messages', '/api/messages');

        $data = 'Hi from Larvis';

        Http::fake([
            'http://localhost:58673/*' => Http::response(null, 201, []),
        ]);

        larvis($data);

        Http::assertSent(function (Request $request) use ($data) {
            /** @var MessageData */
            $messageData = MessageData::fromArray($request['message']);

            /** @var AppData */
            $appData = AppData::fromArray($request['app']);

            $isMessageDataPresent = $messageData->data === '"Hi from Larvis"' &&
            $messageData->kind === 'string' &&
            $messageData->line === 82 &&
            $messageData->file === __FILE__;

            $isAppDataPresent = $appData->name === config('app.name') &&
            $appData->framework === 'Laravel' &&
            $appData->frameworkVersion === app()->version() &&
            $appData->language === 'PHP' &&
            $appData->languageVersion === PHP_VERSION;

            $this->assertStringContainsString('larvis', $appData->directory);

            return $isMessageDataPresent && $isAppDataPresent;
        });
    }
}
