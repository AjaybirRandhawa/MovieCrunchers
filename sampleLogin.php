<?php
session_start();
session_regenerate_id();
if($_SESSION["authenticated"] == True){
    echo "Authenticated: " . $_SESSION["user"];
}
?>

<html>
<head></head>

<body>
<p>Login:</p>
<form name="login" method='post' action="sampleLogin.php">
    <input type="hidden" id="type" name="type" value="login">
    <p>Username</p>
    <input type="text" id="name" name="name">
    <br>
    <p>Password</p>
    <input type="password" id="pass" name="pass">
    <br>
    <input type="submit" name="submit" value="Log in">
</form>

<p>Register:</p>
<form name="register" method='post' action="sampleLogin.php">
    <input type="hidden" id="type" name="type" value="register">
    <p>Username</p>
    <input type="text" id="name" name="name">
    <br>
    <p>Password</p>
    <input type="password" id="pass" name="pass">
    <br>
    <input type="submit" name="submit" value="Register">
</form>


</body>


</html>

<?php


if($_SERVER["REQUEST_METHOD"] == "POST"){
    $type = $_POST["type"];
    $name = $_POST["name"];
    $pass = $_POST["pass"];

    $curl = curl_init("https://raynorelgie.com/MovieCrunchers/users.php"); //Change if hosted elsewhere
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS,"type=$type&name=$name&pass=$pass");
    $response = curl_exec($curl);
    echo $response . "\n";
    $succ = json_decode($response, True)["success"];
    curl_close($curl);
    #echo $val;
    echo $succ;
    
    if($succ == 1){
        $_SESSION["authenticated"] = True;
        $_SESSION["user"] = $name;
    }
    
}

// $type = 'login';
// $name = 'raynor';
// $pass = '';

// $curl = curl_init("https://raynorelgie.com/MovieCrunchers/users.php");
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($curl, CURLOPT_POST, 1);
// curl_setopt($curl, CURLOPT_POSTFIELDS,"type=$type&name=$name&pass=$pass");
// $response = curl_exec($curl);
// echo $response . "\n";
// $succ = json_decode($response, True)["success"];
// curl_close($curl);
// #echo $val;
// echo $succ;


?>
