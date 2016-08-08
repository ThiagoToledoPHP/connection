# Connection Class  [![Build Status](https://travis-ci.org/ThiagoToledoPHP/Connection.svg?branch=master)](https://travis-ci.org/ThiagoToledoPHP/Connection)  [![Code Climate](https://codeclimate.com/github/ThiagoToledoPHP/Connection/badges/gpa.svg)](https://codeclimate.com/github/ThiagoToledoPHP/Connection)
[PHP Class](http://php.net/manual/pt_BR/language.oop5.php) for connection and use [MySql](https://www.mysql.com/) database using [mysqli](http://php.net/manual/pt_BR/book.mysqli.php).

It has tests in [PHPUnit](https://phpunit.de/), treatment with different versions of PHP, exception handling and custom output errors to Debug.


**Example 1 - Silent mode:**
    
``` php
<?php
        
    //Set a connections strings host, user, pass and DBname and set the Silent mode for errors
    $connection = new Connection("DbHost","DbName","DbUser","DbPass");
    
    //Start a connection
    $connection->start();
    
    //Execute a Query
    $connection->executeQuery("Select * from client");
    
    //Fecth a array of objects
    $ResultSetFetchArrayObjects = $connection->getResultSetFetchArrayObjects();
    
    //Stop the connection
    $connection->stop();
    
    //Save errors to Log. Example of hipotetic Log class
    $myLog->save($connection->getErrorMsg() . " - " . $connection->getErrorNumber());


?>
```

**Example 2 - Exception mode (create a exception in errors):**

``` php
<?php            
    
    try{
    
        //Set the Exception mode for errors
        $connection = new Connection("DbHost","DbName","DbUser","DbPass");
        $connection->setGenerateException(true);
                        
    //...
    
?>
```
   
    
###Instalation

I recommend you use the composer for this (Yes, this project have a [Packagist Page](https://packagist.org/packages/thiagotoledo/connection) ).

Using compose:

`composer require thiagotoledo/connection`
    
###Notice
  
_I created this class to study [Composer] , [Packagist] , [TDD] with [PHPUnit] , [PSR] , [MySQLi] and [Continuous Integration] .
        In its initial version use for only for studies._
      
###References

Important references to you understand and study this project:
        
[PHP Class] <http://php.net/manual/pt_BR/language.oop5.php>

[MySql] <https://www.mysql.com/>
        
[PHPUnit] <https://phpunit.de/>

[Composer] <https://getcomposer.org/>

[PSR] <http://www.php-fig.org/psr/>

[Packagist] <https://packagist.org/>

[MySQLi] <http://php.net/manual/pt_BR/book.mysqli.php>

[Continuous Integration] <https://en.wikipedia.org/wiki/Continuous_integration>

[TDD] <https://pt.wikipedia.org/wiki/Test_Driven_Development>
