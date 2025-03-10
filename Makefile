OS := $(shell uname)


start:
ifeq ($(OS),Darwin)
	docker volume create --name=app-sync
	docker-compose -f docker-compose-dev.yml up -d
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

start_dev:
	docker-compose build --no-cache
	docker-compose up
stop_dev:
	docker-compose down
restart_dev:
	docker-compose down
	docker-compose build --no-cache
	docker-compose up

