<?php
namespace Tests\Support\Libraries;

class TestOtpDelivery implements \App\Libraries\OtpDeliveryInterface
{
    public array $messages = [];
    public bool $fail = false;
    public function send(string $channel, string $destination, string $code): void
    {
        if ($this->fail) { throw new \RuntimeException('Simulated unavailable delivery.'); }
        $this->messages[] = ['channel' => $channel, 'destination' => $destination, 'code' => $code];
    }
    public function code(): string { return $this->messages[count($this->messages) - 1]['code']; }
}
