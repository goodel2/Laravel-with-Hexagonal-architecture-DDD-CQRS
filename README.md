## Technical test for Bodeboca

A shopping cart built on Laravel 9 that implements DDD + Hexagonal Architectue + CQRS.

This shopping cart allow us to add and remove products, and to calculate 
the total amount of the shopping cart both in euros and in other currencies.

There are some restrictions on the shopping cart:

- A maximum of `10 different products` can be added.
- A maximum of `50 units per product` can be added.
- The products have a normal price and a discounted price, 
depending on a minimum number of units that each product must 
have for the `discounted price` to be applied.
- The cart has to be able to show the `total amount` with and 
without discount.
- The cart must show the total amount in euros and 
in another `currency`, if necessary.

### How to configure this project

First of all, you have to copy the `.env.example` file, located on the project 
root path, to a new one called `.env`

Then you can to run this command to install all dependencies:

```
composer install
```

To start the project in a virtual machine using docker, you need to run (Docker Desktop is required):

```
./vendor/bin/sail up
```

You can run the tests with the following commands:

```
./vendor/bin/phpunit --group=Domain
```

```
./vendor/bin/phpunit --group=Infrastructure
```

You can check the errors found by `psalm` using this command:

```
./vendor/bin/psalm
```

And the command rector php to check refactorizations:

```
./vendor/bin/rector process src --dry-run
```
