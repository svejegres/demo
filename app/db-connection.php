<?php

/*
 * CONNECT to the database
 */
function db() {
  static $conn;
  if ($conn === NULL) {
    $conn = mysqli_connect('your_hostname', 'your_username', 'your_password', 'your_db_name');
    if (!$conn) {
      die('Connection failed ' . mysqli_error($conn));
    }
  }
  return $conn;
}
$conn = db();

// MySQL used to create table 'users':
// create table users (user_id int auto_increment primary key, username varchar(255) not null, password varchar(255) not null);

// MySQL used to create table 'user_categories':
// create table user_categories (category_id int auto_increment primary key, username varchar(255) not null, title varchar(255) not null, description varchar(255) not null, parent_id int, nesting_lvl int);
