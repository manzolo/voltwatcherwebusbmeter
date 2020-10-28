#!/bin/sh
set -e
 
bin/console cache:warm
symfony server:start --no-tls --port=8010