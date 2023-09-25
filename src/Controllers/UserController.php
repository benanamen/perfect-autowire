<?php declare(strict_types=1);

class UserController
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    #[Route('/user')]
    public function index(): void
    {
        $this->logger->log("UserController@index");
    }
}
