default := 'dev'
PHPUNIT := 'vendor/bin/phpunit -d xdebug.max_nesting_level=250 -d memory_limit=1024M --coverage-php storage/coverage/coverage.cov'

# [Homestead] Set up backend installation
backend:
	composer install  --prefer-dist --optimize-autoloader --no-suggest
	php artisan key:generate
	php artisan migrate

# [System] Set up frontend installation
frontend:
	yarn
	yarn dev
	if os_family() = 'unix'; then pre-commit install; fi

# [System] Compile frontend assets
assets env=default:
	yarn {{env}}

# [Homestead] Run unit and integration tests
@test:
	echo "Running unit and integration tests"; \
	sudo phpdismod -s fpm xdebug; \
	sudo service php7.1-fpm reload; \
	{{PHPUNIT}};
	php artisan dusk;

# [Homestead] Run tests and create code-coverage report
@coverage:
	echo "Running unit and integration tests"; \
	echo "Once completed, the generated code coverage report can be found under ./reports)"; \
	mv storage/framework/cache/data/ storage/framework/cache/data-bu; \
	mkdir storage/framework/cache/data; \
	[ -d "storage/coverage" ] && ls storage/coverage/* | grep -v .gitignore | xargs rm -r; \
	sudo phpenmod -s fpm xdebug; \
	sudo service php7.1-fpm reload; \
	{{PHPUNIT}}; \
	echo "Running browser tests"; \
	php artisan dusk; \
	vendor/bin/phpcov merge -v storage/coverage/ --html=reports/; \
	sudo phpdismod -s fpm xdebug; \
	sudo service php7.1-fpm reload; \
	rm -rf storage/framework/cache/data; \
	mv storage/framework/cache/data-bu storage/framework/cache/data;

# [Homestead] Lint files
@lint:
	vendor/bin/php-cs-fixer fix
