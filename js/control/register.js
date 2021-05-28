
function register(){
    var fnameE = document.getElementById("Fname");
    var lnameE = document.getElementById("Lname");
    var usernameE = document.getElementById("username");
    var passE = document.getElementById("password");
    var emailE = document.getElementById("email");
    var repassE = document.getElementById("repassword");

    var fname = fnameE.value;
    var lname = lnameE.value;
    var username = usernameE.value;
    var password = passE.value;
    var email =emailE.value;
    var repassword = repassE.value;

    if (checkInfo(fname, lname, username, password, email, repassword)){
        // console.log("Success");
        fetch(API_ADMIN_REGISTER, {
        method: 'POST', // or 'PUT'
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            name: {
              FName: fname,
              LName: lname
            },
            username: username,
            password: password,
            email: email
          }),
    })
        .then(response => response.json())
        .then(data => {
            if (data._id != null) {
                alert("Create account successfully");
                window.location.href = "login.html";
            }
            else{
                console.log(data);
                fnameE.value = '';
                lnameE.value = '';
                usernameE.value = '';
                passE.value = '';
                repassE.value = '';
                emailE.value = '';
                alert("Failed to create account");
            } 
        })
        .catch((error) => {
            // console.log(error.response);
        });
    }
    else{
        console.log("Signup failed");
    }
    
}

function checkInfo(fname, lname, user, pass, email, repass){
    if(fname.length == 0 || 
        lname.length == 0 || 
        user.length  == 0 || 
        pass.length < 6 ||
        email.length < 2 ||
        pass != repass)
        return false;
    return true;
}