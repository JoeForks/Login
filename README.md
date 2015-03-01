# StyleCI Login ![Analytics](https://ga-beacon.appspot.com/UA-60053271-6/StyleCI/Login?pixel)


StyleCI Login was created by, and is maintained by [Graham Campbell](https://github.com/GrahamCampbell), and is a login with GitHub provider. Feel free to check out the [change log](CHANGELOG.md), [releases](https://github.com/StyleCI/Login/releases), [license](LICENSE), [api docs](http://docs.grahamjcampbell.co.uk), and [contribution guidelines](CONTRIBUTING.md).

![StyleCI Login](https://cloud.githubusercontent.com/assets/2829600/6430512/0ba7a11e-c005-11e4-89be-87eb9bdc58cc.png)

<p align="center">
<a href="https://travis-ci.org/StyleCI/Login"><img src="https://img.shields.io/travis/StyleCI/Login/master.svg?style=flat-square" alt="Build Status"></img></a>
<a href="https://scrutinizer-ci.com/g/StyleCI/Login/code-structure"><img src="https://img.shields.io/scrutinizer/coverage/g/StyleCI/Login.svg?style=flat-square" alt="Coverage Status"></img></a>
<a href="https://scrutinizer-ci.com/g/StyleCI/Login"><img src="https://img.shields.io/scrutinizer/g/StyleCI/Login.svg?style=flat-square" alt="Quality Score"></img></a>
<a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square" alt="Software License"></img></a>
<a href="https://github.com/StyleCI/Login/releases"><img src="https://img.shields.io/github/release/StyleCI/Login.svg?style=flat-square" alt="Latest Version"></img></a>
</p>


## Installation

[PHP](https://php.net) 5.5+ or [HHVM](http://hhvm.com) 3.3+, and [Composer](https://getcomposer.org) are required.

To get the latest version of StyleCI Login, simply add the following line to the require block of your `composer.json` file:

```
"styleci/login": "0.1.*"
```

You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.

If you're using Laravel 5, then you can register our service provider. Open up `login/app.php` and add the following to the `providers` key.

* `'StyleCI\Login\LoginServiceProvider'`


## Usage

StyleCI Login is designed to allow users to login with GitHub. There is currently no real documentation for this package, but feel free to check out the [API Documentation](http://docs.grahamjcampbell.co.uk) for StyleCI Login.


## License

StyleCI Login is licensed under [The MIT License (MIT)](LICENSE).
