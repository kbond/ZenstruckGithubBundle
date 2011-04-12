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

3. Add the ``Github`` prefix to your autoloader:

        // app/autoload.php
        $loader->registerPrefixes(array(
            'Github_'            => __DIR__.'/../vendor/php-github-api/lib'
            // your other prefixes
        ));

4. Add this bundle to your application's kernel:

        // app/AppKernel.php
         public function registerBundles()
         {
             return array(
                 // ...
                 new Zenstruck\GithubCMSBundle\GithubCMSBundle(),
                 // ...
             );
         }

# Configuration

    # app/config/config.yml
    zenstruck_github_cms:
        user: # the github username
        repo: # the github reponame
        branch: master # git branch

# Usage
    
    // get service
    $repo = $this->get('zenstruck.github.filesystem');

    // get file from repo (extension required)
    $index = $repo->getFile('index.md');

    $contents = $index->getContent();

    // get a file from (extension not required)
    $index = $repo->getMatchingFile('index');

    $contents = $index->getContent();

    // get file list
    $files = $repo->getFileList();

    // get file list in subdir
    $files = $repo->getFileList('subdir');

# Todo

* Controller to publish content file based on a github path
* Allow deeper levels (need slashes in route params - not sure how to do this)
* Cache?