<?php

//Include the Monolog Library
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * Class ConnectionTest
 * Use to test ./lib/Helpers/Connection.php.
 * More information in: https://github.com/ThiagoToledoPHP/Connection
 * @author Thiago Toledo <javaephp@gmail.com>
 */
class ConnectionTest extends PHPUnit_Framework_TestCase {

	protected $obj = NULL;


    /**
     * Setup method to create the table client uses to test in test database
     */
	public function testSetUp() {

	    //Remove old file if exists
        $this->removeFile(DIR_LOGS.'/'. LOGS_FILE);

        $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
        $this->obj->start();

        $sql = "DROP TABLE IF EXISTS ".TEST_TABLE_NAME;

        $this->assertEquals($this->obj->executeQuery($sql),true);

	    $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
        $this->obj->start();

        $sql = "
        CREATE TABLE IF NOT EXISTS ".TEST_TABLE_NAME."
            (
                id INTEGER (11) NOT NULL AUTO_INCREMENT , 
                fullName varchar (255),
                email varchar (255),
                dateOfBirth date,
                numberOfChildren INTEGER(11),
                PRIMARY KEY (id)
            )
        ";

        $this->assertEquals($this->obj->executeQuery($sql),true);

        $sql = "
        INSERT INTO ".TEST_TABLE_NAME." (id, fullName, email, dateOfBirth, numberOfChildren)
        VALUES(NULL,'Thiago Toledo','javaephp@gmail.com','1986-03-10', 1),
        (NULL,'Loren ipsun','loren@ipsun.com','1913-12-13', 0)
        ";

        $this->assertEquals($this->obj->executeQuery($sql),true);


        $this->obj->stop();

	}


    //start method


        //ExceptionInactive tests

            /**
             * Test the method using invalid information for database
             */
            public function testConnectionStartErrorExceptionInactive(){
                $this->obj = new Toledo\Helpers\Connection(TEST_INVALID_HOST_BD,TEST_INVALID_NAME_BD,TEST_INVALID_USER_BD,TEST_INVALID_PASS_BD);
                $this->assertEquals($this->obj->start(),false);
            }

            /**
             * Test the method using valid information for database
             */
            public function testConnectionStartValidExceptionInactive(){
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
                $this->assertEquals($this->obj->start(),true);
            }

        //ExceptionActive tests

            /**
             * Test the method using invalid information for database and exception active
             * @expectedException Exception
             */
            public function testConnectionStartErrorExceptionActive(){
                $this->obj = new Toledo\Helpers\Connection(TEST_INVALID_HOST_BD,TEST_INVALID_NAME_BD,TEST_INVALID_USER_BD,TEST_INVALID_PASS_BD);
                $this->obj->setGenerateException(true);
                $this->obj->start();
            }


            /**
             * Test the method using valid information for database
             */
            public function testConnectionStartValidExceptionActive(){
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
                $this->obj->setGenerateException(true);
                $this->assertEquals($this->obj->start(),true);
            }


    //stop method

        //ExceptionInactive tests

            /**
             * Test the method using invalid information for database
             */
            public function testConnectionStopErrorExceptionInactive(){
                $this->obj = new Toledo\Helpers\Connection(TEST_INVALID_HOST_BD,TEST_INVALID_NAME_BD,TEST_INVALID_USER_BD,TEST_INVALID_PASS_BD);
                $this->assertEquals($this->obj->stop(),false);
            }

            /**
             * Test the method using valid information for database
             */
            public function testConnectionStopValidExceptionInactive(){
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
                $this->obj->start();
                $this->assertEquals($this->obj->stop(),true);
            }

        //ExceptionActive tests

            /**
             * Test the method using invalid information for database
             * @expectedException Exception
             */
            public function testConnectionStopErrorExceptionActive(){
                $this->obj = new Toledo\Helpers\Connection(TEST_INVALID_HOST_BD,TEST_INVALID_NAME_BD,TEST_INVALID_USER_BD,TEST_INVALID_PASS_BD);
                $this->obj->setGenerateException(true);
                $this->obj->stop();
            }

