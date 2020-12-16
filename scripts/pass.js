/*Check for login */
function check() {
    var u = $("#user").val();
    var p = $("#pass").val();
    requests.account("login", u, p, data => {
        if (data == "No user") return displayError("#user", "No user found");
        if (data == "Doesn't match") return displayError("#user", "Bad Password");
        setSession(u,true)
    });
}

/*Creates a random unique token for each sign in, to access user and id information
   you have to read that token this extra is to try and make it harder to access someones
   account information by editing their session information*/
function setSession(u,isLogin) {
    requests.getID(u, id => {
        sessionStorage.setItem("Token", Date.now())
        sessionStorage.setItem("U"+Date.now(), u)
        sessionStorage.setItem("I"+Date.now(), id)
        if(isLogin) history.back();
        else location.replace("./");
    })
}

function displayError(tag, message) {
    $("#error").text(message);
    if (screen.width > 1024) $(tag).css({
        "margin-top": "1%"
    })
    else $(tag).css({
        "margin-top": "0"
    })
    return false;
}

function checkEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

/*Checks if email not valid, checks if passwords match and if user already exists */
function register() {
    var e = $("#email").val();
    if (!checkEmail(e)) return displayError("#email", "Email not valid")

    var u = $("#user").val();
    var p = $("#pass").val();
    var v = $("#verify").val();
    if (p == v) {
        requests.account("register", u, p, data => {
            if (data == "Name unavailable.") return displayError("#email", "Username exists");
            setSession(u,false)
        })
    } else return displayError("#email", "Not matching")
}