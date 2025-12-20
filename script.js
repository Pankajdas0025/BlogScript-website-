
// to open login up box ...........................................................................................
document.getElementById("lbtn").addEventListener("click",openSignIn);
function openSignIn()
{

    var a=document.querySelector(".box2");
    var b=document.querySelector(".box");
    a.style.display="block";


}

document.getElementById("Sibtn").addEventListener("click",openSignUp);
// to open sign up box ...........................................................................................
function openSignUp()
{

    var a=document.querySelector(".box2");
    var b=document.querySelector(".box");
    a.style.display="none";


}

//client side form validation .........................................................................................//
// Check Name
function checkName() {
  var Name = document.querySelector("#uName");
  if (Name.value.trim() === "") {
    alert("Name should be filled");
    return false;
  } else if (/\d/.test(Name.value)) {
    alert("Numbers are not allowed in the name");
    Name.value = "";
    return false;
  }
  return true;
}

// Check Email
function checkEmail() {
  var Email = document.querySelector("#uEmail");

  if (Email.value.trim() === "") {
    alert("Email should be filled");
    return false;
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(Email.value)) {
    alert("Enter a valid email address");
    Email.value = "";
    return false;
  }
  return true;
}

// Check Password
function checkPass() {
  var Pass = document.querySelector("#uPass");

  if (Pass.value.trim() === "") {
    alert("Password should be filled");
    return false;
  } else if (Pass.value.length < 6) {
    alert("Password length should be at least 6 characters");
    Pass.value = "";
    return false;
  }
  return true;
}
