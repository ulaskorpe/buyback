<?php
include "connect_buyback_db.php";
//phpinfo();


$tmps = $dbh->query("SELECT * FROM tmp");
foreach ($tmps as $tmp) {


    echo $tmp['title']."<br><p>".$tmp['data']."</p>";

}

?>