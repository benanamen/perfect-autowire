<?php declare(strict_types=1);

use PerfectApp\Routing\Route;

class UserController
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    #[Route('/user')]
    public function index(): void
    {
        $this->logger->log("UserController@index");
    }
}
