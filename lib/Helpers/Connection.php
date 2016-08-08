<?php

    namespace Toledo\Helpers;
    use mysqli, Exception; //Necessary: by default, PHP will try to load classes from your current namespace

    /**
     * Class Connection
     * Use to start, stop and execute a query in MySql Database.
     * More information in: https://github.com/ThiagoToledoPHP/Connection
     * @author Thiago Toledo <javaephp@gmail.com>
     */
	class Connection {

		private $dbHost;
        private $dbName;
        private $dbUser;
        private $dbPass;

		private $mysqliObject; //Mysql Object
        private $isConnected; //True if the connection start
        private $errorMsg;   //Save the error msg to create silent Logs, for example
        private $errorCode; //Save the error code
        private $resultSet; //Query's  ResultSet

        private $generateException; //If you prefer, the class can create one Exception to you use Try/Catch

        /**
         * Connection constructor.
         * @param string $dbHost
         * @param string $dbName
         * @param string $dbUser
         * @param string $dbPass
         */
		public function __construct($dbHost, $dbName, $dbUser, $dbPass ){
		    $this->setConnection($dbHost, $dbName, $dbUser, $dbPass);
		}

        /**
         * Return a error message, if exists.
         * @return string
         */
        public function getErrorMsg(){
            return $this->errorMsg;
        }

        /*
         * Set if the class generate or not exceptions
         * @param boolean $generateException
         */
        public function setGenerateException($generateException){
            $this->generateException = $generateException;
        }

        /**
         * Return a error number, if exists.
         * @return string
         */
        public function getErrorCode(){
            return $this->errorCode;
        }

        /**
         * Return a true, if connected
         * @return boolean
         */
        public function isConnected(){
            return $this->isConnected;
        }

        /**
         * Return a escape string with mysqli escapes tring
         * @return string
         */
        public function getEscapeString($string){
            return $this->mysqliObject->real_escape_string($string);
        }


        /**
         * Set the connection, if you need
         * @param string $dbHost
         * @param string $dbName
         * @param string $dbUser
         * @param string $dbPass
         */
		public function setConnection($dbHost, $dbName, $dbUser, $dbPass){

		    $this->dbHost = $dbHost;
            $this->dbUser = $dbUser;
            $this->dbPass = $dbPass;
            $this->dbName = $dbName;

            $this->generateException = false; //Default
            $this->errorMsg = "";
            $this->errorCode = "";

            $this->mysqliObject = null; //Null the local mysqliObject to begin
            $this->isConnected = false;
            $this->resultSet = null;

        }

        /**
         * Create a custom error. Can create one exception, print and stop application
         * @param string $errorMsg
         * @param string $errorCode
         * @throws Exception
         */
        private function createCustomError($errorMsg, $errorCode){

            $this->errorMsg = $errorMsg;
            $this->errorCode = $errorCode;

            if($this->generateException === true){

                throw new Exception($errorMsg, $errorCode);

            }

        }

        /**
         * Start the Database connection
         * @return bool
         */
		public function start(){

		    //Only used @ to total control the report of application
		    $mysqli = @new mysqli($this->dbHost,$this->dbUser,$this->dbPass,$this->dbName);

            /*
             *  $connect_error was broken until PHP 5.2.9 and 5.3.0.
            */
            if ($mysqli->connect_error) {

                $this->isConnected = false;
                $this->createCustomError(utf8_encode('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error),$mysqli->connect_errno);

                return $this->isConnected;

            }

            /*
             * Use this instead of $connect_error if you need to ensure
             * compatibility with PHP versions prior to 5.2.9 and 5.3.0.
             */
            if (mysqli_connect_error()) {

                $this->isConnected = false;
                $this->createCustomError(utf8_encode('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error()), mysqli_connect_errno());

                return $this->isConnected;

            }

            $this->mysqliObject = $mysqli;
            $this->isConnected = true;
            return $this->isConnected;

        }

        /**
         * Stop the Database connection. Return true only if sucess stop the currently connection.
         * @return bool
         */
        public function stop(){

            if($this->isConnected === false){

                //All the number error of this class user 4 zeros in begin
                $this->createCustomError("Connection::stop error: No is possible close a connection if you no begin using Connection:start in sequence.", "00001");

                return false;

            }else {
                $this->mysqliObject->close();
                $this->isConnected = false;
            }

            return true;

        }

        /**
         * Execute a query in Database. Return true in sucess
         * @param $sql
         * @return bool
         */
        public function executeQuery($sql){

            if($this->isConnected === false){

                //All the number error of this class user 4 zeros in begin
                $this->createCustomError("Connection::executeQuery error: No is possible execute a query if you no begin using Connection:start in sequence.", "00002");

                return false;

            }


            $this->resultSet = $this->mysqliObject->query($sql);

            if($this->resultSet === false){

                $this->createCustomError(utf8_encode('Connect Error (' . $this->mysqliObject->errno . ') ' . $this->mysqliObject->error),$this->mysqliObject->errno);

                return false;

            }



            return true;

        }


        /**
         * Use only after executeQuery with 'SELECT SQLs', create a array of objects, or false in error
         * @return array|bool
         */
        public function getResultSetFetchArrayObjects(){

            $arrayFetchReturn = array();

            if(is_null($this->resultSet)) {

                //All the number error of this class user 4 zeros in begin
                $this->createCustomError("Connection::getResultSetFetchObject error: No is possible fetch a query if you no begin using Connection:executeQuery to 'SELECT query' in sequence.", "00003");

                return false; //Execute only if the execution not stop
            }

            while($obj = $this->resultSet->fetch_object()){
                $arrayFetchReturn[] = $obj;
            }

            return $arrayFetchReturn;

        }


	 }


?>