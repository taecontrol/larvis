<?php

namespace Taecontrol\Larvis\Tests\Mock;

class TestObject
{
    public const A = 'HELLO WORLD';

    private string $propA = 'Hi';

    public string $propB = 'World';

    protected string $propC = 'Ok';

    public string $name;

    public string $email;

    public function __construct(string $name = '', string $email = '')
    {
        $this->name = $name;
        $this->email = $email;
    }
}
