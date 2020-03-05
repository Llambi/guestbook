SHELL := /bin/bash

dev:
	symfony run -d --watch=config,src,templates,vendor symfony console messenger:consume async
	symfony run -d yarn encore dev --watch
	symfony server:start -d

tests:
	symfony console doctrine:fixtures:load -n
	symfony run bin/phpunit

.PHONY: tests dev