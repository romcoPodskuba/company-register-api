# company-register-api

Symfony API for fetching company data from the Czech business register (ARES).

> **Note:** This repository is a technical showcase. It demonstrates architecture, design decisions, and integration with an external register — it is not intended as a fully featured, production-ready application.

## About

Loads company data from [ARES](https://ares.gov.cz) by Czech business ID (IČO) and returns it as structured, immutable DTOs ready for further use.

The implementation validates the business ID (format and checksum) before calling the external API, maps the ARES response to domain objects, and handles error states explicitly — invalid input, not found, rate limiting, and register unavailability.

Data source: `GET https://ares.gov.cz/ekonomicke-subjekty-v-be/rest/ekonomicke-subjekty/{ICO}`

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

## Architecture decisions

- **Provider pattern with interfaces** — `CompanyRegisterProviderInterface` and `BusinessIdValidatorInterface` decouple the application from ARES. Swapping the data source means implementing new providers, not changing controllers or services.
- **Immutable DTOs** — ARES responses are mapped to `Company` and `Address` value objects. The API returns a stable, domain-specific shape instead of exposing the raw register payload.
- **Validation before the HTTP call** — Business ID format and checksum are verified locally first. Invalid input fails fast without hitting the external API. Leading zeros are not padded: the client must send all 8 digits, so incomplete input is rejected instead of silently resolving to a different company.
- **Layered responsibilities** — `ApiClient` handles HTTP and status codes, `Mapper` transforms the response, `CompanyRegisterProvider` orchestrates the flow. Each class has a single reason to change.

## Unit tests

Unit tests use mocked HTTP client.

```bash
make test
```
