php-orcid
=========

Installation
------------

The library requires PHP5 and CURL for PHP. The following statements are for 
Ubuntu 14.04 LTS.

    sudo apt-get install php5 libapache2-mod-php5
    sudo apt-get install php5-curl

In order to test php-orcid, you need to install the PHP command line interpreter 
and [PHPUnit](https://phpunit.de/getting-started.html). Ubuntu 14.04 LTS ships 
with PHP 5.5 and needs to be updated to 5.6.

    sudo add-apt-repository ppa:ondrej/php5-5.6
    sudo apt-get update
    sudo apt-get install python-software-properties
    sudo apt-get update
    sudo apt-get install php5-cli
    php5 -v
    wget https://phar.phpunit.de/phpunit.phar
    chmod +x phpunit.phar
    sudo mv phpunit.phar /usr/local/bin/phpunit
    phpunit --version

Configuration
-------------

Copy the file `config.example.php` to `src/config.php` and edit the 
configuration according to your ORCID application.

Example
-------

The file `index.php` uses the `php-orcid` library and provides example usage 
code. The example demonstrates authorization and token request. The ORCID iD of
the authorized user is returned.

Tests
-----

    phpunit tests/ORCIDTest

Token
-----

Having authorized a user, a client can exchange the `code` returned by ORCID 
with an `access token`. With this request, the client also obtains the ORCID
iD of the user.

    $orcid = new ORCID($config['clientId'], $config['clientSecret']);
    $response = $orcid->getToken($_GET['code']);

    echo 'ORCID: ' . $response->orcid;