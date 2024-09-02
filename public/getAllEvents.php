<?php include __DIR__ . "/database.php" ?>

<?php

$allEvents = query($db, "SELECT id, title, day, month, year FROM events WHERE month = :month AND year = :year", ['month' => $_GET['month'], 'year' => $_GET['year']]);

header("content-Type:application/json");

echo json_encode($allEvents);
