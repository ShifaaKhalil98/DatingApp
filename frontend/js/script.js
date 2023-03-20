const goto_login_btn = document.getElementById("goto_login");
const goto_signup_btn = document.getElementById("goto_signup");
const login_form = document.getElementById("login_form");
const signup_form = document.getElementById("signup_form");
const login_btn = document.getElementById("login");
const signup_btn = document.getElementById("signup");

signup_form.style.display = "none";

goto_login_btn.addEventListener("click", goto_login);
goto_signup_btn.addEventListener("click", goto_signup);
login_btn.addEventListener("click", login);
signup_btn.addEventListener("click", signup);

function goto_login() {
  login_form.style.display = "block";
  signup_form.style.display = "none";
}
function goto_signup() {
  signup_form.style.display = "block";
  login_form.style.display = "none";
}
function login(event) {
  event.preventDefault();

  const email = document.getElementById("email").value;
  const password = document.getElementById("password").value;

  axios
    .post("http://127.0.0.1:8000/api/login", {
      email: email,
      password: password,
    })
    .then((response) => {
      console.log(response);
      if ((response.data.status = "success")) {
        alert("logged in successfully");
        window.location.href = "./main.html";
      }
    })
    .catch((error) => {
      console.error(error);
    });
}
function signup(event) {
  event.preventDefault();

  const name = document.getElementById("name").value;
  const dob = document.getElementById("dob").value;
  const email = document.getElementById("email").value;
  const password = document.getElementById("password").value;
  const confirm_password = document.getElementById("confirm_password").value;

  axios
    .post("http://127.0.0.1:8000/api/register", {
      name: name,
      dob: dob,
      email: email,
      password: password,
      confirm_password: confirm_password,
    })
    .then((response) => {
      console.log(response);
      if ((response.data.status = "success")) {
        // alert(response.data.message);
        alert("welcome");
        console.log(response);
        window.location.href = "./main.html";
      }
    })
    .catch((error) => {
      console.error(error);
    });
}
