<?php

include_once("lib\Helpers\Connection.php");
include_once("tests\ConfigTest.php");


class ConnectionTest extends PHPUnit_Framework_TestCase {

	protected $obj = NULL;
	
	protected function setUp() {

	    $this->obj = new Toledo\Helpers\Connection(VALID_HOST_BD,VALID_NAME_BD,VALID_USER_BD,VALID_PASS_BD);
        $this->obj->start();

        //Create test table
        $sql = "
        CREATE TABLE 'client' 
            (
                'id' integer (11) NOT NULL AUTO_INCREMENT , 
                'nome' varchar (255),
                PRIMARY KEY ('id')
            )
        ";

        $this->obj->executeQuery($sql);
        $this->obj->stop();

	}


    //start method


        //ExceptionInactive tests

            public function testConnectionStartErrorExceptionInactive(){
                $this->obj = new Toledo\Helpers\Connection(INVALID_HOST_BD,INVALID_NAME_BD,INVALID_USER_BD,INVALID_PASS_BD);
                $this->assertEquals($this->obj->start(),false);
            }


            public function testConnectionStartValidExceptionInactive(){
                $this->obj = new Toledo\Helpers\Connection(VALID_HOST_BD,VALID_NAME_BD,VALID_USER_BD,VALID_PASS_BD);
                $this->assertEquals($this->obj->start(),true);
            }

        //ExceptionActive tests

            /**
             * @expectedException Exception
             */
            public function testConnectionStartErrorExceptionActive(){
                $this->obj = new Toledo\Helpers\Connection(INVALID_HOST_BD,INVALID_NAME_BD,INVALID_USER_BD,INVALID_PASS_BD,true);
                $this->obj->start();
            }


            public function testConnectionStartValidExceptionActive(){
                $this->obj = new Toledo\Helpers\Connection(VALID_HOST_BD,VALID_NAME_BD,VALID_USER_BD,VALID_PASS_BD,true);
                $this->assertEquals($this->obj->start(),true);
            }


    //stop method

        //ExceptionInactive tests

            public function testConnectionStopErrorExceptionInactive(){
                $this->obj = new Toledo\Helpers\Connection(INVALID_HOST_BD,INVALID_NAME_BD,INVALID_USER_BD,INVALID_PASS_BD);
                $this->assertEquals($this->obj->stop(),false);
            }

            public function testConnectionStopValidExceptionInactive(){
                $this->obj = new Toledo\Helpers\Connection(VALID_HOST_BD,VALID_NAME_BD,VALID_USER_BD,VALID_PASS_BD);
                $this->obj->start();
                $this->assertEquals($this->obj->stop(),true);
            }

        //ExceptionActive tests

            /**
             * @expectedException Exception
             */
            public function testConnectionStopErrorExceptionActive(){
                $this->obj = new Toledo\Helpers\Connection(INVALID_HOST_BD,INVALID_NAME_BD,INVALID_USER_BD,INVALID_PASS_BD,true);
                $this->obj->stop();
            }

            public function testConnectionStopValidExceptionActive(){
                $this->obj = new Toledo\Helpers\Connection(VALID_HOST_BD,VALID_NAME_BD,VALID_USER_BD,VALID_PASS_BD,true);
                $this->obj->start();
                $this->assertEquals($this->obj->stop(),true);
            }

    //executeQuery method

        //ExceptionInactive tests

            public function testExecuteQueryErrorExceptionInactive(){
                $this->obj = new Toledo\Helpers\Connection(VALID_HOST_BD,VALID_NAME_BD,VALID_USER_BD,VALID_PASS_BD);
                $this->obj->start();
                $this->assertEquals($this->obj->executeQuery("Select from error_table"),false);
                $this->obj->stop();
            }

            public function testExecuteQueryValidExceptionInactive(){
                $this->obj = new Toledo\Helpers\Connection(VALID_HOST_BD,VALID_NAME_BD,VALID_USER_BD,VALID_PASS_BD);
                $this->obj->start();
                $this->assertEquals($this->obj->executeQuery("Select * from client"),true);
                $this->obj->stop();
            }

        //ExceptionActive tests

            /**
             * @expectedException Exception
             */
            public function testExecuteQueryErrorExceptionActive(){
                $this->obj = new Toledo\Helpers\Connection(VALID_HOST_BD,VALID_NAME_BD,VALID_USER_BD,VALID_PASS_BD,true);
                $this->obj->start();
                $this->obj->executeQuery("Select from error_table");
                $this->obj->stop();
            }

            public function testExecuteQueryValidExceptionActive(){
                $this->obj = new Toledo\Helpers\Connection(VALID_HOST_BD,VALID_NAME_BD,VALID_USER_BD,VALID_PASS_BD,true);
                $this->obj->start();
                $this->assertEquals($this->obj->executeQuery("Select * from client"),true);
                $this->obj->stop();
            }

	protected function tearDown() {
		unset($this->obj);
	}
}
?>