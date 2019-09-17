<?php
session_start();

include('db-connection.php');
include('input-validation.php');

/*
 * SIGN UP logic:
 */
if (isset($_POST['signup'])) {
  $username = $_POST['name'];
  $password = $_POST['pwd'];
  $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

  /*
   * Perform input validation before storing credentials into database:
   */
  if (validateName($username) && validatePassword($password)) {
    /*
     * Store new user credentials and log in with this user:
     */
    $sql = "INSERT INTO users (username, password) VALUES ('{$username}', '{$passwordHashed}')";
    if (mysqli_query($conn, $sql)) {
      $_SESSION['Username'] = $username;
      header('location: private-page.php');
    } else {
      echo "Error: ". mysqli_error($conn);
    }
  }
}

/*
 * LOGIN logic:
 */
if (isset($_POST['login'])) {
  $username = $_POST['name'];

  $sql = "SELECT password FROM users WHERE username='{$username}';";

  $password = mysqli_fetch_row(mysqli_query($conn, $sql))[0];
  $checked = password_verify($_POST['pwd'], $password);

  if ($checked) {
    $_SESSION['Username'] = $username;
    header('location: private-page.php');
  } else {
    $_SESSION['Error'] = "Wrong credentials! Please try again.";
  }
}

/*
* LOGOUT logic:
*/
if (isset($_POST['logout'])) {
  if($_POST['logout'] === "true") {
    $_POST['logout'] = "false";
    unset($_SESSION['Username']);
  }
}

/*
 * SAVE new category into database:
 */
if (isset($_POST['save'])) {
  $username = $_SESSION['Username'];
  $title = $_POST['title'];
  $description = $_POST['description'];
  $parentId = $_POST['parent_id'];

  if($parentId === "0") {
    $nestingLvl = 0;
  } else {
    $nestingLvl = 1 + mysqli_fetch_row(mysqli_query($conn, "SELECT nesting_lvl FROM user_categories WHERE category_id='{$parentId}';"))[0];
  }

  $stmt = $conn->prepare('INSERT INTO user_categories (username, title, description, parent_id, nesting_lvl) VALUES (?, ?, ?, ?, ?)');
  $stmt->bind_param('sssii', $username, $title, $description, $parentId, $nestingLvl);

  if ($stmt->execute()) {
    $id = mysqli_insert_id($conn);
    $offset = $_POST['nesting_lvl'] * 30;
    $margin = "0 " . (5 - $offset) . "px 0 " . (5 + $offset) . "px";
    $saved_task = '<div class="category-box" data-parent_id="' . $_POST['parent_id'] . '" data-nesting_lvl="' . $_POST['nesting_lvl'] .
     '" style="margin: ' . $margin . '">
        <span class="new" data-id="' . $id . '" >new</span>
        <span class="delete" data-id="' . $id . '" >delete</span>
        <span class="edit" data-id="' . $id . '">edit</span>
        <div class="category-title">'. $title .'</div>
        <div class="category-description">'. $description .'</div>
     </div>';
    echo $saved_task;
  } else {
    echo "Error: ". mysqli_error($conn);
  }
  exit();
}

/*
* DELETE category and all its subcategories from database:
*/
if (isset($_GET['delete'])) {
  $id = $_GET['id'];
  $categoriesToDelete = array($id);

  $sql = "SELECT category_id FROM user_categories WHERE parent_id='{$id}';";
  $result = mysqli_query($conn, $sql);
  $isResult = false;
  if ($result->num_rows > 0) {
   $isResult = true;
  }

  while ($isResult) {
   while ($row = mysqli_fetch_array($result)) {
     array_push($categoriesToDelete, $row['category_id']);
   }

   $previouslySearchedCategoryId = array_search($id, $categoriesToDelete);
   $id = $categoriesToDelete[$previouslySearchedCategoryId + 1];
   if ($id != NULL) {
     $sql = "SELECT category_id FROM user_categories WHERE parent_id='{$id}';";
     $result = mysqli_query($conn, $sql);
   }
   if ($id === NULL && $result->num_rows === 0) {
     $isResult = false;
   }
  }

  // tell client-side what categories to delete:
  echo json_encode($categoriesToDelete);

  // create one SQL statement for DELETE query:
  $categoryIDsToDelete = "(";
  $i = 0;
  for ($i; $i < count($categoriesToDelete); $i++) {
   if ($i != count($categoriesToDelete) - 1) {
     $categoryIDsToDelete .= $categoriesToDelete[$i] . ",";
   } else {
     $categoryIDsToDelete .= $categoriesToDelete[$i];
   }
  }
  $categoryIDsToDelete .= ");";
  $sql = "DELETE FROM user_categories WHERE category_id IN " . $categoryIDsToDelete;

  // perform DELETE:
  mysqli_query($conn, $sql);

  exit();
}

/*
* UPDATE category info:
*/
if (isset($_POST['update'])) {
  $id = $_POST['id'];
  $title = $_POST['title'];
  $description = $_POST['description'];

  $sql = "UPDATE user_categories SET title='{$title}', description='{$description}' WHERE category_id=" . $id;

  if (mysqli_query($conn, $sql)) {
    $id = mysqli_insert_id($conn);
    $offset = $_POST['nesting_lvl'] * 30;
    $margin = "0 " . (5 - $offset) . "px 0 " . (5 + $offset) . "px";

    $saved_task = '<div class="category-box" data-parent_id="' . $_POST['parent_id'] . '" data-nesting_lvl="' . $_POST['nesting_lvl'] .
      '" style="margin: ' . $margin . '">
        <span class="new" data-id="' . $id . '" >new</span>
        <span class="delete" data-id="' . $id . '" >delete</span>
        <span class="edit" data-id="' . $id . '">edit</span>
        <div class="category-title">'. $title .'</div>
        <div class="category-description">'. $description .'</div>
      </div>';
    echo $saved_task;
  } else {
    echo "Error: ". mysqli_error($conn);
  }
  exit();
}
