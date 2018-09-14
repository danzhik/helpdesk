<?php

//database connection configuration

$dblocation = "127.0.0.1";
$dbname = "helpdesk";
$dbuser = "root";
$dbpasswd = "";

$db = new mysqli($dblocation, $dbuser, $dbpasswd, $dbname);

if ($db->connect_error){
    die ('DBMS connection error:'.$db->connect_error);
}

$db->set_charset ('utf8');

