<?php

    //Configure test bd
    //Structure in test.sql

    define("TEST_INVALID_HOST_BD","test");
    define("TEST_INVALID_USER_BD","");
    define("TEST_INVALID_PASS_BD","");
    define("TEST_INVALID_NAME_BD","");

    //NEVER connect this in production DB!!
    define("TEST_VALID_HOST_BD","127.0.0.1");
    define("TEST_VALID_USER_BD","travis");
    define("TEST_VALID_PASS_BD","");
    define("TEST_VALID_NAME_BD","connection_test");

    //Test table name
    define("TEST_TABLE_NAME","test_table_client");

    //Test logs
    define("DIR_LOGS","tests/logs/");
    define("LOGS_FILE",date("Ymd") . '.log');

?>