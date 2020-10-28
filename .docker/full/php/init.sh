#!/bin/sh
set -e

echo "Waiting mysql start..."

while ! nc -z mysql 3306; do   
  sleep 0.1 # wait for 1/10 of the second before check again
done

echo "mysql started"

bin/console cache:warm
symfony server:start --no-tls --port=8001