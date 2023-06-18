<?php

require 'env.php';

$conn = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if (!$conn)
{
    die("Connection failed: ". mysqli_connect_error());
}
else
{
    if (!defined('MAIL_HOST'))
    {
        $sql = "select mail_host,mail_username,mail_password,mail_encryption,mail_port from mail_config;";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
    
            return $_SESSION['ERRORS']['scripterror'] = 'SQL error';
        } 
        else {
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);   
            if (!$row = mysqli_fetch_assoc($result)) {
                echo ("mail_config missing on db");
            }
            else {
                define('MAIL_HOST', $row['mail_host']);
                define('MAIL_USERNAME', $row['mail_username']);
                define('MAIL_PASSWORD', $row['mail_password']);
                define('MAIL_ENCRYPTION', $row['mail_encryption']);
                define('MAIL_PORT', $row['mail_port']);        
            }
        }
    }     
}
