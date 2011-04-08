# Introduction

Enables a Github repository to become the host of static content for your cms.

# Requirements

* [php-github-api](https://github.com/ornicar/php-github-api)

# Installation

1. Add this bundle and php-github-api to your Symfony2 project:

    $ git submodule add git://github.com/kbond/GithubCMSBundle.git vendor/bundles/Zenstruck/GithubCMSBundle
    $ git submodule add git://github.com/ornicar/php-github-api.git vendor/php-github-api

2. Add the ``Zenstruck`` namespace to your autoloader:

        // app/autoload.php
        $loader->registerNamespaces(array(
           'Zenstruck' => __DIR__.'/../vendor/bundles',
           // your other namespaces
        ));

2. Add the ``Github`` prefix to your autoloader:

        // app/autoload.php
        $loader->registerPrefixes(array(
            'Github_'            => __DIR__.'/../vendor/php-github-api/lib'
            // your other prefixes
        ));

# Supported Formats

* HTML
* Markdown

# Todo

* Allow deeper levels (need slashes in route params - not sure how to do this)