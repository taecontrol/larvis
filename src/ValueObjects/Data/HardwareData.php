<?php

namespace Taecontrol\Larvis\ValueObjects\Data;

use Illuminate\Contracts\Support\Arrayable;

class HardwareData implements Arrayable
{
    public function __construct(
        public readonly float $cpuLoad,
        public readonly float $memory,
        public readonly array $disk,
    ) {
    }

    public function toArray(): array
    {
        return [
            'cpuLoad' => $this->cpuLoad,
            'memory' => $this->memory,
            'disk' => $this->disk,
        ];
    }

    public static function fromArray(array $args): HardwareData
    {
        return new HardwareData(
            cpuLoad: $args['cpuload'],
            memory: $args['memory'],
            disk: $args['disk'],
        );
    }
}