            /**
             * Test the method using valid information for database
             */
            public function testConnectionStopValidExceptionActive(){
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
                $this->obj->setGenerateException(true);
                $this->obj->start();
                $this->assertEquals($this->obj->stop(),true);
            }

    //executeQuery method

        //ExceptionInactive tests

            /**
             * Test the method using valid information for database and no valid query
             */
            public function testExecuteQueryErrorExceptionInactive(){
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
                $this->obj->start();
                $this->assertEquals($this->obj->executeQuery("Select from error_table"),false);
                $this->obj->stop();
            }

            /**
             * Test the method using valid information for database and valid query
             * @depends testSetUp
             */
            public function testExecuteQueryValidExceptionInactive(){
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
                $this->obj->start();
                $this->assertEquals($this->obj->executeQuery("Select * from ". TEST_TABLE_NAME),true);
                $this->obj->stop();
            }

        //ExceptionActive tests

            /**
             * Test the method using valid information for database and no valid query
             * @expectedException Exception
             */
            public function testExecuteQueryErrorExceptionActive(){
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
                $this->obj->setGenerateException(true);
                $this->obj->start();
                $this->obj->executeQuery("Select from error_table");
                $this->obj->stop();
            }

            /**
             *  Test the method using valid information for database and valid query
             *  @depends testSetUp
             */
            public function testExecuteQueryValidExceptionActive(){
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
                $this->obj->setGenerateException(true);
                $this->obj->start();
                $this->assertEquals($this->obj->executeQuery("Select * from ".TEST_TABLE_NAME),true);
                $this->obj->stop();
            }

            /**
             * Test the methods using preparedStatement method and one Statement
             * @depends testSetUp
             */
            public function testExecutePreparedStatement(){
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
                $this->obj->setGenerateException(true);
                $this->obj->start();
                $this->obj->createPreparedStatement("SELECT * FROM ".TEST_TABLE_NAME." WHERE email = ?","s",array("javaephp@gmail.com"));
                $this->obj->executeQuery();
                $this->assertEquals(sizeof($this->obj->getResultSetFetchArrayObjects()),1);
                $this->obj->stop();
            }


            /**
             * Test the methods using preparedStatement method and two Statements
             * @depends testSetUp
             */
            public function testExecutePreparedStatementTwo(){
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
                $this->obj->setGenerateException(true);
                $this->obj->start();
                $this->obj->createPreparedStatement("SELECT * FROM ".TEST_TABLE_NAME." WHERE email = ? AND fullName = ? ","ss",array("javaephp@gmail.com","Thiago Toledo"));
                $this->obj->executeQuery();
                $this->assertEquals(sizeof($this->obj->getResultSetFetchArrayObjects()),1);
                $this->obj->stop();
            }

            /**
             * Test the methods using preparedStatement method and two Statements, but using wrong sql
             * @depends testSetUp
             * @expectedException Exception
             */
            public function testExecutePreparedStatementThree(){
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
                $this->obj->setGenerateException(true);
                $this->obj->start();
                $this->obj->createPreparedStatement("SELECT * FROM ".TEST_TABLE_NAME." WHERE email = ? AND fullNam = ? ","ss",array("javaephp@gmail.com","Thiago Toledo"));
            }


     //Error tests

        //__construct method

            /**
             * Test the __construct method errors type missmatch
             * Param $dbHost
             * @expectedException Exception
             * @expectedExceptionCode 00006
             */
            public function test__ConstructErrorTypeMissmatchParamDbHost(){

                $lineLogError = 1;
                $errorNumber = "00006";

                //Remove old file if exists
                $this->removeFile(DIR_LOGS.'/'. LOGS_FILE);

                //Create and verify Log error without exception
                $log = new Logger('connection');
                $log->pushHandler(new StreamHandler(DIR_LOGS.'/'. LOGS_FILE, Logger::DEBUG));
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_INVALID_PASS_BD);
                $this->obj->setPsrLogObject($log);
                $this->obj->__construct(1,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_INVALID_PASS_BD);
                $this->assertEquals($this->readFileLine(DIR_LOGS.'/'. LOGS_FILE,$lineLogError,$errorNumber),true);

