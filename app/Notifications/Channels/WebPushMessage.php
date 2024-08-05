<?php
namespace App\Notifications\Channels;
class WebPushMessage
{
    public string $title = '';
    public string $message = '';
    public ?bool $sendToAll = false;

    public function __construct(string $title = '', string $message = '', ?bool $sendToAll = false)
    {
        $this->title = $title;
        $this->message = $message;
        $this->sendToAll = $sendToAll;
    }

    public function title(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function message(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function sendToAll(bool $sendToAll): self
    {
        $this->sendToAll = $sendToAll;
        return $this;
    }

    public static function make(): static
    {
        return new static();
    }

}
