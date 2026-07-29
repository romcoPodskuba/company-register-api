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

## Tasks

| Task         | Branch         | Pull Request                                        |
| ------------ | -------------- | --------------------------------------------------- |
| 1. Algorithm | algorithm-task | https://github.com/romcoPodskuba/sf-demo-api/pull/1 |
| 2. Database  | database-task  | https://github.com/romcoPodskuba/sf-demo-api/pull/2 |
| 3. PHP       | php-task       | https://github.com/romcoPodskuba/sf-demo-api/pull/3 |

## Answers:

### Odpoveď k otázkam na zamyslenie zo zadania databázovej úlohy:

Pri tabuľke s väčším počtom riadkov by som pridal index na stĺpec value, pretože podľa neho sa zgrupuje aj filtruje. Vo väčšine prípadov by mal index stačiť. Ďalšiu optimalizáciu by som zvažoval až v prípade, že by to ukázalo EXPLAIN alebo ďalšia analýza problému.
