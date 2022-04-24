<?php
session_start();

require '../../assets/includes/security_functions.php';
require '../../assets/includes/auth_functions.php';
check_verified();

require '../../assets/vendor/PHPMailer/src/Exception.php';
require '../../assets/vendor/PHPMailer/src/PHPMailer.php';
require '../../assets/vendor/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function checkFileUploaded($filenew,$file,$imageerror,$allowed,$folder)
{
    if (!empty($file['name']))
    {
        $fileName = $file['name'];
        $fileTmpName =$file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileType = $file['type']; 

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));
        
        if (in_array($fileActualExt, $allowed))
        {
            if ($fileError === 0)
            {
                if ($fileSize < 10000000)
                {
                    $filenew = uniqid('', true) . "." . $fileActualExt;
                    $fileDestination = $folder.$filenew;
                    move_uploaded_file($fileTmpName, $fileDestination);

                    /*
                    * -------------------------------------------------------------------------------
                    *   Deleting old profile photo
                    * -------------------------------------------------------------------------------
                    */
                    if ( $_SESSION['profile_image'] != "_defaultUser.png" ) {
                        if (!unlink($folder . $_SESSION['profile_image'])) {  

                            $_SESSION['ERRORS'][$imageerror] = 'old image could not be deleted';
                            return false;
                        } 
                    }
                }
                else
                {
                    $_SESSION['ERRORS'][$imageerror] = 'image size should be less than 10MB';
                    return false;
                }
            }
            else
            {
                $_SESSION['ERRORS'][$imageerror] = 'image upload failed, try again';
                return false;
            }
        }
        else
        {
            $_SESSION['ERRORS'][$imageerror]= 'invalid image type, try again';
            return false;
        }
    }
    else
    {
        $imageerror = 'image is required';
        return false;
    }
    return true;
}

if (isset($_POST['update-profile'])) {

    /*
    * -------------------------------------------------------------------------------
    *   Securing against Header Injection
    * -------------------------------------------------------------------------------
    */

    foreach($_POST as $key => $value){

        $_POST[$key] = _cleaninjections(trim($value));
    }

    /*
    * -------------------------------------------------------------------------------
    *   Verifying CSRF token
    * -------------------------------------------------------------------------------
    */

    if (!verify_csrf_token()){

        $_SESSION['STATUS']['editstatus'] = 'Request could not be validated';
        header("Location: ../");
        exit();
    }


    require '../../assets/setup/db.inc.php';
    require '../../assets/includes/datacheck.php';

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $birthday = $_POST['birthday'];
    $nationality = $_POST['nationality'];

    if (empty($first_name)) {
        $_SESSION['ERRORS']['first_name'] = 'First Name field is required';
        header("Location: ../");
        exit();
    } 
    else {
        $_SESSION['first_name'] =  $first_name;
    }
    if (empty($last_name)) {
        $_SESSION['ERRORS']['last_name'] = 'Last Name field is required';
        header("Location: ../");
        exit();
    } 
    else {
        $_SESSION['last_name'] =  $last_name;
    }
    if (empty($birthday)) {
        $_SESSION['ERRORS']['birthday'] = 'Birthday field is required';
        header("Location: ../");
        exit();
    } 
    else {
        $_SESSION['birthday'] =  $birthday;
    }
    if (empty($nationality)) {
        $_SESSION['ERRORS']['nationality'] = 'Nationality field is required';
        header("Location: ../");
        exit();
    } 
    else {
        $_SESSION['nationality'] =  $nationality;
    }

    $fileNew = $_SESSION['id_doc_image'];
    $file = $_FILES['idDocAvatar'];
    $fileError = 'idDocError';
    $allowed = array('jpg', 'jpeg', 'png', 'gif');
    $folder ='../../imgUpload/idDoc/';
    
    if (checkFileUploaded($fileNew,$file,$fileError,$allowed,$folder) == false)
    {
        header("Location: ../");
        exit();
    }

    $fileNew = $_SESSION['proof_addr_image'];
    $file = $_FILES['proAddrAvatar'];
    $fileError =  'proAddrError';
    $allowed = array('jpg', 'jpeg', 'png', 'gif');
    $folder ='../../imgUpload/proAddr/';
    
    if (checkFileUploaded($fileNew,$file,$fileError,$allowed,$folder) == false)
    {
        header("Location: ../");
        exit();
    }

    $fileNew = $_SESSION['kyc_video'];
    $file = $_FILES['videoAvatar'];
    $fileError = 'videoError';
    $allowed = array('mp4', 'mov', 'avi');
    $folder ='../../imgUpload/video/';
    
    if (checkFileUploaded($fileNew,$file,$fileError,$allowed,$folder) == false)
    {
        header("Location: ../");
        exit();
    }
    /*
    * -------------------------------------------------------------------------------
    *   User Updation
    * -------------------------------------------------------------------------------
    */

    $sql = "UPDATE users 
        SET username=?,
        email=?, 
        first_name=?, 
        last_name=?, 
        gender=?, 
        headline=?, 
        bio=?, 
        profile_image=?";

    if ($passwordUpdated){

        $sql .= ", password=? 
                WHERE id=?;";
    }
    else{

        $sql .= " WHERE id=?;";
    }

    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {

        $_SESSION['ERRORS']['scripterror'] = 'SQL ERROR';
        header("Location: ../");
        exit();
    } 
    else {

        if ($passwordUpdated){

            $hashedPwd = password_hash($newpassword, PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($stmt, "ssssssssss", 
                $username,
                $email,
                $first_name,
                $last_name,
                $gender,
                $headline,
                $bio,
                $FileNameNew,
                $hashedPwd,
                $_SESSION['id']
            );
        }
        else{

            mysqli_stmt_bind_param($stmt, "sssssssss", 
                $username,
                $email,
                $first_name,
                $last_name,
                $gender,
                $headline,
                $bio,
                $FileNameNew,
                $_SESSION['id']
            );
        }

        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);


        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;
        $_SESSION['gender'] = $gender;
        $_SESSION['headline'] = $headline;
        $_SESSION['bio'] = $bio;
        $_SESSION['profile_image'] = $FileNameNew;

        $_SESSION['STATUS']['editstatus'] = 'profile successfully updated';
        
        /*
            * -------------------------------------------------------------------------------
            *  Send Email for updated Kyc data
            * -------------------------------------------------------------------------------
        */
        $to = $_SESSION['email'];
        $subject = 'Kyc Updated';

        $mail_variables = array();

        $mail_variables['APP_NAME'] = APP_NAME;
        $mail_variables['email'] = $_SESSION['email'];

        $message = file_get_contents("./template_notificationemail.php");

        foreach($mail_variables as $key => $value) {
            
            $message = str_replace('{{ '.$key.' }}', $value, $message);
        }
    
        $mail = new PHPMailer(true);
    
        try {
    
            $mail->isSMTP();
            $mail->Host = MAIL_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = MAIL_USERNAME;
            $mail->Password = MAIL_PASSWORD;
            $mail->SMTPSecure = MAIL_ENCRYPTION;
            $mail->Port = MAIL_PORT;
    
            $mail->setFrom(MAIL_USERNAME, APP_NAME);
            $mail->addAddress($to, APP_NAME);
    
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;
    
            $mail->send();
        } 
        catch (Exception $e) {
            
        }

        header("Location: ../");
        exit();
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} 
else {

    header("Location: ../");
    exit();
}
