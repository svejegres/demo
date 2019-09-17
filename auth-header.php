<header>
  <div class="form-wrapper">
    <?php
      if (isset($_SESSION['Error'])) {
        echo '<div class="validation-err-msg">' . $_SESSION['Error'] . '</div>';
        unset($_SESSION['Error']);
      }
    ?>
    <div id="form-back">
      <div class="alt-form signup">
        <h1>
          Don't have an account?
        </h1>
        <p>
          Register for free in a simple and fast way
          to create your own trees of categories
          from your personal page.
        </p>
        <button onclick="toggle();">
          Sign up
        </button>
      </div>
      <div class="alt-form login">
        <h1>
          Have an account?
        </h1>
        <p>
          Use your username and password
          to access your own trees of categories
          from your personal page.
        </p>
        <button onclick="toggle();">
          Login
        </button>
      </div>
    </div>
    <div id="form-front">
      <form method="post" class="form login">
        <h1>
          Login
        </h1>
        <input type="text" name="name" required="required">
        <label for="name">
          Username
        </label>
        <div id="name-icon"></div>
        <input type="password" name="pwd" required="required">
        <label for="pwd">
          Password
        </label>
        <div id="password-icon"></div>
        <button type="submit" name="login">
          Log in
        </button>
        <a href="#">
          Forgot?
        </a>
      </form>
      <form method="post" class="form signup">
        <h1>
          Sign Up
        </h1>
        <input type="text" name="name" required="required">
        <label for="name">
          Username
        </label>
        <div id="name-icon"></div>
        <input type="password" name="pwd" required="required">
        <label for="pwd">
          Password
        </label>
        <div id="password-icon"></div>
        <button type="submit" name="signup">
          Sign up
        </button>
      </form>
      <div id="upperfold"></div>
      <div id="lowerfold"></div>
    </div>
  </div>
</header>