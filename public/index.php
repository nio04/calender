<?php

function vd($data) {
  $trace = debug_backtrace();
  $caller = $trace[0];
  echo '<br><br>' . 'File: ' . $caller['file'] . '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; Line: ' . $caller['line'] . '<br>';
  echo '<div class="var-dump">' . nl2br(htmlspecialchars(var_export($data, true))) . '</div>';
}

?>

<html>
<header>
  <title>calender</title>
</header>

<body style="padding-left: 1rem; padding-right: 1rem;">
  <?php
  $year = date('Y');
  $month = date('m');
  $firstDayTimestamp = mktime(0, 0, 0, $month, 1, $year);
  $firstDayOfWeek = date('N', $firstDayTimestamp);

  $calenderHeader = date("d F, Y");
  $storeWeekDays = ['su', 'mo', 'tu',  'we', 'th', 'fr', 'sa'];
  $maxDay = date('t');
  $currentDay = date('j');
  $storeDays = [];
  ?>

  <!-- store: loop and store current month days -->
  <?php for ($i = 1; $i <= $maxDay; $i++): ?>
    <?php $storeDays[] = $i ?>
  <?php endfor ?>

  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 2rem;">
    <button style="padding: .5rem 1rem; text-transform: uppercase;cursor:pointer;">prev</button>
    <p>Today is: <?= $calenderHeader ?></p>
    <button style="padding: .5rem 1rem;cursor:pointer;">next</button>
  </div>

  <!-- display week days name -->
  <div style="display:grid; grid-template-columns: repeat(7,1fr); gap:2rem; margin-bottom: 2rem;">
    <?php foreach ($storeWeekDays as $weekDay): ?>
      <div style="text-transform: uppercase;"><?= $weekDay ?></div>
    <?php endforeach ?>
  </div>

  <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 1rem;">

    <!-- when the $i is divisble by 7 break the row to a new line -->
    <?php $breakLine = 8 ?>
    <?php for ($i = 1; $i <= $maxDay; $i++): ?>

      <?php if ($i === 1): ?>
        <!-- set grid col start only for the first day of the month -->
        <div style="display: flex; align-items:center; justify-content:space-between; padding:1rem; grid-column: <?= $firstDayOfWeek + 1 ?>; cursor:pointer" class="day-container" data-event="false" data-day="<?= $i ?>" data-has-event="false">
          <div style="" class="day-item"><?= $i ?></div>
        </div>
      <?php else: ?>
        <div style="display: flex; align-items:center; justify-content:space-between; cursor:pointer; padding:1rem" class="day-container" data-event="false" data-day="<?= $i ?>" data-has-event="false">
          <div style="" class="day-item"><?= $i  ?></div>
        </div>
      <?php endif ?>
    <?php endfor ?>
  </div>

  <div style="display: flex; justify-content: center; margin-top: 1rem" class="event-clear-container">
    <button style="cursor: pointer; padding: .5rem 1rem; text-transform: capitalize;" class="clear-btn">clear all events</button>
  </div>

  <script>
    let targetScope;

    document.addEventListener("click", function(ev) {
      // if (!(ev.target.closest(".day-container") || ev.target.closest(".event-clear-container"))) return

      targetScope = ev.target

      const target = check(".day-container");
      const dayContent = target?.querySelector(".day-item").textContent;

      // for setting an event
      if (check(".day-container")?.dataset.event === 'false') {
        const eventDescription = prompt(`save event on day: ${dayContent}`);

        // if eventDescription is empty then simply return
        if (eventDescription?.trim().length < 1 || eventDescription == null) return

        const event = {
          day: parseInt(dayContent),
          event: eventDescription
        };

        // before setting item to LS, check if there are existing conetent
        const datas = get("event-calender");

        if (!datas) {
          // there were no event data on localStorage
          // save event to local storage
          const eventInString = stringify([event]);

          set("event-calender", eventInString);

          reload()
        } else {
          // localStorage contain existing data
          const eventCollectins = parse(datas);

          eventCollectins.push(event);

          // set to localStorage
          set('event-calender', stringify(eventCollectins))

          reload()
        }

        target.dataset.event = "true";
      }

      // for displaying event
      if (check(".day-container")?.dataset.hasEvent === "true") {
        // take the event content from the hidden block
        const event = target.querySelector(".event-content").textContent;

        show(`${event}`)
      }

      // clear event from local storage
      if (check(".event-clear-container")) {
        if (ask("you sure want to clear out all events?")) {
          remove('event-calender')
          reload()
        }
      }
    })

    document.addEventListener("DOMContentLoaded", function() {
      // retrive the localStorage of event-calender 
      const eventCollectins = get("event-calender") ? parse(get("event-calender")) : []

      // if there are contents on localStorage then modify the daycontainer dataset
      if (eventCollectins.length > 0) {

        for (const event of eventCollectins) {
          const day = document.querySelector(`[data-day='${event.day}']`)
          day.dataset.event = "true";
          day.dataset.hasEvent = "true";

          // add a dynamic content specifying the specific day may or may not have a event
          day.insertAdjacentHTML("beforeend", eventTextLoader())
          // add the event but hide it for now
          day.insertAdjacentHTML("beforeend", setEventDetails(event.event))
        }
      }
    })

    // miscs : helper
    function reload() {
      location.reload()
    }

    function stringify(obj) {
      return JSON.stringify(obj)
    }

    function parse(strings) {
      return JSON.parse(strings)
    }

    function get(item) {
      return localStorage.getItem(item)
    }

    function set(key, value) {
      return localStorage.setItem(key, value);
    }

    function remove(key) {
      localStorage.removeItem(key)
    }

    function show(content) {
      alert(content)
    }

    function ask(content) {
      return confirm(content)
    }

    function check(element) {
      return targetScope.closest(element)
    }



    // ev text loader: specify if certain day have an event or not
    function eventTextLoader() {
      return `<div style="font-size: 15px; font-family: Arial, Helvetica, sans-serif; background-color: #e4e1e1; border-radius: 50%; padding: 6px;">ev</div>`
    }

    function setEventDetails(event) {
      return `<div style="display:none;" class="event-content"> ${event}</div>`
    }
  </script>

</body>

</html>
