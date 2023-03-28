<?php
include("DBQueries.php");
$DBQueries = new DBQueries();
$data = $DBQueries->getAllUsers();
header("Content-Type: application/json");
echo json_encode($data);
exit();
?>