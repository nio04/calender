<?php loadPartials("header") ?>

<section>
  <h2 class="text-lg p-4 leading-snug text-gray-600">practise-2: where we send many weirds data directly from user input and then save it to database and we will know if the data is successfully saved on the database or not. we will also render the id of the newly saved data</h2>

  <h2 class="text-center text-3xl uppercase my-6">form</h2>
  <form action="" method="post">
    <div class="grid grid-cols-[110px_250px] my-4 pl-[35%] capitalize" data-container="firstName">
      <label for="fname" class="pr-6">first name</label>
      <input type="text" name="first_name" id="fname" placeholder="enter your first name...">
    </div>
    <div class="grid grid-cols-[110px_250px] my-4 pl-[35%] capitalize" data-container="lastName">
      <label for="lname" class="pr-6">last name</label>
      <input type="text" name="last_name" id="lname" placeholder="enter your last name...">
    </div>
    <div class="grid grid-cols-[110px_250px] my-4 pl-[35%] capitalize" data-container="age">
      <label for="age" class="pr-6">age</label>
      <input type="text" name="age" id="age" placeholder="enter your age...">
    </div>
    <div class="grid grid-cols-[110px_250px] my-4 pl-[35%] capitalize" data-container="gender">
      <h2>gender: </h2>
      <div class="flex gap-8">
        <div>
          <label for="male-gender">male</label>
          <input type="radio" name="gender" id="male-gender" value="male">
        </div>
        <div>
          <label for="female-gender">gender</label>
          <input type="radio" name="gender" id="female-gender" value="female">
        </div>
        <div>
          <label for="other-gender">other</label>
          <input type="radio" name="gender" id="other-gender" value="other">
        </div>
      </div>
    </div>
    <div class="grid grid-cols-[110px_250px] my-4 pl-[35%] capitalize" data-container="hobby">
      <h2>hobby:</h2>
      <div class="flex gap-8">
        <div>
          <label for="cricket-hobby">cricket</label>
          <input type="checkbox" name="hobby" id="cricket-hobby" value="cricket">
        </div>
        <div>
          <label for="football-hobby">football</label>
          <input type="checkbox" name="hobby" id="football-hobby" value="football">
        </div>
      </div>
    </div>
    <div class="grid grid-cols-[110px_250px] my-4 pl-[35%] capitalize" data-container="about">
      <h2>write about yourself</h2>
      <div>
        <textarea name="about" class="w-full"></textarea>
      </div>
    </div>
    <input type="submit" value="add" class="pl-[50%] py-4 cursor-pointer">
  </form>
</section>

<?php loadPartials("footer") ?>
