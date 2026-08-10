# Define services and Docker Compose file
COMPOSE_FILE = docker-compose.yml
DOCKER_COMPOSE = docker-compose -f $(COMPOSE_FILE)

# Start the services
start:
	$(DOCKER_COMPOSE) up -d

# Restart the services
restart:
	$(DOCKER_COMPOSE) down
	$(DOCKER_COMPOSE) up -d

# Stop the services
stop:
	$(DOCKER_COMPOSE) down

# View the logs of the app
logs:
	$(DOCKER_COMPOSE) logs -f app

# Rebuild the containers
rebuild:
	$(DOCKER_COMPOSE) down --volumes --remove-orphans
	$(DOCKER_COMPOSE) up --build -d

# Enter the Symfony container shell
shell:
	docker exec -it company-register-api bash

# Run PHPUnit tests
test:
	docker exec -it company-register-api vendor/bin/simple-phpunit

# Clear Symfony cache
cache-clear:
	docker exec -it company-register-api bin/console cache:clear
