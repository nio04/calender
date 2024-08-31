<?php

function loadPartials($file, $data = []) {
  extract($data);
  include __DIR__ . "/partials/" . $file . ".php";
}

function vd($data) {
  $trace = debug_backtrace();
  $caller = $trace[0];
  echo '<br><br>' . 'File: ' . $caller['file'] . '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; Line: ' . $caller['line'] . '<br>';
  echo '<div class="var-dump">' . nl2br(htmlspecialchars(var_export($data, true))) . '</div>';
}
