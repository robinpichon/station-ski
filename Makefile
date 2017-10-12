start:
	bin/console server:start 0.0.0.0:8000

stop:
	bin/console server:stop

install:
	composer install
	make fixtures

fixtures:
	bin/console doctrine:database:drop --force
	bin/console doctrine:database:create
	bin/console doctrine:schema:update --force
	bin/console ski:fixtures
