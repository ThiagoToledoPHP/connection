# Connection Class  [![Build Status](https://travis-ci.org/ThiagoToledoPHP/Connection.svg?branch=master)](https://travis-ci.org/ThiagoToledoPHP/Connection)  [![Code Climate](https://codeclimate.com/github/ThiagoToledoPHP/Connection/badges/gpa.svg)](https://codeclimate.com/github/ThiagoToledoPHP/Connection)  [![Latest Stable Version](https://img.shields.io/packagist/v/thiagotoledo/connection.svg)](https://packagist.org/packages/thiagotoledo/connection)
[PHP Class](http://php.net/manual/pt_BR/language.oop5.php) for connection and use [MySql](https://www.mysql.com/) database using [mysqli](http://php.net/manual/pt_BR/book.mysqli.php).

It has tests in [PHPUnit](https://phpunit.de/), treatment with different versions of PHP and exception handling.

To check the quality of the source code of the class, was introduced to the service configuration [Code climate]( https://codeclimate.com ) to the project .
The code is checked by the project [PHP Mess Detector](https://phpmd.org/) and [PHP Code Sniffer](http://pear.php.net/package/PHP_CodeSniffer) using [PSR1](http://www.php-fig.org/psr/psr-1/) and [PSR2](http://www.php-fig.org/psr/psr-2/) Standards further increasing the reliability of the class.


**Example 1 - Silent mode:**
    
``` php
<?php
        
    //Set a connections strings host, user, pass and DBname and set the Silent mode for errors
    $connection = new Toledo\Helpers\Connection("DbHost","DbName","DbUser","DbPass");
    
    //Start a connection
    $connection->start();
    
    //Execute a Query
    $connection->executeQuery("Select * from client");
    
    //Fecth a array of objects
    $ResultSetFetchArrayObjects = $connection->getResultSetFetchArrayObjects();
    
    //Stop the connection
    $connection->stop();
    
    //Save errors to Log. Example of hipotetic Log class
    $myLog->save($connection->getErrorMsg() . " - " . $connection->getErrorCode());


?>
```

**Example 2 - Exception mode (create a exception in errors):**

``` php
<?php            
    
    try{
    
        //Set the Exception mode for errors
        $connection = new Toledo\Helpers\Connection("DbHost","DbName","DbUser","DbPass");
        $connection->setGenerateException(true);
                        
    //...
    
?>
```

**Example 3 - Prepared Statement (New alpha feature):**


``` php
<?php
    
    //Set a connections strings host, user, pass and DBname and set the Silent mode for errors
    $connection = new Toledo\Helpers\Connection("DbHost","DbName","DbUser","DbPass");
        
    //Start a connection
    $connection->start();

    //SQL
    $sql_prep = "SELECT * FROM client WHERE email = ? AND fullName = ? ";
    
    //Bind parameters. Types: s = String, i = Integer, d = Double,  B = Blob
    $bind_params = "ss";
     
    //Bind values
    $bind_values = array("javaephp@gmail.com","Thiago Toledo"); 

    //Only one new method for Prepared
    $connection->createPreparedStatement($sql_prep,$bind_params,$bind_values);
    
    //Execute a Query
    $connection->executeQuery();

    //Fecth a array of objects
    $ResultSetFetchArrayObjects = $connection->getResultSetFetchArrayObjects();
    
    //Stop the connection
    $connection->stop();

?>
```   

**Example 4 - Escapes special characters in an SQL:**    

``` php
<?php
    
    //...
    
    $connection->start();
    $string_escape = $connection->getEscapeString($string);
    
    //...
?>
```
    
###Instalation

I recommend you use the composer for this (Yes, this project have a [Packagist Page](https://packagist.org/packages/thiagotoledo/connection) ).

Using in your [composer.json](https://getcomposer.org/doc/01-basic-usage.md#composer-json-project-setup) replace "releaseNumber" for your prefered release ([See the releases](https://github.com/ThiagoToledoPHP/Connection/releases)) :


``` javascript
{
    "require": {
        "thiagotoledo/connection": "releaseNumber"
    }
}
```
    
###Notice
  
_I created this class to study [Composer] , [Packagist] , [TDD] with [PHPUnit] , [PSR] , [MySQLi] and [Continuous Integration] .
        In its initial version use for only for studies._
      
###References

Important references to you understand and study this project:
        
[PHP Class] <http://php.net/manual/pt_BR/language.oop5.php>

[MySql] <https://www.mysql.com/>
        
[PHPUnit] <https://phpunit.de/>

[Composer] <https://getcomposer.org/>

[Packagist] <https://packagist.org/>

[MySQLi] <http://php.net/manual/pt_BR/book.mysqli.php>

[Continuous Integration] <https://en.wikipedia.org/wiki/Continuous_integration>

[TDD] <https://pt.wikipedia.org/wiki/Test_Driven_Development>

[PHP Mess Detector] <https://phpmd.org/>

[PHP Code Sniffer] <http://pear.php.net/package/PHP_CodeSniffer>

[PSR] <http://www.php-fig.org/psr/>

[PSR1] <http://www.php-fig.org/psr/psr-1/>

[PSR2] <http://www.php-fig.org/psr/psr-2/>
