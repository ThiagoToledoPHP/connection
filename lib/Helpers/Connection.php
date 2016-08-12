<?php

    namespace Toledo\Helpers;

    use mysqli;
    use Exception;

     //Necessary: by default, PHP will try to load classes from your current namespace

    /**
     * Class Connection
     * Use to start, stop and execute a query in MySql Database.
     * More information in: https://github.com/ThiagoToledoPHP/Connection
     * @author Thiago Toledo <javaephp@gmail.com>
     */
class Connection
{

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
    private $preparedStatementObject; //internal prepared Statement object.

    //PSR3
    private $psrLogObject; //internal PSR3 log object to log errors
    private $errorSeverity; //Severity PSR3 Standards: emergency, alert, critical, error, warning, notice, info, debug


    /**
     * Connection constructor.
     * @param string $dbHost
     * @param string $dbName
     * @param string $dbUser
     * @param string $dbPass
     * @return boolean
     */
    public function __construct($dbHost, $dbName, $dbUser, $dbPass)
    {
        //Verify param Type mismatch
        if (!$this->verifyTypeMismatch("string", "__construct", "dbHost", $dbHost)) {
            return false;
        }
        if (!$this->verifyTypeMismatch("string", "__construct", "dbName", $dbName)) {
            return false;
        }
        if (!$this->verifyTypeMismatch("string", "__construct", "dbUser", $dbUser)) {
            return false;
        }
        if (!$this->verifyTypeMismatch("string", "__construct", "dbPass", $dbPass)) {
            return false;
        }

        return $this->setConnection($dbHost, $dbName, $dbUser, $dbPass);
    }

    /**
     * Internal Type Mismatch verify
     * implemented to increase compatible code :(
     * More info: http://php.net/manual/pt_BR/functions.arguments.php#functions.arguments.type-declaration
     * @param string $type
     * @param string $methodName
     * @param string $paramName
     * @param string $paramValue
     * @return bool
     */
    private function verifyTypeMismatch($type, $methodName, $paramName, $paramValue)
    {
        if ($type == "string" && !is_string($paramValue)) {
            $errorMsg = "Class Connection -- Method $methodName -- Param string $paramName Type Mismatch.";
            $errorMsg .= " Details: [".var_export($paramValue, true)."].";
            $this->createCustomError($errorMsg, "00006", "alert", "InvalidArgumentException");
            return false;
        } elseif ($type == "boolean" && !is_bool($paramValue)) {
            $errorMsg = "Class Connection -- Method $methodName -- Param boolean $paramName Type Mismatch.";
            $errorMsg .= " Details: [".var_export($paramValue, true)."].";
            $this->createCustomError($errorMsg, "00006", "alert", "InvalidArgumentException");
            return false;
        } elseif ($type == "object" && !is_object($paramValue)) {
            $errorMsg = "Class Connection -- Method $methodName -- Param object $paramName Type Mismatch.";
            $errorMsg .= " Details: [".var_export($paramValue, true)."].";
            $this->createCustomError($errorMsg, "00006", "alert", "InvalidArgumentException");
            return false;
        } elseif ($type == "array" && !is_array($paramValue)) {
            $errorMsg = "Class Connection -- Method $methodName -- Param array $paramName Type Mismatch.";
            $errorMsg .= " Details: [".var_export($paramValue, true)."].";
            $this->createCustomError($errorMsg, "00006", "alert", "InvalidArgumentException");
            return false;
        }
        return true;
    }

    /**
     * Return a error message, if exists.
     * @return string
     */
    public function getErrorMsg()
    {
        return $this->errorMsg;
    }

    /*
     * Set if the class generate or not exceptions
     * @param boolean $generateException
     * @return boolean
     */
    public function setGenerateException($generateException)
    {
        //Verify param Type mismatch
        if (!$this->verifyTypeMismatch("boolean", "setGenerateException", "generateException", $generateException)) {
            return false;
        }

        $this->generateException = $generateException;
        return true;
    }

    /**
     * Set if the class generate logs with PSR3 Object
     * @param object $psrLogObject
     * @return boolean
     */
    public function setPsrLogObject($psrLogObject)
    {
        //Verify param Type mismatch
        if (!$this->verifyTypeMismatch("object", "setPsrLogObject", "psrLogObject", $psrLogObject)) {
            return false;
        }
        $this->psrLogObject = $psrLogObject;
        return true;
    }

    /**
     * Return a error number, if exists.
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * Return a error severity, if exists.
     * Use PSR3 standards values: emergency, alert, critical, error, warning, notice, info, debug, log
     * more info: http://www.php-fig.org/psr/psr-3/
     * @return string
     */
    public function getErrorSeverity()
    {
        return $this->errorSeverity;
    }

    /**
     * Return a true, if connected
     * @return boolean
     */
    public function isConnected()
    {
        return $this->isConnected;
    }

