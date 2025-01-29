<?php

namespace App\Contracts;

interface SMSGateway
{
    public function sendSMS(array $recipient, array $params = []): void;

    public function sendBulkSMS(array $recipients, array $params = []): void;

    public function sendCustomizedSMS(array $recipients, array $params = []): void;
}
