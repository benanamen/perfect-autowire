<?php declare(strict_types=1);

class PersonController
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    #[Route('/person')]
    public function index(): void
    {
        echo 'Person';
    }

    #[Route('/people/([1-9][0-9]*)', ['GET', 'POST'])]
    public function show(string $id): false|string|null
    {
        die("Yo Here People Number $id");
    }

    #[Route('/people/([1-9][0-9]*)/edit', ['GET', 'POST'])]
    public function edit(string $id): false|string|null
    {
        die("Edit Number $id");
    }
}
