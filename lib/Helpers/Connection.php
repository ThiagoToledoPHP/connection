<?php

    namespace Toledo\Helpers;
    use mysqli, Exception; //Necessary: by default, PHP will try to load classes from your current namespace

    /**
     * Class Connection
     * Use to start, stop and execute a query in MySql Database.
     * More information in:
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
        private $errorNumber; //Save the error number
        private $resultSet; //Query's  ResultSet

        private $printErrorMsg; //Print error for emergency debugs. For security of app, only show errors if this is true
        private $generateException; //If you prefer, the class can create one Exception to you use Try/Catch
        private $stopOnPrintErrorMsg; //Die the execution, if print error msg is true.

        /**
         * Connection constructor.
         * @param string $dbHost
         * @param string $dbName
         * @param string $dbUser
         * @param string $dbPass
         * @param bool $generateException Set true to create an Exception
         * @param bool $printErrorMsg If $generateException set false, create a friendly HTML print
         * @param bool $stopOnPrintErrorMsg If $generateException set false, and $printErrorMsg set true, create a friendly HTML print don't stop the app execution
         */
		public function __construct($dbHost, $dbName, $dbUser, $dbPass, $generateException = false, $printErrorMsg = false, $stopOnPrintErrorMsg = true ){
		    $this->setConnection($dbHost, $dbName, $dbUser, $dbPass, $generateException, $printErrorMsg, $stopOnPrintErrorMsg);
		}

        /**
         * Return a error message, if exists.
         * @return string
         */
        public function getErrorMsg(){
            return $this->errorMsg;
        }

        /**
         * Return a error number, if exists.
         * @return string
         */
        public function getErrorNumber(){
            return $this->errorNumber;
        }

        /**
         * Return a true, if connected
         * @return boolean
         */
        public function getIsConnected(){
            return $this->isConnected;
        }


        /**
         * Set the connection, if you need
         * @param string $dbHost
         * @param string $dbName
         * @param string $dbUser
         * @param string $dbPass
         * @param bool $generateException Set true to create an Exception
         * @param bool $printErrorMsg If $generateException set false, create a friendly HTML print
         * @param bool $stopOnPrintErrorMsg If $generateException set false, and $printErrorMsg set true, create a friendly HTML print don't stop the app execution
         */
		public function setConnection($dbHost, $dbName, $dbUser, $dbPass, $generateException = false, $printErrorMsg = false, $stopOnPrintErrorMsg = true){

		    $this->dbHost = $dbHost;
            $this->dbUser = $dbUser;
            $this->dbPass = $dbPass;
            $this->dbName = $dbName;

            $this->generateException = $generateException;
            $this->printErrorMsg = $printErrorMsg;
            $this->stopOnPrintErrorMsg = $stopOnPrintErrorMsg;
            $this->errorMsg = "";
            $this->errorNumber = "";

            $this->mysqliObject = null; //Null the local mysqliObject to begin
            $this->isConnected = false;
            $this->resultSet = null;

        }

        /**
         * Create a custom error. Can create one exception, print and stop application
         * @param string $errorMsg
         * @param string $errorNumber
         * @param bool $generateException Set true to create an Exception
         * @param bool $printErrorMsg If $generateException set false, create a friendly HTML print
         * @param bool $stopOnPrintErrorMsg If $generateException set false, and $printErrorMsg set true, create a friendly HTML print don't stop the app execution
         * @throws Exception
         */
        private function createCustomError($errorMsg, $errorNumber, $generateException = false, $printErrorMsg = false, $stopOnPrintErrorMsg = true){

            $returnString = "";

            if($generateException === true){

                throw new Exception($errorMsg, $errorNumber);

            }elseif ($printErrorMsg === true){


                //I don't like use HTML internal in PHP code, but, to no need other files, temporary use in this way
                $returnString .=  "<div class='connectionWarningBlock'>";

                    $returnString .=  "<p>";

                        $returnString .=  "<b>Error number:</b> ".$errorNumber."<br>";
                        $returnString .=  "<b>Error message:</b> ".$errorMsg."";

                    $returnString .=  "</p>";

                    $debug_backtrace = debug_backtrace();

                    if(is_array($debug_backtrace)){

                        $debug_backtrace = array_reverse($debug_backtrace);

                        $returnString .=  "<ul>";

                        foreach ($debug_backtrace as $backtrace_element){

                            $returnString .=
                                "<li>" .
                                    "<b>File: </b>"     . $backtrace_element["file"]        . "<br>".
                                    "<b>Line: </b>"     . $backtrace_element["line"]        . "<br>".
                                    "<b>Function: </b>" . $backtrace_element["function"]    . "<br>".
                                    "<b>Class: </b>"    . $backtrace_element["class"]       . "<br>".
                                "</li>";
                        }

                        $returnString .=  "</ul>";

                    }

                $returnString .= "</div>";

                echo $returnString;

                if($stopOnPrintErrorMsg === true){
                    die();
                }

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
                $this->errorMsg = utf8_encode('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
                $this->errorNumber = $mysqli->connect_errno;
                $this->createCustomError($this->errorMsg,$this->errorNumber,$this->generateException,$this->printErrorMsg,$this->stopOnPrintErrorMsg);

                return $this->isConnected; //Execute only if the execution not stop

            }

            /*
             * Use this instead of $connect_error if you need to ensure
             * compatibility with PHP versions prior to 5.2.9 and 5.3.0.
             */
            if (mysqli_connect_error()) {

                $this->isConnected = false;
                $this->errorMsg = utf8_encode('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
                $this->errorNumber = mysqli_connect_errno();
                $this->createCustomError($this->errorMsg, $this->errorNumber,$this->generateException,$this->printErrorMsg,$this->stopOnPrintErrorMsg);

                return $this->isConnected; //Execute only if the execution not stop

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

                $this->errorNumber = "00001"; //All the number error of this class user 4 zeros in begin
                $this->errorMsg = "Connection::stop error: No is possible close a connection if you no begin using Connection:start in sequence.";
                $this->createCustomError($this->errorMsg, $this->errorNumber,$this->generateException,$this->printErrorMsg,$this->stopOnPrintErrorMsg);

                return false; //Execute only if the execution not stop

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

                $this->errorNumber = "00002"; //All the number error of this class user 4 zeros in begin
                $this->errorMsg = "Connection::executeQuery error: No is possible execute a query if you no begin using Connection:start in sequence.";
                $this->createCustomError($this->errorMsg, $this->errorNumber,$this->generateException,$this->printErrorMsg,$this->stopOnPrintErrorMsg);

                return false; //Execute only if the execution not stop

            }else {

                    $this->resultSet = $this->mysqliObject->query($sql);

                    if($this->resultSet === false){

                        $this->errorMsg = utf8_encode('Connect Error (' . $this->mysqliObject->errno . ') ' . $this->mysqliObject->error);
                        $this->errorNumber = $this->mysqliObject->errno;
                        $this->createCustomError($this->errorMsg,$this->errorNumber,$this->generateException,$this->printErrorMsg,$this->stopOnPrintErrorMsg);

                        return false;

                    }

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

                $this->errorNumber = "00003"; //All the number error of this class user 4 zeros in begin
                $this->errorMsg = "Connection::getResultSetFetchObject error: No is possible fetch a query if you no begin using Connection:executeQuery to 'SELECT query' in sequence.";
                $this->createCustomError($this->errorMsg, $this->errorNumber, $this->generateException, $this->printErrorMsg, $this->stopOnPrintErrorMsg);

                return false; //Execute only if the execution not stop
            }

            while($obj = $this->resultSet->fetch_object()){
                $arrayFetchReturn[] = $obj;
            }

            return $arrayFetchReturn;

        }


	 }

?>