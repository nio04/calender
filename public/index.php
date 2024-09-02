<?php include __DIR__ . "/database.php" ?>

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

  <style>
    .hidden {
      display: none !important;
    }
  </style>
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

  <div style="display: flex; justify-content: space-around; margin-top: 1rem" class="event-clear-container">
    <button style="cursor: pointer; padding: .5rem 1rem; text-transform: capitalize;" class="clear-btn">clear all events</button>
  </div>

  <!-- <ul class="show-all-events" style="display: grid; grid-template-columns: repeat(5,1fr); gap: .5rem; padding: 2rem;"></ul> -->

  <script>
    let targetScope;
    let dayContent;
    let month;
    let year;

    document.addEventListener("click", function(ev) {
      // if (!(ev.target.closest(".day-container") || ev.target.closest(".event-clear-container"))) return

      targetScope = ev.target

      const target = check(".day-container");
      dayContent = parseInt(target?.querySelector(".day-item").textContent);
      month = parseInt(document.querySelector("#prev").dataset.month)
      year = parseInt(document.querySelector("#prev").dataset.year);
      const prevBtn = document.querySelector("#prev")
      const nextBtn = document.querySelector("#next")

      // for setting an event
      if (check(".day-container")) {
        window.location.href = `?day=${targetScope.querySelector(".day-item").textContent}&month=${month}&year=${year}`
        //   const totalContents = hasEvents(target) && getEventContents(target).split(",")

        //   const contents = inputTemplate(target, dayContent, totalContents);

        //   const eventDescription = input(contents);

        //   // if eventDescription is empty then simply return
        //   if (eventDescription?.trim().length < 1 || eventDescription == null) return

        //   const event = {
        //     id: (new Date()).getTime(),
        //     day: parseInt(dayContent),
        //     month: parseInt(document.querySelector("#prev").dataset.month),
        //     year: parseInt(document.querySelector("#prev").dataset.year),
        //     event: [{
        //       id: (new Date()).getTime(),
        //       event: eventDescription
        //     }]
        //   };

        //   // before setting item to LS, check if there are existing conetent
        //   const datas = get("event-calender");

        //   if (!datas) {
        //     // there were no event data on localStorage
        //     // save event to local storage
        //     const eventInString = stringify([event]);

        //     set("event-calender", eventInString);

        //     reload()
        //   } else {
        //     // localStorage contain existing data
        //     const eventCollectins = parse(datas);
        //     let eventExist = false;

        //     // check if LS have the current date saved already
        //     for (const collection of eventCollectins) {
        //       if (collection === null) continue

        //       if (collection.day === event.day) {
        //         collection.event = [...collection.event, {
        //           id: (new Date()).getTime(),
        //           event: eventDescription
        //         }];
        //         eventExist = true;
        //       }
        //     }

        //     eventExist || eventCollectins.push(event);

        //     // set to localStorage
        //     set('event-calender', stringify(eventCollectins))

        //     reload()
        //   }

        //   target.dataset.event = "true";
      }

      // clear event from local storage
      if (check(".clear-btn")) {
        ask("you sure want to clear out all events?") && (remove('event-calender') && reload())
      }

      // click single event delete
      if (check(".clear-event-btn")) {
        // document.querySelector(".show-all-events").classList.toggle("hidden")
      }

      // delete single event
      if (check("#delete-event")) {
        // delete from LS & refresh the browser
        const id = parseInt(ev.target.dataset.id);

        // get from ls
        const eventCollectins = JSON.parse(localStorage.getItem('event-calender'));

        // filter from parsed ls
        const filterEvent = eventCollectins.filter(event => event !== null).map(event => {

          if (event.event.length === 1) {
            if (event.id === id) {
              delete event;
            } else {
              return event
            }
          } else {
            // handle when multple event within same day
            return event.event.map((ev, idx) => {
              // console.log(event)
              if (ev.id === id) {
                return event.event.splice(idx, 1);
                // delete ev;
                // console.log(event)
                // return null;
              }
              // return event
            })
          }
        })

        // set back to ls
        localStorage.setItem("event-calender", filterEvent[0] === undefined ? [] : JSON.stringify(filterEvent))

        reload()
      }

      // make the current date home button
      if (ev.target.id === "curr_day") {
        let url = new URL(window.location.href);
        url.search = ''
        url.hash = ''

        url = url.toString()
        window.location.href = url
      }

      // navigating between months
      if (check("#prev")) {
        month = ev.target.dataset.month
        year = ev.target.dataset.year

        month--;

        if (month < 1) {
          month = 12;
          year--;
        }

        window.location.href = `?month=${month}&year=${year}`;
      }

      if (check("#next")) {
        month = ev.target.dataset.month
        year = ev.target.dataset.year

        month++;

        if (month > 12) {
          month = 1;
          year++;
        }

        window.location.href = `?month=${month}&year=${year}`;
      }
    })

    document.addEventListener("DOMContentLoaded", async function() {

      month = parseInt(document.querySelector("#prev").dataset.month)
      year = parseInt(document.querySelector("#prev").dataset.year);
      let curr_month = parseInt(document.querySelector("#prev").dataset.month);
      let curr_year = parseInt(document.querySelector("#prev").dataset.year);
      let navMonth = parseInt(document.querySelector("#navigate").dataset.navMonth)
      let navYear = parseInt(document.querySelector("#navigate").dataset.navYear)

      const res = await fetch(`/getAllEvents.php?month=${navMonth}&year=${navYear}`);
      const data = await res.json();

      console.log(data)

      const allDays = [...document.querySelectorAll(".day-item")]

      for (const d of data) {
        const parent = document.querySelector(`[data-day='${d.day}']`);
        parent.insertAdjacentHTML("beforeend", eventTextLoader(d.totalEvents))
      }

      // retrive the localStorage of event-calender 
      const eventCollectins = get("event-calender") ? parse(get("event-calender")) : []

      // if there are contents on localStorage then modify the daycontainer dataset
      if (eventCollectins.length > 0) {
        const clearEventContainer = document.querySelector(".show-all-events");
        let curr_month = parseInt(document.querySelector("#prev").dataset.month);
        let curr_year = parseInt(document.querySelector("#prev").dataset.year);
        let navMonth = parseInt(document.querySelector("#navigate").dataset.navMonth)
        let navYear = parseInt(document.querySelector("#navigate").dataset.navYear)

        for (const event of eventCollectins) {

          if (event === null) continue

          function strcture(day, month, year, eventTitle, id) {
            return `
          <li style="display:flex; border:2px solid black; padding: .5rem 1rem;">
            <div style="margin-left: auto;">
                <p>day: ${day}</p>
                <p>month: ${month}</p>
                <p>year: ${year}</p>
                <p>content: ${eventTitle}</p>
            </div>
            <button id="delete-event" style="align-self: center; margin-left: 3rem; background-color: red; color: white; cursor:pointer;" data-id="${id}">delete</button>
          </li>`
          }

          // if (event.event?.length > 1) {
          //   [event].forEach(ev => {
          //     ev.event.map(e => {
          //       console.log(e.id)
          //       const template = strcture(event.day, event.month, event.year, e.event, e.id);
          //       clearEventContainer.insertAdjacentHTML("beforeend", template)
          //     })
          //   });
          // } else {
          //   const template = strcture(event.day, event.month, event.year, event.event[0]?.event, event.event[0].id);
          //   clearEventContainer.insertAdjacentHTML("beforeend", template)
          // }

          if (!(navMonth === event.month && navYear === event.year)) continue

          const day = document.querySelector(`[data-day='${event.day}']`)
          day.dataset.event = "true";
          day.dataset.hasEvent = "true";

          // add a dynamic content specifying the specific day may or may not have a event
          day.insertAdjacentHTML("beforeend", eventTextLoader(day, event.event.length))
          // add the event but hide it for now
          day.insertAdjacentHTML("beforeend", setEventDetails(event.event))

        }
      } else {
        // remove clear event button
        deleteEl('.event-clear-container')
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
      return true;
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

    function input(q) {
      const p = prompt(q);
      console.log("PROMPT=>", p)
      return p;

    }

    function deleteEl(element) {
      document.querySelector(element).remove()
    }

    function getEventContents(target) {
      return target.querySelector(".event-content").textContent
    }

    function deleteBtn(parent) {
      const button = document.createElement("div")
      button.innerText = "Click me";

      return parent.append(button);
    }

    function inputTemplate(target, dayContent, totalContents) {
      return `
> ${hasEvents(target) ? `day ${dayContent}: has following ${totalContents.length} events:` : `day ${dayContent}: has no events`}
${totalContents ? totalContents.map(content=> `   * ${content}`).join("\n") : ""}
> save event on day: ${dayContent}:
        `;
    }

    // 
    function hasEvents(target) {
      if (check(".day-container")?.dataset.hasEvent === "true") {
        // take the event content from the hidden block
        return target.querySelector(".event-content");
      }
    }

    // ev text loader: specify if certain day have an event or not
    function eventTextLoader(count) {
      return `<div style="font-size: 15px; font-family: Arial, Helvetica, sans-serif; background-color: #e4e1e1; border-radius: 1rem; padding: 6px;">ev ${count}</div>`
    }

    function setEventDetails(event) {
      return `<div style="display:none;" class="event-content"> ${event}</div>`
    }
  </script>

</body>

</html>
