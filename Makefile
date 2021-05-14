.PHONY: check
check: build-abnfgen lint cs tests phpstan

.PHONY: tests
tests:
	php vendor/bin/phpunit

.PHONY: lint
lint:
	php vendor/bin/parallel-lint --colors \
		src tests \
		--exclude tests/PHPStan/Analyser/data \
		--exclude tests/PHPStan/Rules/Methods/data \
		--exclude tests/PHPStan/Rules/Functions/data

.PHONY: cs
cs:
	composer install --working-dir build-cs && php build-cs/vendor/bin/phpcs

.PHONY: cs-fix
cs-fix:
	php build-cs/vendor/bin/phpcbf

.PHONY: phpstan
phpstan:
	php vendor/bin/phpstan analyse -l 5 -c phpstan.neon src tests

.PHONY: build-abnfgen
build-abnfgen:
	./build-abnfgen.sh
