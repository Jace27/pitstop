<?php

namespace App\Components\Structures;

class SessionData
{
    public $message_id;

    private array $fields = [
        'message_id',
    ];

    public function __construct(string $data)
    {
        $data = json_decode($data, true);
        foreach ($this->fields as $field) {
            $this->$field = $data[$field] ?? null;
        }
    }

    public function encode(): string
    {
        $object = [];
        foreach ($this->fields as $field) {
            $object[$field] = $this->$field;
        }
        return json_encode($object, JSON_UNESCAPED_UNICODE);
    }
}
