<?php declare(strict_types=1);

use App\Controllers\LoggerInterface;
use PerfectApp\Routing\Route;

readonly class UserController
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    #[Route('/examples/user')]
    public function index(): void
    {
        $this->logger->log("UserController@index");
    }
}
