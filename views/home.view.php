<?php loadPartials("header") ?>

<div class="h-screen px-12">
  <h3 class="text-2xl my-8 uppercase border-dashed border-b-2 border-blue-500 text-center pb-2 ">ajax practises</h3>
  <ul class="grid grid-cols-3 gap-8 justify-items-center items-stretch">
    <li class="border-2 border-blue-500 p-2 capitalize hover:bg-blue-200 transition-all rounded-lg text-gray-700">
      <a href="/practise-1">go to practise-1: fetch data from database on data recived on client side by ajax</a>
    </li>
    <li class="border-2 border-blue-500 p-2 capitalize hover:bg-blue-200 transition-all rounded-lg text-gray-700"><a href="/practise-2">go to practise-2: send data from client and know if the data successfully saved on the database</a></li>
    <li class="border-2 border-blue-500 p-2 capitalize hover:bg-blue-200 transition-all rounded-lg text-gray-700"><a href="/practise-3">go to practise-3: form validation on both client and server side</a></li>
  </ul>
</div>

<?php loadPartials("footer") ?>
