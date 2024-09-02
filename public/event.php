<?php include __DIR__ . "/database.php" ?>
<?php

$eventDay = $_GET['day'] ?? "";
$eventMonth = $_GET['month'] ?? "";
$eventYear = $_GET['year'] ?? "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $title = isset($_POST['title']) ? $_POST['title'] : '';

  $title = htmlspecialchars(trim($title));

  // save to db
  query($db, "INSERT INTO events (title, day, month, year) VALUES (:title, :day, :month, :year)", ['title' => $title, 'day' => (int)$eventDay, 'month' => (int)$eventMonth, 'year' => (int)$eventYear]);
}
?>

<section style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 1rem;">

  <div style="grid-column: 2 / 10; grid-row: 1 / 2; display: flex; justify-content:space-around; align-items:center;">
    <p style="font-size: 1.4rem; text-transform: capitalize;">slected day is: <?= $eventDay ?>, month: <?= $eventMonth ?>, year:<?= $eventYear ?></p>
    <a href="/" style="margin-left: auto; font-size: 1.4rem; text-transform: capitalize;">go back to calender</a>
  </div>

  <div style="grid-row: 2 / 3; grid-column: 2 / 10;">
    <?php

    $lists = query($db, "SELECT id, title from events WHERE day = $eventDay AND month = $eventMonth AND year = $eventYear");

    ?>

    <?php if (count($lists) > 0) : ?>
      <p style="font-size: 1.4rem; text-transform: capitalize;">list of events</p>
      <ul style="display: grid; grid-template-columns: repeat(5, 1fr); gap:1rem; border: 2px dotted blue;  padding: 1.5rem;">
        <?php foreach ($lists as $list): ?>
          <li style="width:10rem; display: flex; border:1px dotted green;">
            <p style="font-size: 1.2rem; "><?= $list->title ?></p>
            <a href="/delete.php?id=<?= $list->id ?>" style="margin-left: auto; align-self: center; padding: .4rem .5rem;">delete</a>
          </li>
        <?php endforeach ?>
      </ul>

    <?php else: ?>
      <p style="text-align: center; font-size:1.4rem;text-transform: uppercase; padding: 2rem 0; font-family: Arial, Helvetica, sans-serif; font-style: italic; color: gray;">there is no event to display</p>
    <?php endif ?>
  </div>

  <div style="grid-row: 3 / 4; grid-column: 4 / 10; margin-top:1rem;">
    <form action="/event.php?day=<?= $eventDay ?>&month=<?= $eventMonth ?>&year=<?= $eventYear ?>" method="POST">
      <div style="display: flex;">
        <label for="title" style="font-size: 1.6rem; text-transform:capitalize; font-family:Arial, Helvetica, sans-serif; margin-right: 1rem;">enter event title:</label>
        <input type="text" name="title" id="title" placeholder="enter event title..." style="height:2.5rem; width: 12rem;">
      </div>
    </form>
  </div>
</section>
