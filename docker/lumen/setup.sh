#!/bin/sh
set -e

# 🐳 App setup 🐳

curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# TODO: run all background processes

