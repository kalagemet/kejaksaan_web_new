<?php
namespace App\Libraries;

interface OtpDeliveryInterface
{
    // Future WhatsApp providers implement this contract without changing OTP verification.
    public function send(string $channel, string $destination, string $code): void;
}
