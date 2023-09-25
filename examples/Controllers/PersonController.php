<?php declare(strict_types=1);

use App\Controllers\LoggerInterface;
use PerfectApp\Routing\Route;

readonly class PersonController
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    #[Route('/examples/people')]
    public function index(): void
    {
        $this->logger->log("PersonController@index");
        echo 'All People';
    }

    #[Route('/examples/person/([1-9][0-9]*)', ['GET', 'POST'])]
    public function show(string $id): void
    {
        echo "Person ID# $id";
    }

    #[Route('/examples/person/([1-9][0-9]*)/edit', ['GET', 'POST'])]
    public function edit(string $id): void
    {
        echo "Edit Person ID# $id";
    }
}
