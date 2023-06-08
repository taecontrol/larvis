<?php

namespace Taecontrol\Larvis\Watchers;

use Exception;
use Throwable;
use Taecontrol\Larvis\Larvis;
use Illuminate\Support\Facades\Event;
use Illuminate\Log\Events\MessageLogged;
use Taecontrol\Larvis\ValueObjects\Data\ExceptionData;

class ExceptionWatcher extends Watcher
{
    public function register(): void
    {
        $this->enabled = config('larvis.watchers.exceptions.enabled');

        Event::listen(function (MessageLogged $message) {
            if (! $this->enabled()) {
                return;
            }

            if (! $this->isAnException($message)) {
                return;
            }

            $this->handleException($message->context['exception']);
        });
    }

    public function isAnException(MessageLogged $message): bool
    {
        if (! isset($message->context['exception'])) {
            return false;
        }

        if (! $message->context['exception'] instanceof Exception) {
            return false;
        }

        return true;
    }

    public function handleException(Throwable $exception): void
    {
        /** @var Larvis */
        $larvis = app(Larvis::class);

        $exceptionLoggerUrl = $this->getUrl($larvis);

        $data = $this->getData($larvis, $exception);

        $larvis->send($exceptionLoggerUrl, $data);
    }

    public function getUrl(Larvis $larvis): string
    {
        if ($larvis->isLocalDebugEnabled()) {
            return config('larvis.debug.url') . config('larvis.debug.api.exception');
        }

        return config('larvis.moonguard.domain') . config('larvis.moonguard.exception_logger.endpoint');
    }

    public function getData(Larvis $larvis, Throwable $exception): array
    {
        $exceptionData = ExceptionData::from($exception);

        if ($larvis->isLocalDebugEnabled()) {
            $appData = $larvis->getAppData();

            return [
                'exception' => $exceptionData->debugFormat(),
                'app' => $appData->toArray(),
            ];
        }

        return array_merge(
            $exceptionData->toArray(),
            ['api_token' => config('larvis.site.api_token')],
        );
    }
}
