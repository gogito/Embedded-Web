function login() {
  var userE = document.getElementById("username");
  var passE = document.getElementById("password");
  var user = userE.value;
  var pass = passE.value;
  //   console.log("Click login button");

  fetch("http://bkparking.ddns.net:3002/admin_login", {
    method: "POST", // or 'PUT'
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ username: user, password: pass }),
  })
    .then((response) => response.json())
    .then((data) => {
      // window.location.href = "index.html"
      if (data._id != null) {
        console.log("Success");
        window.location.href = "index.php";
        document.cookie = JSON.stringify(data);
      } else {
        console.log(data);
        userE.value = "";
        passE.value = "";
        alert("Wrong username or password");
      }
    })
    .catch((error) => {
      // console.log(error.response);
    });
}

