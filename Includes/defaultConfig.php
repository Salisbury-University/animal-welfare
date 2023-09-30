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

    $defaultConfigFile = '[Database]
databaseIP = "localhost"
databaseUsername = "user"
databasePassword = "password"
databaseName = "database name"

[DEBUG]
debugMode = 1
';

?>