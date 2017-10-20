start:
	bin/console server:start 0.0.0.0:8000

stop:
	bin/console server:stop

install:
	composer install
	make fixtures
	make admin

fixtures:
	bin/console doctrine:database:drop --force
	bin/console doctrine:database:create
	bin/console doctrine:schema:update --force
	bin/console assets:install
	bin/console ski:fixtures
	bin/console ski:admin

admin:
	bin/console ski:admin
