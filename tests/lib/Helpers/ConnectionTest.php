<?php

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

	    $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
        $this->obj->start();

        $sql = "
        CREATE TABLE IF NOT EXISTS client
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
        INSERT INTO client (id, fullName, email, dateOfBirth, numberOfChildren)
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
             */
            public function testExecuteQueryValidExceptionInactive(){
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
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
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
                $this->obj->setGenerateException(true);
                $this->obj->start();
                $this->obj->executeQuery("Select from error_table");
                $this->obj->stop();
            }

            /**
             *  Test the method using valid information for database and valid query
             */
            public function testExecuteQueryValidExceptionActive(){
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
                $this->obj->setGenerateException(true);
                $this->obj->start();
                $this->assertEquals($this->obj->executeQuery("Select * from client"),true);
                $this->obj->stop();
            }

            /**
             * Test the methods using preparedStatement method and one Statement
             */
            public function testExecutePreparedStatement(){
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
                $this->obj->setGenerateException(true);
                $this->obj->start();
                $this->obj->createPreparedStatement("SELECT * FROM client WHERE email = ?","s",array("javaephp@gmail.com"));
                $this->obj->executeQuery();
                $this->assertEquals(sizeof($this->obj->getResultSetFetchArrayObjects()),1);
                $this->obj->stop();
            }


            /**
             * Test the methods using preparedStatement method and two Statements
             */
            public function testExecutePreparedStatementTwo(){
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
                $this->obj->setGenerateException(true);
                $this->obj->start();
                $this->obj->createPreparedStatement("SELECT * FROM client WHERE email = ? AND fullName = ? ","ss",array("javaephp@gmail.com","Thiago Toledo"));
                $this->obj->executeQuery();
                $this->assertEquals(sizeof($this->obj->getResultSetFetchArrayObjects()),1);
                $this->obj->stop();
            }

            /**
             * Test the methods using preparedStatement method and two Statements, but using wrong sql
             * @expectedException Exception
             */
            public function testExecutePreparedStatementThree(){
                $this->obj = new Toledo\Helpers\Connection(TEST_VALID_HOST_BD,TEST_VALID_NAME_BD,TEST_VALID_USER_BD,TEST_VALID_PASS_BD);
                $this->obj->setGenerateException(true);
                $this->obj->start();
                $this->obj->createPreparedStatement("SELECT * FROM client WHERE email = ? AND fullNam = ? ","ss",array("javaephp@gmail.com","Thiago Toledo"));
            }

	protected function tearDown() {
		unset($this->obj);
	}
}
?>