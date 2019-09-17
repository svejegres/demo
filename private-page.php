<?php

include('app/db-connection.php');

session_start();
// GET categories from database
if (isset($_SESSION['Username'])) {
  $username = $_SESSION['Username'];

  $sql = "SELECT category_id, title, description, parent_id, nesting_lvl FROM user_categories WHERE username='{$username}';";
  $result = mysqli_query($conn, $sql);
  $sortedResult = array();
  while ($row = mysqli_fetch_array($result)) {
    // sort result to place subcategories after right categories
    if($row["parent_id"] === "0") {
      array_push(
        $sortedResult,
        array(
          "category_id" => $row['category_id'],
          "parent_id"   => $row['parent_id'],
          "title"       => $row['title'],
          "description" => $row['description'],
          "nesting_lvl" => $row['nesting_lvl'],
        )
      );
    } else {
      $pos = array_search($row['parent_id'], array_column($sortedResult, 'category_id'));
      array_splice($sortedResult, $pos+1, 0, array(
        array(
          "category_id" => $row['category_id'],
          "parent_id"   => $row['parent_id'],
          "title"       => $row['title'],
          "description" => $row['description'],
          "nesting_lvl" => $row['nesting_lvl'],
        )
      ));
    }
  }

  // render categories and subcategories in the right order
  $categories = '<div id="display-area">';
  foreach ($sortedResult as $row) {
    $categories .= '<div class="category-box" data-parent_id="' . $row['parent_id'] . '" data-nesting_lvl="' . $row['nesting_lvl'] . '">
        <span class="new" data-id="' . $row['category_id'] . '" >new</span>
  		  <span class="delete" data-id="' . $row['category_id'] . '" >delete</span>
  		  <span class="edit" data-id="' . $row['category_id'] . '">edit</span>
  		  <div class="category-title">'. $row['title'] .'</div>
  		  <div class="category-description">'. $row['description'] .'</div>
  	  </div>';
  }
  $categories .= '</div>';
}

?>

<!DOCTYPE html>
<html lang="en">
<?php include('head.html'); ?>
<body>
  <?php
    session_start();
    if (isset($_SESSION['Username'])) {
      echo '<nav><div class="new-root-category">Create new <span>root</span> category: <button class="plus-sign"></button></div>' .
      'Hello, ' . $_SESSION['Username'] .
      '!<button class="logout-btn">Log Out</button></nav>';
    }
  ?>

  <div id="category-modal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>

      <form class="category-form">
        <div>
          <label for="title">Title:</label>
          <input type="text" name="title" id="title">
        </div>
        <div>
          <label for="description">Description:</label>
          <textarea name="description" id="description" cols="30" rows="3"></textarea>
        </div>
        <button type="button" id="submit_btn">CREATE</button>
        <button type="button" id="update_btn" style="display: none;">UPDATE</button>
      </form>
    </div>
  </div>

  <div class="categories-wrapper">
    <?php echo $categories; ?>
  </div>

  <!-- Javascript -->
  <script src="assets/js/jquery-3.3.1.min.js"></script>
  <script src="assets/js/private-page.js"></script>
</body>
</html>