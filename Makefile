start:
	bin/console server:start 0.0.0.0:8000

stop:
	bin/console server:stop

fix:
	bin/console doctrine:database:drop --force
	bin/console doctrine:database:create
	bin/console doctrine:schema:update --force
	bin/console cat:fix 5
