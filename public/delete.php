<?php include __DIR__ . "/database.php" ?>

<?php

$id = isset($_GET['id']) ? $_GET['id'] : "";

query($db, "DELETE from events WHERE id = :id", ['id' => $id]);

$day = $_GET['day'];
$month = $_GET['month'];
$year = $_GET['year'];

header("location:/?&month=$month&year=$year");
