<?php include __DIR__ . "/database.php" ?>

<?php

$allEvents = query($db, "SELECT count(day) as totalEvents, day from events where month = :month and year = :year group by day", ['month' => $_GET['month'], 'year' => $_GET['year']]);

header("content-Type:application/json");

echo json_encode($allEvents);
