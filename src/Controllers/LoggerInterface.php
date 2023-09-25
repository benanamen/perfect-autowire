<?php declare(strict_types=1);

interface LoggerInterface
{
    public function log(string $message): void;
}
