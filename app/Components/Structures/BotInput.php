<?php

namespace App\Components\Structures;

class BotInput
{
    public ?string $text;
    public ?string $button;

    public function __construct(?string $text, ?string $button)
    {
        $this->text = $text;
        $this->button = $button;
    }
}
