<?php include __DIR__ . "/database.php" ?>

<html>
<header>
  <title>calender</title>
</header>

<body style="padding-left: 1rem; padding-right: 1rem; font-family: Arial, Helvetica, sans-serif;">
  <?php
  $month = isset($_GET['month']) ? (int) $_GET['month'] : date('m');
  $year = isset($_GET['year']) ? (int) $_GET['year'] : date('Y');
  $navMonth = date("M", mktime(0, 0, 0, $month, 1, $year));
  $navMonthDigit = date("n", mktime(0, 0, 0, $month, 1, $year));
  $navYear = date("Y", mktime(0, 0, 0, $month, 1, $year));
  $firstDayTimestamp = mktime(0, 0, 0, $month, 1, $year);
  $firstDayOfWeek = date('w', $firstDayTimestamp);

  $calenderHeader = date("d F, Y");
  $storeWeekDays = ['su', 'mo', 'tu',  'we', 'th', 'fr', 'sa'];
  $maxDay = date('t');
  $currentDay = date('j');
  $storeDays = [];
  ?>

  <?php if (isset($_GET['day']) && isset($_GET['month']) && $_GET['year']): ?>
    <?php header("location: /event.php?day=" . $_GET['day'] . "&month=" . $_GET['month'] . "&year=" . $_GET['year']) ?>
  <?php endif ?>

  <!-- store: loop and store current month days -->
  <?php for ($i = 1; $i <= $maxDay; $i++): ?>
    <?php $storeDays[] = $i ?>
  <?php endfor ?>

  <!-- header -->
  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 2rem;">
    <button id="prev" style="padding: .5rem 1rem; text-transform: uppercase;cursor:pointer;" data-month="<?= $month ?>" data-year="<?= $year ?>">prev</button>
    <p id="curr_day" style="margin-right: auto; margin-left: 2rem; cursor: pointer;">Today is: <?= $calenderHeader ?></p>
    <p id="navigate" style="margin-left: auto; margin-right: 2rem;" data-nav-month="<?= $navMonthDigit ?>" data-nav-year="<?= $navYear ?>">Month: <?= $navMonth ?>, Year: <?= $year ?></p>
    <button id="next" style="padding: .5rem 1rem; text-transform: uppercase; cursor:pointer;" data-month="<?= $month ?>" data-year="<?= $year ?>">next</button>
  </div>

  <!-- display week days name -->
  <div style="display:grid; grid-template-columns: repeat(7,1fr); gap:2rem; margin-bottom: 2rem; border: 2px dotted blue; padding: .5rem">
    <?php foreach ($storeWeekDays as $key => $weekDay): ?>
      <?php if ($key === count($storeWeekDays) - 1): ?>
        <div style="text-transform: uppercase;"><?= $weekDay ?></div>
      <?php else: ?>
        <div style="text-transform: uppercase; border-right: 2px dotted green;"><?= $weekDay ?></div>
      <?php endif ?>
    <?php endforeach ?>
  </div>

  <!-- display days in month -->
  <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 1rem; border: 2px dotted blue; padding:.5rem;">
    <!-- when the $i is divisble by 7 break the row to a new line -->
    <?php $breakLine = 8 ?>
    <?php for ($i = 1; $i <= $maxDay; $i++): ?>

      <?php if ($i === 1): ?>
        <!-- set grid col start only for the first day of the month -->
        <div style="display: flex; align-items:center; justify-content:space-between; padding:1rem; grid-column: <?= $firstDayOfWeek + 1 ?>; cursor:pointer; border: 2px dotted blue;" class="day-container" data-event="false" data-day="<?= $i ?>" data-has-event="false">
          <div style="" class="day-item"><?= $i ?></div>
        </div>
      <?php else: ?>
        <div style="display: flex; align-items:center; justify-content:space-between; cursor:pointer; padding:1rem; border: 2px dotted blue;" class="day-container" data-event="false" data-day="<?= $i ?>" data-has-event="false">
          <div style="" class="day-item"><?= $i  ?></div>
        </div>
      <?php endif ?>
    <?php endfor ?>
  </div>

  <script>
    let targetScope;
    let month;
    let year;

    document.addEventListener("click", function(ev) {

      targetScope = ev.target
      month = parseInt(document.querySelector("#prev").dataset.month)
      year = parseInt(document.querySelector("#prev").dataset.year);
      const prevBtn = document.querySelector("#prev")
      const nextBtn = document.querySelector("#next")

      // for setting an event
      if (check(".day-container")) {
        targetScope = ev.target.closest(".day-container")
        window.location.href = `?day=${targetScope.querySelector(".day-item").textContent}&month=${month}&year=${year}`
      }

      // make the current date home button
      if (ev.target.id === "curr_day") {
        redirectToHome();
      }

      // navigating between months
      if (check("#prev")) {
        navToPrevMonth(ev)
      }

      if (check("#next")) {
        navToNextMonth(ev)
      }
    })

    document.addEventListener("DOMContentLoaded", async function() {

      const allDays = [...document.querySelectorAll(".day-item")]
      let navMonth = parseInt(document.querySelector("#navigate").dataset.navMonth)
      let navYear = parseInt(document.querySelector("#navigate").dataset.navYear)

      const res = await fetch(`/getAllEvents.php?month=${navMonth}&year=${navYear}`);
      const data = await res.json();

      for (const d of data) {
        const parent = document.querySelector(`[data-day='${d.day}']`);
        parent.insertAdjacentHTML("beforeend", eventTextLoader(d.totalEvents))
      }

    })

    // miscs : helper
    function reload() {
      location.reload()
    }

    function check(element) {
      return targetScope.closest(element)
    }

    function redirectToHome() {
      let url = new URL(window.location.href);
      url.search = ''
      url.hash = ''

      url = url.toString()
      window.location.href = url
    }

    function navToPrevMonth(ev) {
      month = ev.target.dataset.month
      year = ev.target.dataset.year

      month--;

      if (month < 1) {
        month = 12;
        year--;
      }

      window.location.href = `?month=${month}&year=${year}`;
    }

    function navToNextMonth(ev) {
      month = ev.target.dataset.month
      year = ev.target.dataset.year

      month++;

      if (month > 12) {
        month = 1;
        year++;
      }

      window.location.href = `?month=${month}&year=${year}`;
    }

    // ev text loader: specify if certain day have an event or not
    function eventTextLoader(count) {
      return `<div style="font-size: 15px; font-family: Arial, Helvetica, sans-serif; background-color: #e4e1e1; border-radius: 1rem; padding: 6px;">ev ${count}</div>`
    }
  </script>

</body>

</html>
