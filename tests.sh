#!/bin/bash

if [ "$2" == "-db" ]
then
echo "rebuilding database ..."
php bin/console doctrine:schema:drop -n -q --force --full-database
rm migrations/*.php
rm data/*
php bin/console --env=test doctrine:database:create
php bin/console --env=test doctrine:schema:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate -n -q
php bin/console doctrine:fixtures:load -n -q
php bin/console --env=test doctrine:fixtures:load -n -q
fi

if [ -n "$1" ]
then
./bin/phpunit $1
else
./bin/phpunit
fi
