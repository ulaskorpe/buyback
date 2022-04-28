<?php
include "connect_buyback_db.php";

$data=(!empty($_REQUEST['data']))?$_REQUEST['data']:'YOK';
$sql_insert="INSERT INTO tmp (title,data)
VALUES ('".date('Y-m-dHis')."','".$data."') ";
///echo $sql_insert;
if(!$dbh->query($sql_insert)){
    print_r($dbh->errorInfo());
}

?>