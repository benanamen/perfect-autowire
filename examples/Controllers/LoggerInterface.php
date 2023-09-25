<?php declare(strict_types=1);

namespace App\Controllers;

interface LoggerInterface
{
    public function log(string $message): void;
}
