# repo

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A wrapper for the Instagram api v2

## TODO
- add signature to calls: https://www.instagram.com/developer/secure-api-requests/ this requires the user to enforce signed requests in his app for better security.
- support JSONP
- support pagination (on endpoints which support count)
- subscriptions
- add docblocks

## Install

Via Composer

``` bash
$ composer require bencavens/instagram
```

## Quickstart


``` php
    
    // Init your instagram object
    $instagram = \Bencavens\Instagram\Instagram::init();
    
    // Get your recent media
    $instagram->you()->media()->get();
    
```

## Authentication flow
Instagram API requires user authentication before it can make any requests on behalf of that user.

1. [Register your application](https://www.instagram.com/developer/clients/manage/). This will assign your application with an unique clientid and -secret.

### Direct the user  to the Instagram authorization URL. 
This url can be generated via `$instagram->oauth()->getAuthorizationUrl()`
Each user will need to authorize your application via Oauth. The flow is discussed below.

The `getAuthorizationUrl()` method accepts an optional _scopes_ array to define custom permissions. By default the `basic` permission are set._ 

Available permissions:
basic - to read a user’s profile info and media
public_content - to read any public profile info and media on a user’s behalf
follower_list - to read the list of followers and followed-by users
comments - to post and delete comments on a user’s behalf
relationships - to follow and unfollow accounts on a user’s behalf
likes - to like and unlike media on a user’s behalf

3. Once authorized, your application can make requests on behalf of the authenticated user.

## Usage



## Testing

``` bash
$ vendor/bin/phpunit
```

## Security

If you discover any security related issues, please email cavensben@gmail.com instead of using the issue tracker.

## Credits

- Ben Cavens <cavensben@gmail.com>

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/bencavens/instagram.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/bencavens/instagram/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/bencavens/instagram.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/bencavens/instagram.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/bencavens/instagram.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/bencavens/instagram
[link-travis]: https://travis-ci.org/bencavens/instagram
[link-scrutinizer]: https://scrutinizer-ci.com/g/bencavens/instagram/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/bencavens/instagram
[link-downloads]: https://packagist.org/packages/bencavens/instagram
[link-author]: https://github.com/bencavens