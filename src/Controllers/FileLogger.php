<?php declare(strict_types=1);

require 'LoggerInterface.php';

class FileLogger implements LoggerInterface
{
    public function log(string $message): void
    {
        echo "Logged: {$message}\n";
    }
}
