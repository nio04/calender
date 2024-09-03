<?php loadPartials('header') ?>

<section class="h-screen p-8">
  <div class="flex items-center gap-6 mb-8">
    <h2 class="text-2xl">When this page first load, we will automatically get events from db by ajax</h2>
    <a href="/" class="go-back" class="ml-auto text-blue-900 uppercase underline cursor-pointer">go back to homepage</a>
  </div>
  <h3 class="uppercase mb-4 text-xl">events:</h3>
  <?php loadPartials("spinner") ?>
  <ul id="result" class="grid grid-cols-[repeat(auto-fill,minmax(min-content,30rem))] gap-8 border-2 border-blue-500 p-4"></ul>
</section>

<?php loadPartials('footer') ?>
