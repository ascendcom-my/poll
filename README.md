# Bigmom Poll

Voting/Polling package. Contains an example widget, but not the best.

## Installation

- `composer require bigmom/hook`
- `php artisan vendor:publish`
- `php artisan migrate`

If [bigmom/auth](https://packagist.org/packages/bigmom/auth) has not been published yet, please do so.

## Usage

Access the management UI through `/bigmom`. Use the Vote Facade to cast votes.