<?php
/*
    This file holds the default config file, stored as a string variable.
    The config handler includes this file and uses it to replace the config file 
    if it doesnt exist. 

    ONLY MODIFY THE CONTENTS OF THE VARIABLE
    Additionally, make sure that what you write follows the .ini file format.
    If you are the salisbury IT (or whoever has to configure this website), 
        DO NOT MODIFY THIS FILE
*/

$defaultConfigFile = ';MYSQL database credentials
[Database]
databaseIP = "localhost"
databaseUsername = "user"
databasePassword = "password"
databaseName = "database name"

; If you get locked out of the website, enable this account, log in and add a new user or manage an existing one
; Keep in mind this account IS NOT FOR REGULAR USE and will be disabled automatically after one login.
[Recovery]
recoveryAccountEnabled = 0
recoveryAccountUsername = "root"
recoveryAccountPassword = "defPass1"

; Only touch this section if you want errors to be printed on the website to the user
[DEBUG]
debugMode = 0
';

?>