    /**
     * Return a escape string with mysqli escapes tring
     * @param string $string
     * @return string|boolean
     */
    public function getEscapeString($string)
    {
        if (!$this->verifyTypeMismatch("string", "getEscapeString", "string", $string)) {
            return false;
        }
        return $this->mysqliObject->real_escape_string($string);
    }


    /**
     * Set the connection, if you need
     * @param string $dbHost
     * @param string $dbName
     * @param string $dbUser
     * @param string $dbPass
     * @return boolean
     */
    public function setConnection($dbHost, $dbName, $dbUser, $dbPass)
    {

        //Verify param Type mismatch
        if (!$this->verifyTypeMismatch("string", "setConnection", "dbHost", $dbHost)) {
            return false;
        }
        if (!$this->verifyTypeMismatch("string", "setConnection", "dbName", $dbName)) {
            return false;
        }
        if (!$this->verifyTypeMismatch("string", "setConnection", "dbUser", $dbUser)) {
            return false;
        }
        if (!$this->verifyTypeMismatch("string", "setConnection", "dbPass", $dbPass)) {
            return false;
        }

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
        $this->preparedStatementObject = null; //Internal Default

        //PSR3
        $this->psrLogObject = null; //Internal Default
        $this->errorSeverity = "";

        return true;
    }

    /**
     * Create a custom error. Can create one exception, print and stop application
     * @param string $errorMsg
     * @param string $errorCode
     * @param string $errorSeverity
     * @param string $exceptionType
     * @throws Exception
     */
    private function createCustomError($errorMsg, $errorCode, $errorSeverity, $exceptionType = "")
    {

        $this->errorMsg = $errorMsg;
        $this->errorCode = $errorCode;
        $this->errorSeverity = $errorSeverity;

        if (!is_null($this->psrLogObject)) {
            $this->psrLogObject->$errorSeverity(strtoupper($errorSeverity)." :: ".$errorCode." :: ".$errorMsg);
        }

        if ($this->generateException === true) {
            if ($exceptionType == "") {
                throw new Exception(strtoupper($errorSeverity) . " :: " . $errorMsg, $errorCode);
            } else {
                throw new $exceptionType(strtoupper($errorSeverity) . " :: " . $errorMsg, $errorCode);
            }
        }
    }

    /**
     * Create prepared Statement using mysqli.
     * Types String using the mysqli reference: Type specification chars Table:
     * s = String, i = Integer, d = Double,  B = Blob
     * Reference: http://php.net/manual/en/mysqli-stmt.bind-param.php
     *
     * @param string $query
     * @param string $typesString
     * @param array $valuesArray
     * @return boolean
     */
    public function createPreparedStatement($query, $typesString, $valuesArray)
    {

        //Verify param Type mismatch
        if (!$this->verifyTypeMismatch("string", "createPreparedStatement", "query", $query)) {
            return false;
        }
        if (!$this->verifyTypeMismatch("string", "createPreparedStatement", "typesString", $typesString)) {
            return false;
        }
        if (!$this->verifyTypeMismatch("array", "createPreparedStatement", "valuesArray", $valuesArray)) {
            return false;
        }

        //This method only can be used after the method start create mysqli object
        if ($this->isConnected === true) {
            $tempParamsArray = array();
            $this->preparedStatementObject = $this->mysqliObject->stmt_init();

            if ($this->preparedStatementObject->prepare($query) === false) {
                $errorMsg = "Class Connection -- Method createPreparedStatement -- ";
                $errorMsg .= "Error number: ".$this->preparedStatementObject->errno." -- ";
                $errorMsg .= "Error description: ".$this->preparedStatementObject->error." -- ";
                $errorMsg .= "Wrong SQL: ".$query;

                $this->createCustomError(utf8_encode($errorMsg), $this->preparedStatementObject->errno, "alert");

                return false;
            }

            if (sizeof($valuesArray) == strlen($typesString)) {
                $tempParamsArray[] = &$typesString;
                for ($i = 0; $i < sizeof($valuesArray); $i++) {
                    $tempParamsArray[] = &$valuesArray[$i];
                }


                call_user_func_array(array($this->preparedStatementObject, 'bind_param'), $tempParamsArray);

                return true;
            }

            $errorMsg = "Class Connection -- Method createPreparedStatement -- ";
            $errorMsg .= "Error number: 0005 -- ";
            $errorMsg .= "Error description: Is necessary equal numbers of elements in valuesArray param and 
            types string param.";

            $this->createCustomError(utf8_encode($errorMsg), "00005", "alert");

            return false;
        }

        $errorMsg = "Class Connection -- Method createPreparedStatement -- ";
        $errorMsg .= " This method only can be user after use the start Connection method and sucess connect to DB.";
        $this->createCustomError($errorMsg, "00007", "alert");

        return false;
    }

