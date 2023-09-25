<?php declare(strict_types=1);

use App\Controllers\LoggerInterface;

class FileLogger implements LoggerInterface
{
    public function log(string $message): void
    {
        echo "Logged: $message";
    }
}
