<?php

namespace App\Components\Structures;

class BotOutput
{
    public string $text;
    public array $actions;
    public array $attachments;

    public function __construct()
    {
        $this->text = '';
        $this->actions = [];
        $this->attachments = [];
    }
}
