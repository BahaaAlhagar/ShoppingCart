# ShoppingCart

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

a Simple ShoppingCart impelementaion for Laravel.

## Install

Install the package through [Composer](http://getcomposer.org/).

``` bash
	composer require BahaaAlhagar/ShoppingCart
```
If you're using Laravel 5.5, this is all there is to do.
if you're using Laravel 5.4 or less version then you have the register the package service provider and the package alias in your app.
open `config/app.php` file.
Add a new line to the `providers` array:

``` bash
	BahaaAlhagar\ShoppingCart\ShoppingCartServiceProvider::class,
```
And add a new line to the `aliases` array:

``` bash
	'Cart' => BahaaAlhagar\ShoppingCart\Facades\Cart::class,
```

## Usage

The ShoppingCart gives you the following methods to use:

### Cart::add()

Adding an item to the cart is really simple, you just use the `add()` method, which accepts a variety of parameters.

In its most basic form you can specify the product Model, quantity ($qty) you'd like to add to the cart.
note that the Product model must have a price column.

```php
Cart::add($productModel, $qty);
```
if you didnt specify the $qty the cart will assume its 1 item.

As an optional third parameter you can pass it options, so you can add multiple items with the same id, but with (for instance) a different size or color for example.

```php
Cart::add($productModel, $qty, ['size' => 'large', 'color' => 'black']);
```

**The `add()` method will return CartItem array of the item you just added to the cart.**


### Cart::modify()

To modify an item in the cart, you'll first need the uinqueIndex of the item.

the uniqueIndex is the item offset in the cart Model items array.

Next you can use the `modify()` method to modify it.

If you simply want to modify the quantity, you'll pass the modify method the uinqueIndex and the new quantity:

```php
$uinqueIndex = '20185a4530208f76b2ef3eb95307a021';

Cart::modify($uinqueIndex, 2); // Will modify the quantity
```

### Cart::reduceOneItem()
to reduce certain Cart item quantity by 1, you'll first need the uinqueIndex of the item.

then 

```php
$uinqueIndex = '20185a4530208f76b2ef3eb95307a021';

Cart::reduceOneItem($uinqueIndex); // will reduce item quantity by 1
```


### Cart::remove()

To remove an item for the cart, you'll again need the uniqueIndex. This uniqueIndex you simply pass to the `remove()` method and it will remove the item from the cart.

```php
$uniqueIndex = '20185a4530208f76b2ef3eb95307a021';

Cart::remove($uniqueIndex);
```


### Cart::getContent()

Of course you also want to get the carts content. This is where you'll use the `getContent` method. This method will return a Cart model which you can iterate over and show the content to your customers.

```php
Cart::getContent();
```


### Cart::destroy()

If you want to completely remove the content of a cart, you can call the destroy method on the cart. This will remove the cart from Session.

```php
Cart::destroy();
```


### Cart::total()

The `total()` method can be used to get the calculated total of all items in the cart, given there price and quantity.

```php
Cart::total();
```



### Cart::count()

If you want to know how many items there are in your cart, you can use the `count()` method. This method will return the total number of items in the cart. So if you've added 2 books and 1 shirt, it will return 3 items.

```php
Cart::count();
```


## Exceptions

The Cart package will throw exceptions if something goes wrong. This way it's easier to debug your code using the Cart package or to handle the error based on the type of exceptions. The Cart packages can throw the following exceptions:

| Exception                    | Reason                                                                             |
| ---------------------------- | ---------------------------------------------------------------------------------- |
| *CartIsEmptyException* | When trying to modify or reduce a cart item and the cart already empty |
| *InvalidUniqueIndexException*      | When the uniqueIndex that got passed doesn't exists in the cart         |
| *InvalidQuantityException*      | When you try to add negative or float quantity.                    |

## Events

The cart also has events build in. There are five events available for you to listen for.

| Event         | Fired                                    | Parameter                        |
| ------------- | ---------------------------------------- | -------------------------------- |
| cartItem.added    | When an item was added to the cart.      | The `CartItem` that was added.   |
| cartItem.modified  | When an item in the cart was modified or reduced by 1.    | The `CartItem` that was modified. |
| cartItem.removed  | When an item is removed from the cart.   | The `CartItem` that was removed. |
| cart.destroyed   | When the content of a cart was destroyed.   | return the removed Cart Model. |

## Example

you may use the `php artisan make:shop` command for a quick code setup, and test the Cart.



## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email bahaa.rock@gmail.com instead of using the issue tracker.

## Credits

- [Bahaa Alhagar][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/BahaaAlhagar/ShoppingCart.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/BahaaAlhagar/ShoppingCart/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/BahaaAlhagar/ShoppingCart.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/BahaaAlhagar/ShoppingCart.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/BahaaAlhagar/ShoppingCart.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/BahaaAlhagar/ShoppingCart
[link-travis]: https://travis-ci.org/BahaaAlhagar/ShoppingCart
[link-scrutinizer]: https://scrutinizer-ci.com/g/BahaaAlhagar/ShoppingCart/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/BahaaAlhagar/ShoppingCart
[link-downloads]: https://packagist.org/packages/BahaaAlhagar/ShoppingCart
[link-author]: https://github.com/https://github.com/BahaaAlhagar
[link-contributors]: ../../contributors
