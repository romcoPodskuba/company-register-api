# sf-demo-api

## Requirements

- Docker & Docker Compose
- PHP 8.4+ and Composer (for installing dependencies on the host)
- Make

## Getting started

1. Clone the repository
2. Install PHP dependencies:
    ```bash
    composer install
    ```
3. Start the containers:
    ```bash
    make start
    ```

See the `Makefile` for additional commands.

## URLs

| Service                        | URL                      |
| ------------------------------ | ------------------------ |
| API documentation (Swagger UI) | http://localhost/api/doc |
