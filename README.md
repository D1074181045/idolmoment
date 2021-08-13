<p align="center"><a href="https://idolmoment-vue.herokuapp.com/" target="_blank"><img src="https://ik.imagekit.io/7bjbvrubevy/logo.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Installation

- via git clone or download
- Edit `.env` and set your database connection details
#### Run

```bash
composer install
npm install
php artisan key:generate
php artisan jwt:secret
php artisan migrate --seed
```

## Usage

#### Development

```bash
npm run dev
```

#### Production

```bash
npm run prod
```