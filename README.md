# Connection Class
[PHP Class](http://php.net/manual/pt_BR/language.oop5.php) for connection to [MySql](https://www.mysql.com/) database using [mysqli](http://php.net/manual/pt_BR/book.mysqli.php).

It has tests in [PHPUnit](https://phpunit.de/), treatment with different versions of PHP, exception handling and custom output errors to Debug.


**Example 1 - Silent mode:**
    
        ```php
        
            //Set a connections strings host, user, pass and DBname and set the Silent mode for errors
            $connection = new Connection("DbHost","DbName","DbUser","DbPass");
            
            //Start a connection
            $connection->start();
            
            //Execute a Query
            $connection->executeQuery("Select * from cliente");
            
            //Fecth a array of objects
            $ResultSetFetchArrayObjects = $connection->getResultSetFetchArrayObjects();
            
            //Stop the connection
            $connection->stop();
            
            //Save errors to Log. Example of hipotetic Log class
            $myLog->save($connection->getErrorMsg() . " - " . $connection->getErrorNumber());
        
        ```

**Example 2 - Exception mode (create a exception in errors):**

    ```php
    
        //...
            
            try{
            
                //Set the Exception mode for errors
                $connection = new Connection("DbHost","DbName","DbUser","DbPass",true);
                                
        //...
    
    ```
    
**Example 3 - Friendly print error stop the application**
 
    ```php
    
        //...
            //Set the Print mode stop for errors (including backtrace \º/ \º/ )
            $connection = new Connection("DbHost","DbUser","DbPass","DbName",false,true);
                            
        //...
    
    ```    

**Example 4 - Friendly print error don't stop the application**
 
    ```php
    
        //...
            //Print mode for errors don't stop the application (backtrace too \º/ \º/ )
            $connection = new Connection("DbHost","DbUser","DbPass","DbName",false,true,false);
                            
        //...
    
    ```    
    
    
**Notice**
  
_I created this class to study [Composer] , [Packagist] , [TDD] with [PHPUnit] , [PSR] , [MySQLi] and [Continuous Integration] .
        In its initial version use for only for studies._
      
**References**

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
