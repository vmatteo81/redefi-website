<?php

require '../assets/setup/env.php';
require '../assets/setup/db.inc.php';

if (!isset($_GET['wallet_address'])){
    $ret = array("status"=>"error", "status_desc"=>"no wallet address provided");
    echo json_encode($ret);
    return;
}

$sql = "SELECT * FROM wallets WHERE wallet_address=?;";
$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {

    $ret = array("status"=>"error", "status_desc"=>"sql error, plz retry later");
    echo json_encode($ret);
    return;
} 
else {

    mysqli_stmt_bind_param($stmt, "s", $_GET['wallet_address']);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $ret = array("status"=>$row['status'], "status_desc"=>$row['status_desc']);
        echo json_encode($ret);
        return;
    }
    else {
        $ret = array("status"=>"error", "status_desc"=>"no wallet found");
        echo json_encode($ret);
        return;
    }
}
