# Perfect-Autowire
## Description
Perfect-Autowire is a PHP-based project that focuses on autowiring and routing. It is designed to offer a highly decoupled architecture, employing SOLID Principles and best practices for scalable and maintainable code. It uses native PHP features and incorporates a custom-built router and dependency injection container.

## Installation

1. Clone this repository.
2. Navigate to the project folder and run `composer install` to install dependencies.
3. Configure your web server to point to the `public` directory.
4. Start your web server and navigate to the project URL.


## Features

- **Autowiring**: Automatic resolution of dependencies for controllers and services.
- **Routing**: A simple yet powerful custom router, supporting multiple HTTP methods and dynamic parameters.
- **SOLID Compliant**: The architecture is built on SOLID principles for maintainability and scalability.

## Usage
After setting up the project, routes can be defined by annotating controller methods. The router will automatically register routes from controllers in the specified directory.

### Example Route Definition
```php
#[Route('/person/([1-9][0-9]*)', ['GET', 'POST'])]
public function show(string $id): void {
// Implementation
}
```

### Route Handling
Routes are dispatched by the `Router` class, which can be extended to add custom behavior or middleware.

## Testing

    Run `phpunit` to execute the test suite, which aims for 100% coverage.
    Check code quality metrics and adhere to coding standards.

## Dependencies

    PHP 8.0 or higher
    PHPUnit for testing

## Contributions
Pull requests are welcome, following the project's coding standards and passing all existing tests.

## License
This project is licensed under the MIT License.