<?php

@include_once("lib\Helpers\Connection.php");
include_once("tests\ConfigTest.php");

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

	    $this->obj = new Toledo\Helpers\Connection(VALID_HOST_BD,VALID_NAME_BD,VALID_USER_BD,VALID_PASS_BD);
        $this->obj->start();

        $sql = "
        CREATE TABLE IF NOT EXISTS client
            (
                id INTEGER (11) NOT NULL AUTO_INCREMENT , 
                nome varchar (255),
                PRIMARY KEY (id)
            )
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
                $this->obj = new Toledo\Helpers\Connection(INVALID_HOST_BD,INVALID_NAME_BD,INVALID_USER_BD,INVALID_PASS_BD);
                $this->assertEquals($this->obj->start(),false);
            }

            /**
             * Test the method using valid information for database
             */
            public function testConnectionStartValidExceptionInactive(){
                $this->obj = new Toledo\Helpers\Connection(VALID_HOST_BD,VALID_NAME_BD,VALID_USER_BD,VALID_PASS_BD);
                $this->assertEquals($this->obj->start(),true);
            }

        //ExceptionActive tests

            /**
             * Test the method using invalid information for database and exception active
             * @expectedException Exception
             */
            public function testConnectionStartErrorExceptionActive(){
                $this->obj = new Toledo\Helpers\Connection(INVALID_HOST_BD,INVALID_NAME_BD,INVALID_USER_BD,INVALID_PASS_BD);
                $this->obj->setGenerateException(true);
                $this->obj->start();
            }


            /**
             * Test the method using valid information for database
             */
            public function testConnectionStartValidExceptionActive(){
                $this->obj = new Toledo\Helpers\Connection(VALID_HOST_BD,VALID_NAME_BD,VALID_USER_BD,VALID_PASS_BD);
                $this->obj->setGenerateException(true);
                $this->assertEquals($this->obj->start(),true);
            }


    //stop method

        //ExceptionInactive tests

            /**
             * Test the method using invalid information for database
             */
            public function testConnectionStopErrorExceptionInactive(){
                $this->obj = new Toledo\Helpers\Connection(INVALID_HOST_BD,INVALID_NAME_BD,INVALID_USER_BD,INVALID_PASS_BD);
                $this->assertEquals($this->obj->stop(),false);
            }

            /**
             * Test the method using valid information for database
             */
            public function testConnectionStopValidExceptionInactive(){
                $this->obj = new Toledo\Helpers\Connection(VALID_HOST_BD,VALID_NAME_BD,VALID_USER_BD,VALID_PASS_BD);
                $this->obj->start();
                $this->assertEquals($this->obj->stop(),true);
            }

        //ExceptionActive tests

            /**
             * Test the method using invalid information for database
             * @expectedException Exception
             */
            public function testConnectionStopErrorExceptionActive(){
                $this->obj = new Toledo\Helpers\Connection(INVALID_HOST_BD,INVALID_NAME_BD,INVALID_USER_BD,INVALID_PASS_BD);
                $this->obj->setGenerateException(true);
                $this->obj->stop();
            }

            /**
             * Test the method using valid information for database
             */
            public function testConnectionStopValidExceptionActive(){
                $this->obj = new Toledo\Helpers\Connection(VALID_HOST_BD,VALID_NAME_BD,VALID_USER_BD,VALID_PASS_BD);
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
                $this->obj = new Toledo\Helpers\Connection(VALID_HOST_BD,VALID_NAME_BD,VALID_USER_BD,VALID_PASS_BD);
                $this->obj->start();
                $this->assertEquals($this->obj->executeQuery("Select from error_table"),false);
                $this->obj->stop();
            }

            /**
             * Test the method using valid information for database and valid query
             */
            public function testExecuteQueryValidExceptionInactive(){
                $this->obj = new Toledo\Helpers\Connection(VALID_HOST_BD,VALID_NAME_BD,VALID_USER_BD,VALID_PASS_BD);
                $this->obj->start();
                $this->assertEquals($this->obj->executeQuery("Select * from client"),true);
                $this->obj->stop();
            }

        //ExceptionActive tests

            /**
             * Test the method using valid information for database and no valid query
             * @expectedException Exception
             */
            public function testExecuteQueryErrorExceptionActive(){
                $this->obj = new Toledo\Helpers\Connection(VALID_HOST_BD,VALID_NAME_BD,VALID_USER_BD,VALID_PASS_BD);
                $this->obj->setGenerateException(true);
                $this->obj->start();
                $this->obj->executeQuery("Select from error_table");
                $this->obj->stop();
            }

            /**
             *  Test the method using valid information for database and valid query
             */
            public function testExecuteQueryValidExceptionActive(){
                $this->obj = new Toledo\Helpers\Connection(VALID_HOST_BD,VALID_NAME_BD,VALID_USER_BD,VALID_PASS_BD);
                $this->obj->setGenerateException(true);
                $this->obj->start();
                $this->assertEquals($this->obj->executeQuery("Select * from client"),true);
                $this->obj->stop();
            }

	protected function tearDown() {
		unset($this->obj);
	}
}
?>