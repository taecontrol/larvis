<?php

use Taecontrol\Larvis\Larvis;

if (! function_exists('larvis')) {
    /**
     * @param mixed ...$args
     *
     * @return Taecontrol\Larvis\Larvis|null
     */
    function larvis(mixed $args)
    {
        try {
            return app(Larvis::class)->sendMessage($args);
        } catch (Exception $exception) {
            return null;
        }
    }
}
