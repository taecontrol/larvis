<?php

use Taecontrol\Larvis\Larvis;

if (! function_exists('larvis')) {
    /**
     * @param mixed ...$args
     *
     * @return Taecontrol\Larvis\Larvis|null
     */
    function larvis(mixed ...$args)
    {
        try {
            if (count($args) === 0) {
                return app(Larvis::class);
            }

            return app(Larvis::class)->sendMessage($args[0]);
        } catch (Exception $exception) {
            return null;
        }
    }
}
