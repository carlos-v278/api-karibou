#!/bin/bash
composer install --no-interaction
symfony serve -d
exec apache2-foreground