.PHONY: install test

install:
	composer install

test:
	./vendor/bin/phpunit -c phpunit.xml