    /**
     * Start the Database connection
     * @return bool
     */
    public function start()
    {

        //Only used @ to total control the report of application
        $mysqli = @new mysqli($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName);


        //$connect_error was broken until PHP 5.2.9 and 5.3.0.
        if ($mysqli->connect_error) {
            $this->isConnected = false;

            $errorMsg = "Class Connection -- Method start -- ";
            $errorMsg .= "Error number: ".$mysqli->connect_errno." -- ";
            $errorMsg .= "Error message: ".$mysqli->connect_error;
            $this->createCustomError($errorMsg, $mysqli->connect_errno, "emergency");

            return $this->isConnected;
        }

        /*
         * Use this instead of $connect_error if you need to ensure
         * compatibility with PHP versions prior to 5.2.9 and 5.3.0.
         */
        if (mysqli_connect_error()) {
            $this->isConnected = false;

            $errorMsg = "Class Connection -- Method start -- ";
            $errorMsg .= "Error number: ".mysqli_connect_errno()." -- ";
            $errorMsg .= "Error message: ".mysqli_connect_error();
            $this->createCustomError($errorMsg, mysqli_connect_errno(), "emergency");

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
    public function stop()
    {

        //Verify if the class is success connected
        if ($this->isConnected === false) {
            $errorMsg = "Class Connection -- Method stop -- ";
            $errorMsg .= " This method only can be user after use the start Connection method ";
            $errorMsg .= "and success connect to DB.";
            $this->createCustomError($errorMsg, "00001", "alert");

            return false;
        }

        //Verify if the use preparedStatement
        if (!is_null($this->preparedStatementObject)) {
            $this->preparedStatementObject->close();
        }

        $this->mysqliObject->close();
        $this->isConnected = false;

        return true;
    }

    /**
     * Execute a query in Database. Return true in sucess
     * @param string $sql
     * @return bool
     */
    public function executeQuery($sql = "")
    {

        //Verify param Type mismatch
        if (!$this->verifyTypeMismatch("string", "executeQuery", "sql", $sql)) {
            return false;
        }

        //Verify if the class is success connected
        if ($this->isConnected === false) {
            $errorMsg = "Class Connection -- Method executeQuery -- ";
            $errorMsg .= " This method only can be user after use the start Connection method ";
            $errorMsg .= "and success connect to DB.";
            $this->createCustomError($errorMsg, "00002", "alert");

            return false;
        }

        //No use preparedStatement if is passed one diferent string or prepared statement is enabled
        if (is_null($this->preparedStatementObject) || $sql !== "") {
            //Clear prepared and create the mysqli query
            $this->clearPreparedStatement();
            $this->resultSet = $this->mysqliObject->query($sql);

            if ($this->resultSet === false) {
                $errorMsg = "Class Connection -- Method executeQuery -- ";
                $errorMsg .= "Error number: ".$this->mysqliObject->errno." -- ";
                $errorMsg .= "Error message: ".$this->mysqliObject->error;
                $this->createCustomError($errorMsg, $this->mysqliObject->errno, "alert");

                return false;
            }

            return true;
        }

        $this->resultSet = $this->preparedStatementObject->execute();

        if ($this->resultSet === false) {
            $errorMsg = "Class Connection -- Method executeQuery --- ";
            $errorMsg .= "Error number: ".$this->preparedStatementObject->errno." --- ";
            $errorMsg .= "Error message: Prepared Statement -- ".$this->preparedStatementObject->error;
            $this->createCustomError($errorMsg, $this->preparedStatementObject->errno, "alert");

            return false;
        }

        return true;
    }


    /**
     * Use only after executeQuery with 'SELECT SQLs', create a array of objects, or false in error
     * @return array|bool
     */
    public function getResultSetFetchArrayObjects()
    {

        $arrayFetchReturn = array();

        //Check if the result set exists
        if (is_null($this->resultSet)) {
            $errorMsg = "Class Connection -- Method getResultSetFetchArrayObjects -- ";
            $errorMsg .= " No is possible fetch a query if you no begin using ";
            $errorMsg .= "Connection:executeQuery to 'SELECT query' in sequence.";
            $this->createCustomError($errorMsg, "00003", "alert");

            return false;
        }

        //Check if no use prepared Statements
        if (is_null($this->preparedStatementObject)) {
            while ($obj = $this->resultSet->fetch_object()) {
                $arrayFetchReturn[] = $obj;
            }

            return $arrayFetchReturn;
        }

        $result = $this->returnPreparedStatementFetchValues();
        $this->clearPreparedStatement();
        return $result;
    }

    /**
     * Clear the prepared Statement Object
     */
    public function clearPreparedStatement()
    {
        $this->preparedStatementObject = null;
    }

    /**
     * Great internal function to return prepared Statements
     * Thank you  hamidhossain at gmail dot com  for help with initial code :)
     * @return boolean|array
     */
    private function returnPreparedStatementFetchValues()
    {

        $result = array();

        $meta = $this->preparedStatementObject->result_metadata();
        while ($field = $meta->fetch_field()) {
            $params[] = &$row[$field->name];
        }

        call_user_func_array(array($this->preparedStatementObject, 'bind_result'), $params);

        while ($this->preparedStatementObject->fetch()) {
            foreach ($row as $key => $val) {
                $c[$key] = $val;
            }
            $result[] = (object)$c;
        }

        return $result;
    }
}
