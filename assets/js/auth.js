var tmpContainer = 0;

// media query event handler:
if (matchMedia) {
  var mq = window.matchMedia("(min-width: 1023px)");
  mq.addListener(WidthChange);
  WidthChange(mq);
}

// media query change:
function WidthChange(mq) {

  var inputForm = document.getElementById("form-front");
  var loginForm = document.getElementsByClassName("form")[0];
  var signupForm = document.getElementsByClassName("form")[1];

  if (mq.matches) {
    // window width is at least 1024px:
    inputForm.style.top = "0";
    inputForm.style.right = "20px";
    inputForm.style.left = "";

    if (tmpContainer === 2) {
      fade(loginForm);
      setTimeout(function() { unfade(signupForm); }, 300);
    } else {
      fade(signupForm);
      setTimeout(function() { unfade(loginForm); }, 300);
    }

  } else {
    // window width is less than 1024px:
    inputForm.style.top = "380px";
    inputForm.style.left = "50%";
    inputForm.style.right = "";

    if (tmpContainer === 1) {
      fade(loginForm);
      setTimeout(function() { unfade(signupForm); }, 300);
    } else {
      fade(signupForm);
      setTimeout(function() { unfade(loginForm); }, 300);
    }
  }
}

// TOGGLE between Login and Sign Up forms:
function toggle() {
  var loginForm = document.getElementsByClassName("form")[0];
  var signupForm = document.getElementsByClassName("form")[1];
  var inputForm = document.getElementById("form-front");

  var mq = window.matchMedia("(max-width: 1023px)");

  if (loginForm.style.display === 'flex' || loginForm.style.display === "") {
    fade(loginForm);
    setTimeout(function() { unfade(signupForm); }, 300);

    if (mq.matches) {
      inputForm.style.top = "40px";
      inputForm.style.left = "";
      inputForm.style.right = "20px";
    } else {
      inputForm.style.left = "20px";
      inputForm.style.right = "";
      inputForm.style.top = "0";
    }

    tmpContainer = 2;
  } else {
      fade(signupForm);
      setTimeout(function() { unfade(loginForm); }, 300);

      if (mq.matches) {
        inputForm.style.top = "380px";
        inputForm.style.right = "";
      } else {
        inputForm.style.right = "20px";
        inputForm.style.left = "";
        inputForm.style.top = "0";
      }

      tmpContainer = 1;
  }
}

// ANIMATION of toggle:
function unfade(element) {
  var op = 0.1;
  var timer = setInterval(function () {
    element.style.opacity = op;
    element.style.filter = 'alpha(opacity=' + op * 100 + ")";
    op +=  0.2;
    if (op >= 1) {
      clearInterval(timer);
    }
    element.style.display = 'flex';
  }, 100);
}

function fade(element) {
  var op = 1;
  var timer = setInterval(function () {
    if (op <= 0.1) {
      clearInterval(timer);
      element.style.display = 'none';
    }
    element.style.opacity = op;
    element.style.filter = 'alpha(opacity=' + op * 100 + ")";
    op -= 0.2;
  }, 50);
}