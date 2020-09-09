
## About
This project is made in purpose of testing.<br>
This project is about monitoring the latest price of BTC/IDR from INDODAX using web socket. The price history will not stored on database for later use since our main goal is only monitoring the latest price in real-time.
<p align="center">
<img src="https://laravel.com/assets/img/components/logo-laravel.svg" width="128px">
<img src="https://www.shareicon.net/data/256x256/2015/09/11/99371_javascript_512x512.png" width="64px">
<img src="https://redis.io/images/redis-white.png" width="128px">
</p>

## Requiremnt
---
- [PHP 5.6+](https://www.php.net)
- [NodeJS](https://nodejs.org/)
- [Redis](https://redis.io)
- [Composer](https://getcomposer.org)
- [Indodax public API Documentation](https://indodax.com/downloads/BITCOINCOID-API-DOCUMENTATION.pdf)

<br>
<br>

## Setup the project
---
It is highly recommend to use `setup.sh` to setup the project. The following task will be execued:
### Install Laravel Dependencies
```
composer install
npm install
```
<br>

### Setup environtment configuration
```
copy .env.example .env
cat >> .env << EOF
INDODAX_API_KEY=null_is_ok
INDODAX_SECRET_KEY=null_is_ok
EOF
```
note:
> `INDODAX_API_KEY` and `INDODAX_SECRET_KEY` is a required key to access personal account information and trasaction on indodax so i could not share with you, but still working properly to get latest BTC price

<br>

### Generate app key
```
php artisan key:generate
```
<br>

### Generate price history
```
echo "[]" > storage/app/public/btc_idr_history.json
for i in {1..300}
do
    echo "$i of 300"
	php artisan market:saveBtcPrice;
	sleep 1;
done
```
<br>
<br>

## Run the app
---
### Run the php server
```
php artisan serve
```
<br>

### Run the node server
```
node server.js
```
<br>

## Contributing
---
Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).
<br>
<br>

## License
---
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
