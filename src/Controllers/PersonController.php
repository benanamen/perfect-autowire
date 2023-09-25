<?php declare(strict_types=1);

class PersonController
{
    public function __construct(private LoggerInterface $logger) {}

    #[Route('/person')]
    public function index(): void
    {
        echo 'Person';
    }

    #[Route('GET', '/people/([1-9][0-9]*)')]
    public function show(string $id): false|string|null
    {
        try {
            $person = $this->peopleService->getPersonById($id);

            if (!$person) {
                throw new NotFoundException();
            }

            $this->renderView(self::LAYOUT_PATH, [
                'template' => self::TEMPLATE_PATH . 'admin/people/person-details.php',
                'pageTitle' => 'People Details Report',
                'person' => $person,
                'full_name' => $this->getFullName(),
            ]);
        } catch (NotFoundException) {
            $this->sendErrorResponse(404);
        }
        return null;
    }
}
