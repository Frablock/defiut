ifeq ($(OS),Windows_NT)
    OS := Windows
else
    OS := $(shell uname)
endif
start:
ifeq ($(OS),Darwin)
	docker volume create --name=app-sync
	docker-compose -f compose.yml up -d
	docker-sync start
else
	docker-compose up -d
endif
stop:
ifeq ($(OS),Darwin)
	docker-compose stop
	docker-sync stop
else
	docker-compose stop
endif
soft_start_dev:
	docker-compose up
start_dev:
	docker-compose build --no-cache
	docker-compose up
stop_dev:
	docker-compose down
restart_dev:
	docker-compose down
	docker-compose build --no-cache
	docker-compose up
compile:
	docker-compose exec npm npm run watch
clear:
ifeq ($(OS),Darwin)
	rm -r ./vendor ./node_modules ./var ./public/build
else
	if exist vendor rmdir /s /q vendor
	if exist node_modules rmdir /s /q node_modules
	if exist var rmdir /s /q var
	if exist public\build rmdir /s /q public\build
endif

clean_start_dev: clear start_dev

bash:
	docker exec -it defiut-php-1 "/bin/bash"