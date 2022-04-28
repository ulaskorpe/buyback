<?php
try {
    $user = "gteknolo_yser2";
    $pass = "DBsecret2021";

    $dbh = new PDO('mysql:host=localhost;dbname=gteknolo_db', $user, $pass,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));

} catch (PDOException $e) {
    print "Hata!: " . $e->getMessage() . "<br/>";
    die();
}

?>