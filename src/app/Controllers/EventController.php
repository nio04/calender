<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Traits\Database;

class EventController extends Controller {
  use Database;

  function view1() {
    $this->render("practise1");
  }

  function view2() {
    $this->render("practise2");
  }

  function getAllEvents() {
    header("Content-Type: application/json");
    $allEvents = $this->query("SELECT title, day, month, year, created_at FROM events");
    echo json_encode($allEvents);
  }

  function handleP2() {
    header("Content-Type: application/json");
    $content = file_get_contents("php://input");
    $content = json_decode($content, true);

    $firstName = $content['firstName'];
    $lastName = $content['lastName'];
    $age = $content['age'];
    $gender = $content['gender'];
    $hobbies = $content['hobbies'];
    $about = $content['about'];

    // santize 
    $firstName = htmlspecialchars(trim($firstName));
    $lastName = htmlspecialchars(trim($lastName));
    $age = (int) htmlspecialchars(trim($age));
    $gender = htmlspecialchars(trim($gender));
    $about = htmlspecialchars(trim($about));
    $hobbiesCollection = [];
    foreach ($hobbies as $hobby) {
      $hobbiesCollection[] = htmlspecialchars(trim($hobby));
    }

    // validate
    $errors = [];

    if (empty($firstName)) {
      $errors['data']['firstName'] = "first name can not be empty";
    }
    if (empty($lastName)) {
      $errors['data']['lastName'] = "last name can not be empty";
    }
    if (empty($age)) {
      $errors['data']['age'] = "age can not be empty";
    }
    if (empty($gender)) {
      $errors['data']['gender'] = "you have to choose at least one gender";
    }

    if (!empty($errors)) {
      $errors['success'] = false;
      echo json_encode($errors);
      exit;
    }

    // save to db
    $userId = $this->query("INSERT into users (firstName, lastName, age, gender, hobby, about) VALUES(:firstName, :lastName, :age, :gender, :hobby, :about)", ['firstName' => $firstName, 'lastName' => $lastName, 'age' => $age, 'gender' => $gender, 'hobby' => join(",", $hobbiesCollection), 'about' => $about]);


    // send sucess msg to client
    $data = ['id' => $userId, 'success' => true];
    echo json_encode($data);
  }
}