                //Create a exception
                $this->obj->setGenerateException(true);
                $this->obj->__construct(1,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_INVALID_PASS_BD);

            }

            /**
             * Test the __construct method errors type missmatch
             * Param $dbName
             * @depends test__ConstructErrorTypeMissmatchParamDbHost
             * @expectedException Exception
             * @expectedExceptionCode 00006
             */
            public function test__ConstructErrorTypeMissmatchParamDbName(){

                $lineLogError = 3;
                $errorNumber = "00006";

                //Create and verify Log error without exception
                $log = new Logger('connection');
                $log->pushHandler(new StreamHandler(DIR_LOGS.'/'. LOGS_FILE, Logger::DEBUG));
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_INVALID_PASS_BD);
                $this->obj->setPsrLogObject($log);
                $this->obj->__construct(TEST_VALID_HOST_BD,1,TEST_VALID_USER_BD,TEST_INVALID_PASS_BD);
                $this->assertEquals($this->readFileLine(DIR_LOGS.'/'. LOGS_FILE,$lineLogError,$errorNumber),true);

                //Create a exception
                $this->obj->setGenerateException(true);
                $this->obj->__construct(TEST_VALID_HOST_BD,1,TEST_VALID_USER_BD,TEST_INVALID_PASS_BD);

            }

            /**
             * Test the __construct method errors type missmatch
             * Param $dbUser
             * @depends test__ConstructErrorTypeMissmatchParamDbName
             * @expectedException Exception
             * @expectedExceptionCode 00006
             */
            public function test__ConstructErrorTypeMissmatchParamDbUser(){

                $lineLogError = 5;
                $errorNumber = "00006";

                //Create and verify Log error without exception
                $log = new Logger('connection');
                $log->pushHandler(new StreamHandler(DIR_LOGS.'/'. LOGS_FILE, Logger::DEBUG));
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_INVALID_PASS_BD);
                $this->obj->setPsrLogObject($log);
                $this->obj->__construct(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,1,TEST_INVALID_PASS_BD);
                $this->assertEquals($this->readFileLine(DIR_LOGS.'/'. LOGS_FILE,$lineLogError,$errorNumber),true);

                //Create a exception
                $this->obj->setGenerateException(true);
                $this->obj->__construct(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,1,TEST_INVALID_PASS_BD);

            }

            /**
             * Test the __construct method errors type missmatch
             * Param $dbPass
             * @depends test__ConstructErrorTypeMissmatchParamDbUser
             * @expectedException Exception
             * @expectedExceptionCode 00006
             */
            public function test__ConstructErrorTypeMissmatchParamDbPass(){

                $lineLogError = 7;
                $errorNumber = "00006";

                //Create and verify Log error without exception
                $log = new Logger('connection');
                $log->pushHandler(new StreamHandler(DIR_LOGS.'/'. LOGS_FILE, Logger::DEBUG));
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_INVALID_PASS_BD);
                $this->obj->setPsrLogObject($log);
                $this->obj->__construct(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_INVALID_USER_BD,1);
                $this->assertEquals($this->readFileLine(DIR_LOGS.'/'. LOGS_FILE,$lineLogError,$errorNumber),true);

                //Create a exception
                $this->obj->setGenerateException(true);
                $this->obj->__construct(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_INVALID_USER_BD,1);

            }

        //setGenerateException method

            /**
             * Test the setGenerateException method errors type missmatch
             * Param $generateException
             * @depends test__ConstructErrorTypeMissmatchParamDbPass
             * @expectedException Exception
             * @expectedExceptionCode 00006
             */
            public function testSetGenerateExceptionErrorTypeMissmatchParamGenerateException(){

                $lineLogError = 9;
                $errorNumber = "00006";

                //Create and verify Log error without exception
                $log = new Logger('connection');
                $log->pushHandler(new StreamHandler(DIR_LOGS.'/'. LOGS_FILE, Logger::DEBUG));
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_INVALID_PASS_BD);
                $this->obj->setPsrLogObject($log);
                $this->obj->setGenerateException(1);
                $this->assertEquals($this->readFileLine(DIR_LOGS.'/'. LOGS_FILE,$lineLogError,$errorNumber),true);

                //Create a exception
                $this->obj->setGenerateException(true);
                $this->obj->setGenerateException(1);

            }

        //setPsrLogObject method

            /**
             * Test the setPsrLogObject method errors type missmatch
             * Param $psrLogObject
             * @depends testSetGenerateExceptionErrorTypeMissmatchParamGenerateException
             * @expectedException Exception
             * @expectedExceptionCode 00006
             */
            public function testSetPsrLogObjectErrorTypeMissmatchParamPsrLogObject(){

                $lineLogError = 11;
                $errorNumber = "00006";

                //Create and verify Log error without exception
                $log = new Logger('connection');
                $log->pushHandler(new StreamHandler(DIR_LOGS.'/'. LOGS_FILE, Logger::DEBUG));
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_INVALID_PASS_BD);
                $this->obj->setPsrLogObject($log);
                $this->obj->setPsrLogObject(1);
                $this->assertEquals($this->readFileLine(DIR_LOGS.'/'. LOGS_FILE,$lineLogError,$errorNumber),true);

                //Create a exception
                $this->obj->setGenerateException(true);
                $this->obj->setPsrLogObject(1);

            }

        //getEscapeString method

            /**
             * Test the getEscapeString method errors type missmatch
             * Param $string
             * @depends testSetPsrLogObjectErrorTypeMissmatchParamPsrLogObject
             * @expectedException Exception
             * @expectedExceptionCode 00006
             */
            public function testGetEscapeStringErrorTypeMissmatchParamString(){

                $lineLogError = 13;
                $errorNumber = "00006";

                //Create and verify Log error without exception
                $log = new Logger('connection');
                $log->pushHandler(new StreamHandler(DIR_LOGS.'/'. LOGS_FILE, Logger::DEBUG));
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
                $this->obj->setPsrLogObject($log);
                $this->obj->getEscapeString(1);
                $this->assertEquals($this->readFileLine(DIR_LOGS.'/'. LOGS_FILE,$lineLogError,$errorNumber),true);

                //Create a exception
                $this->obj->setGenerateException(true);
                $this->obj->getEscapeString(1);

            }

         //setConnection method

            /**
             * Test the setConnection method errors type missmatch
             * Param $dbHost
             * @depends testGetEscapeStringErrorTypeMissmatchParamString
             * @expectedException Exception
             * @expectedExceptionCode 00006
             */
            public function testSetConnectionErrorTypeMissmatchParamDbHost(){

                $lineLogError = 15;
                $errorNumber = "00006";


                //Create and verify Log error without exception
                $log = new Logger('connection');
                $log->pushHandler(new StreamHandler(DIR_LOGS.'/'. LOGS_FILE, Logger::DEBUG));
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_INVALID_PASS_BD);
                $this->obj->setPsrLogObject($log);
                $this->obj->setConnection(1,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_INVALID_PASS_BD);
                $this->assertEquals($this->readFileLine(DIR_LOGS.'/'. LOGS_FILE,$lineLogError,$errorNumber),true);

                //Create a exception
                $this->obj->setGenerateException(true);
                $this->obj->setConnection(1,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_INVALID_PASS_BD);

            }

            /**
             * Test the setConnection method errors type missmatch
             * Param $dbName
             * @depends testSetConnectionErrorTypeMissmatchParamDbHost
             * @expectedException Exception
             * @expectedExceptionCode 00006
             */
            public function testSetConnectionErrorTypeMissmatchParamDbName(){

                $lineLogError = 17;
                $errorNumber = "00006";

                //Create and verify Log error without exception
                $log = new Logger('connection');
                $log->pushHandler(new StreamHandler(DIR_LOGS.'/'. LOGS_FILE, Logger::DEBUG));
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_INVALID_PASS_BD);
                $this->obj->setPsrLogObject($log);
                $this->obj->setConnection(TEST_VALID_HOST_BD,1,TEST_VALID_USER_BD,TEST_INVALID_PASS_BD);
                $this->assertEquals($this->readFileLine(DIR_LOGS.'/'. LOGS_FILE,$lineLogError,$errorNumber),true);

                //Create a exception
                $this->obj->setGenerateException(true);
                $this->obj->setConnection(TEST_VALID_HOST_BD,1,TEST_VALID_USER_BD,TEST_INVALID_PASS_BD);

            }

            /**
             * Test the setConnection method errors type missmatch
             * Param $dbUser
             * @depends testSetConnectionErrorTypeMissmatchParamDbName
             * @expectedException Exception
             * @expectedExceptionCode 00006
             */
            public function testSetConnectionErrorTypeMissmatchParamDbUser(){

                $lineLogError = 19;
                $errorNumber = "00006";

                //Create and verify Log error without exception
                $log = new Logger('connection');
                $log->pushHandler(new StreamHandler(DIR_LOGS.'/'. LOGS_FILE, Logger::DEBUG));
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_INVALID_PASS_BD);
                $this->obj->setPsrLogObject($log);
                $this->obj->setConnection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,1,TEST_INVALID_PASS_BD);
                $this->assertEquals($this->readFileLine(DIR_LOGS.'/'. LOGS_FILE,$lineLogError,$errorNumber),true);

                //Create a exception
                $this->obj->setGenerateException(true);
                $this->obj->setConnection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,1,TEST_INVALID_PASS_BD);

            }

            /**
             * Test the setConnection method errors type missmatch
             * Param $dbPass
             * @depends testSetConnectionErrorTypeMissmatchParamDbUser
             * @expectedException Exception
             * @expectedExceptionCode 00006
             */
            public function testSetConnectionErrorTypeMissmatchParamDbPass(){

                $lineLogError = 21;
                $errorNumber = "00006";

                //Create and verify Log error without exception
                $log = new Logger('connection');
                $log->pushHandler(new StreamHandler(DIR_LOGS.'/'. LOGS_FILE, Logger::DEBUG));
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_INVALID_PASS_BD);
                $this->obj->setPsrLogObject($log);
                $this->obj->setConnection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_INVALID_USER_BD,1);
                $this->assertEquals($this->readFileLine(DIR_LOGS.'/'. LOGS_FILE,$lineLogError,$errorNumber),true);

                //Create a exception
                $this->obj->setGenerateException(true);
                $this->obj->setConnection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_INVALID_USER_BD,1);

            }

        //createPreparedStatement method

            /**
             * Test the createPreparedStatement method errors type missmatch
             * Param $query
             * @depends testSetConnectionErrorTypeMissmatchParamDbPass
             * @expectedException Exception
             * @expectedExceptionCode 00006
             */
            public function testcreatePreparedStatementErrorTypeMissmatchParamQuery(){

                $lineLogError = 23;
                $errorNumber = "00006";

                //Create and verify Log error without exception
                $log = new Logger('connection');
                $log->pushHandler(new StreamHandler(DIR_LOGS.'/'. LOGS_FILE, Logger::DEBUG));
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
                $this->obj->setPsrLogObject($log);
                $this->obj->createPreparedStatement(1,"s",array());
                $this->assertEquals($this->readFileLine(DIR_LOGS.'/'. LOGS_FILE,$lineLogError,$errorNumber),true);

                //Create a exception
                $this->obj->setGenerateException(true);
                $this->obj->createPreparedStatement(1,"s",array());

            }

            /**
             * Test the createPreparedStatement method errors type missmatch
             * Param $typesString
             * @depends testcreatePreparedStatementErrorTypeMissmatchParamQuery
             * @expectedException Exception
             * @expectedExceptionCode 00006
             */
            public function testcreatePreparedStatementErrorTypeMissmatchParamTypesString(){

                $lineLogError = 25;
                $errorNumber = "00006";

                //Create and verify Log error without exception
                $log = new Logger('connection');
                $log->pushHandler(new StreamHandler(DIR_LOGS.'/'. LOGS_FILE, Logger::DEBUG));
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
                $this->obj->setPsrLogObject($log);
                $this->obj->createPreparedStatement("",1,array());
                $this->assertEquals($this->readFileLine(DIR_LOGS.'/'. LOGS_FILE,$lineLogError,$errorNumber),true);

                //Create a exception
                $this->obj->setGenerateException(true);
                $this->obj->createPreparedStatement("",1,array());

            }

            /**
             * Test the createPreparedStatement method errors type missmatch
             * Param $valuesArray
             * @depends testcreatePreparedStatementErrorTypeMissmatchParamTypesString
             * @expectedException Exception
             */
            public function testcreatePreparedStatementErrorTypeMissmatchParamValuesArray(){

                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
                $this->obj->createPreparedStatement("","",1);

            }

            /**
             * Test the createPreparedStatement method errors sequence methods
             * This method can't be used with use start method in past
             * @depends testcreatePreparedStatementErrorTypeMissmatchParamValuesArray
             * @expectedException Exception
             * @expectedExceptionCode 00007
             */
            public function testcreatePreparedStatementErrorSequence(){

                $lineLogError = 27;
                $errorNumber = "00007";

                //Create and verify Log error without exception
                $log = new Logger('connection');
                $log->pushHandler(new StreamHandler(DIR_LOGS.'/'. LOGS_FILE, Logger::DEBUG));
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
                $this->obj->setPsrLogObject($log);
                $this->obj->createPreparedStatement("","",array());
                $this->assertEquals($this->readFileLine(DIR_LOGS.'/'. LOGS_FILE,$lineLogError,$errorNumber),true);

                //Create a exception
                $this->obj->setGenerateException(true);
                $this->obj->createPreparedStatement("","",array());

            }

            /**
             * Test the createPreparedStatement method errors a invalid query
             * @depends testcreatePreparedStatementErrorSequence
             * @expectedException Exception
             */
            public function testcreatePreparedStatementErrorInvalidQUery(){

                $lineLogError = 29;
                $errorMessage = "Wrong SQL";

                //Create and verify Log error without exception
                $log = new Logger('connection');
                $log->pushHandler(new StreamHandler(DIR_LOGS.'/'. LOGS_FILE, Logger::DEBUG));
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
                $this->obj->setPsrLogObject($log);
                $this->obj->start();
                $this->obj->createPreparedStatement("SELECT * FRO","",array());
                $this->assertEquals($this->readFileLine(DIR_LOGS.'/'. LOGS_FILE,$lineLogError,$errorMessage),true);

                //Create a exception
                $this->obj->setGenerateException(true);
                $this->obj->start();
                $this->obj->createPreparedStatement("SELECT * FRO","",array());

            }


        //Start method


            /**
             * Test the start method errors a invalid connection
             * @depends testcreatePreparedStatementErrorInvalidQUery
             * @expectedException Exception
             */
            public function testStartErrorInvalidConnection(){

                $lineLogError = 31;
                $errorMessage = "Class Connection -- Method start -- ";

                //Create and verify Log error without exception
                $log = new Logger('connection');
                $log->pushHandler(new StreamHandler(DIR_LOGS.'/'. LOGS_FILE, Logger::DEBUG));
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_INVALID_PASS_BD);
                $this->obj->setPsrLogObject($log);
                $this->obj->start();
                $this->assertEquals($this->readFileLine(DIR_LOGS.'/'. LOGS_FILE,$lineLogError,$errorMessage),true);

                //Create a exception
                $this->obj->setGenerateException(true);
                $this->obj->start();

            }

        //Stop method

            /**
             * Test the stop method errors sequence methods
             * This method can't be used with use start method in past
             * @depends testStartErrorInvalidConnection
             * @expectedException Exception
             * @expectedExceptionCode 00001
             */
            public function testStopErrorSequence(){

                $lineLogError = 33;
                $errorNumber = "00001";

                //Create and verify Log error without exception
                $log = new Logger('connection');
                $log->pushHandler(new StreamHandler(DIR_LOGS.'/'. LOGS_FILE, Logger::DEBUG));
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
                $this->obj->setPsrLogObject($log);
                $this->obj->stop();
                $this->assertEquals($this->readFileLine(DIR_LOGS.'/'. LOGS_FILE,$lineLogError,$errorNumber),true);

                //Create a exception
                $this->obj->setGenerateException(true);
                $this->obj->stop();

            }

        //executeQuery method

            /**
             * Test the executeQuery method errors type missmatch
             * Param $sql
             * @depends testStopErrorSequence
             * @expectedException Exception
             * @expectedExceptionCode 00006
             */
            public function testExecuteQueryErrorTypeMissmatchParamSql(){

                $lineLogError = 35;
                $errorNumber = "00006";

                //Create and verify Log error without exception
                $log = new Logger('connection');
                $log->pushHandler(new StreamHandler(DIR_LOGS.'/'. LOGS_FILE, Logger::DEBUG));
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
                $this->obj->setPsrLogObject($log);
                $this->obj->executeQuery(1);
                $this->assertEquals($this->readFileLine(DIR_LOGS.'/'. LOGS_FILE,$lineLogError,$errorNumber),true);

                //Create a exception
                $this->obj->setGenerateException(true);
                $this->obj->executeQuery(1);

            }

            /**
             * Test the executeQuery method sequence
             * Can't use this method if you didn't use start in past
             * @depends testExecuteQueryErrorTypeMissmatchParamSql
             * @expectedException Exception
             * @expectedExceptionCode 00002
             */
            public function testExecuteQueryErrorSequence(){

                $lineLogError = 37;
                $errorNumber = "00002";

                //Create and verify Log error without exception
                $log = new Logger('connection');
                $log->pushHandler(new StreamHandler(DIR_LOGS.'/'. LOGS_FILE, Logger::DEBUG));
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
                $this->obj->setPsrLogObject($log);
                $this->obj->executeQuery("");
                $this->assertEquals($this->readFileLine(DIR_LOGS.'/'. LOGS_FILE,$lineLogError,$errorNumber),true);

                //Create a exception
                $this->obj->setGenerateException(true);
                $this->obj->executeQuery("");

            }

            /**
             * Test the executeQuery invalid query
             * @depends testExecuteQueryErrorSequence
             * @expectedException Exception
             */
            public function testExecuteQueryErrorInvalidQuery(){

                $lineLogError = 39;
                $errorMessage = "Class Connection -- Method executeQuery -- ";

                //Create and verify Log error without exception
                $log = new Logger('connection');
                $log->pushHandler(new StreamHandler(DIR_LOGS.'/'. LOGS_FILE, Logger::DEBUG));
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
                $this->obj->setPsrLogObject($log);
                $this->obj->start();
                $this->obj->executeQuery("SELECT * FROM xxxx");
                $this->assertEquals($this->readFileLine(DIR_LOGS.'/'. LOGS_FILE,$lineLogError,$errorMessage),true);

                //Create a exception
                $this->obj->setGenerateException(true);
                $this->obj->start();
                $this->obj->executeQuery("SELECT * FROM xxxx");

            }

        //getResultSetFetchArrayObjects method

            /**
             * Test the getResultSetFetchArrayObjects method sequence
             * Can't use this method if you didn't use executeQuery in past
             * @depends testExecuteQueryErrorInvalidQuery
             * @expectedException Exception
             * @expectedExceptionCode 00003
             */
            public function testGetResultSetFetchArrayObjectsErrorSequence(){

                $lineLogError = 41;
                $errorNumber = "00003";

                //Create and verify Log error without exception
                $log = new Logger('connection');
                $log->pushHandler(new StreamHandler(DIR_LOGS.'/'. LOGS_FILE, Logger::DEBUG));
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
                $this->obj->setPsrLogObject($log);
                $this->obj->getResultSetFetchArrayObjects();
                $this->assertEquals($this->readFileLine(DIR_LOGS.'/'. LOGS_FILE,$lineLogError,$errorNumber),true);

                //Create a exception
                $this->obj->setGenerateException(true);
                $this->obj->getResultSetFetchArrayObjects();

            }

    //Internal functions

    private function readFileLine($urlFile,$lineNumber,$errorNumber){

        $file = fopen($urlFile,"r");
        $readLine = "";

        for ($i = 1; $i <= (int)$lineNumber; $i++  ){
            $readLine = fgets($file);
        }
        fclose($file);

        //Check for information
        $pos = strpos($readLine,(string)$errorNumber);
        if ($pos === false) return false;
        return true;

    }

    private function removeFile($urlFile){
        //Remove Test Log File
        if(file_exists($urlFile)){
            unlink($urlFile);
        }
    }

	protected function tearDown() {
		unset($this->obj);
	}
}
?>