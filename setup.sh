#!/usr/bin/env bash

# installing laravel dependencies...
composer install


# installing node dependencies...
npm install


# setup environment configuration...
cp .env.example .env
cat >> .env << EOF
INDODAX_API_KEY=null_is_ok
INDODAX_SECRET_KEY=null_is_ok
EOF


# Generating laravel app key
php artisan key:generate


# Generate price history, it will take 5 minute
echo "[]" > storage/app/public/btc_idr_history.json
for i in {1..300};
do
	echo "$i of 300"
	php artisan market:saveBtcPrice
	sleep 1
done