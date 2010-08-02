PHPRoutes
=========

PHPRoutes is a simple framework to map URLs to application actions, inspired
by [Routes](http://routes.groovie.org).

Requirements
------------

* PHP 5.x
* [PHPUnit](http://www.phpunit.de/) (for unit tests)

Usage
-----

    <?php
    $mapper = new PRMapper();
    $mapper->connect(null, '/{controller}/{action}/');
    // (...)
    if ($mapper->match('/product/buy/')) {
        // do something
    }
    ?>

Licensing
---------

This project is under MIT license (see MIT-LICENSE file).

Contributing
------------

To contribute to the project, for it on GitHub and send me a pull request. If
you prefer, you can send a patch generated with `git format-patch`.

Don't forget to write an unit test for your feature/bug.