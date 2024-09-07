.PHONY: check
check: lint cs tests phpstan

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

.PHONY: cs-install
cs-install:
	git clone https://github.com/phpstan/build-cs.git || true
	git -C build-cs fetch origin && git -C build-cs reset --hard origin/2.x
	composer install --working-dir build-cs

.PHONY: cs
cs:
	php build-cs/vendor/bin/phpcs src tests

.PHONY: cs-fix
cs-fix:
	php build-cs/vendor/bin/phpcbf src tests

.PHONY: phpstan
phpstan:
	php vendor/bin/phpstan

.PHONY: phpstan-generate-baseline
phpstan-generate-baseline:
	php vendor/bin/phpstan --generate-baseline
