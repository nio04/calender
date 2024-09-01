<?php loadPartials("header") ?>

<div class="flex items-center justify-center h-screen">
  <div class="w-full max-w-xs">
    <form id="greeting-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
      <div id="error" class="text-center text-white my-6 uppercase bg-red-500"></div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
          Name
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="name" id="name" type="text" placeholder="Enter your name">
      </div>
      <div class="flex items-center justify-between">
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
          Submit
        </button>
      </div>
    </form>
    <button id="show-all-btn" class="bg-green-500 text-white px-4 py-2 rounded mb-5">Show All</button>
    <div id="loading-spinner" class="hidden text-center text-blue-500 mt-4">Loading...</div>
    <div id="greeting-response" class="text-center text-gray-700 mt-4"></div>
  </div>
</div>

<?php loadPartials("footer") ?>
