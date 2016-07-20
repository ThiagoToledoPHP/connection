# Connection Class
PHP Class for connection to MySql database using mysqli.

It has tests in PHPUnit (Main methods), treatment with different versions of PHP, exception handling and custom output errors to Debug.


**Example 1 - Silent mode:**
    
        //Set a connections strings host, user, pass and DBname and set the Silent mode for errors
        $connection = new Connection("DbHost","DbUser","DbPass","DbName");
        
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


**Example 2 - Exception mode (create a exception in errors):**

    //...
        
        try{
        
            //Set a connections strings and set the Exception mode for errors
            $connection = new Connection("DbHost","DbUser","DbPass","DbName",true);
                            
    //...
    
**Example 3 - Friendly print error**
 
    //...
        //Set a connections strings and set the Print mode for errors (including backtrace \º/ \º/ )
        $connection = new Connection("DbHost","DbUser","DbPass","DbName",false,true);
                        
    //...
    
_Notice: I created this class to study on Composer , Packagist , TDD with PHPUnit , PSR , MySQLi and Continuous Integration .
        In its initial version use for only for studies._