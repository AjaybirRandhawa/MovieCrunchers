
<?php


$dbName = "relgie";//REPLACE WITH YOUR DB INFO (& other occurences of "relgie")
$dbPass = "XXXXx";

$mysqli = mysqli_connect("localhost", $dbName, $dbPass, "relgie")   //Establish connection to DB
or die(mysql_error());

#$query = mysqli_query($mysqli, "CREATE TABLE Users (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(30) NOT NULL, hash VARCHAR(255) NOT NULL);");


function getID($name){ //Get user's ID based on name
    global $dbName, $dbPass;
    
    $mysqli = mysqli_connect("localhost", $dbName, $dbPass, "relgie") 
    or die(mysql_error());
    
    $query = mysqli_query($mysqli, "SELECT id FROM Users WHERE name='$name' "); //Query matching info
    echo mysqli_error($mysqli);
    if (mysqli_num_rows($query) > 0) { //If there's 1+ results
        return [True, mysqli_fetch_assoc($query)["id"]]; //Return array w/ polled data & success value
    } else {
        return [False, "No user"];
    }
    
}

function getName($id){ //Get user's name based on ID
    global $dbName, $dbPass;
    $mysqli = mysqli_connect("localhost", $dbName, $dbPass, "relgie") 
    or die(mysql_error());
    $query = mysqli_query($mysqli, "SELECT name FROM Users WHERE id='$id' ");
    
    if (mysqli_num_rows($query) > 0) {
        return [True, mysqli_fetch_assoc($query)["name"]];
    } else {
        return [False, "No user"];
    }
}


function isHashEqual($id, $hash){ //Is hash given = to hash on record?
    global $dbName, $dbPass;
    $mysqli = mysqli_connect("localhost", $dbName, $dbPass, "relgie") 
    or die(mysql_error());
    $query = mysqli_query($mysqli, "SELECT hash FROM Users WHERE id='$id' ");
    if (mysqli_num_rows($query) > 0) {
        if(mysqli_fetch_assoc($query)["hash"]==$hash){
            return [True, "$id"];
        }else {
            return [False, "Doesn't match"];
        }
    } else {
        return [False, "No user"];
    }
}

function isPassCorrect($request){ //Given name and pass, validates it against the has in the database
    $name = $request["name"];
    $pass = $request["pass"];
    global $dbName, $dbPass;
    $mysqli = mysqli_connect("localhost", $dbName, $dbPass, "relgie") 
    or die(mysql_error());
    $query = mysqli_query($mysqli, "SELECT hash FROM Users WHERE name='$name' ");
    
    if (mysqli_num_rows($query) > 0) {
        if(password_verify($pass,mysqli_fetch_assoc($query)["hash"])){
            return [True, "$name"];
        }else {
            return [False, "Doesn't match"];
        }
    } else {
        return [False, "No user"];
    }
}

/* echo getName(0)[0] . " " . getName(0)[1];
echo isHashEqual(0,1)[0] . " " . isHashEqual(0,1)[1]; */
#Code below handles taking POST data and echoing the JSON data
function echoJson($request){ //Picks function & returns json
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json; charset=utf-8');
    if($request["type"]=="name"){
        $data["success"] = getName($request["param"])[0];
        $data["value"] = getName($request["param"])[1];
    }elseif($request["type"]=="hash"){
        $data["success"] = isHashEqual($request["param"])[0];
        $data["value"] = isHashEqual($request["param"])[1];
    }elseif($request["type"]=="login"){
        $data["success"] = isPassCorrect($request)[0];
        $data["value"] = isPassCorrect($request)[1];
    }elseif($request["type"]=="id"){
        $data["success"] = getID($request["param"])[0];
        $data["value"] = getID($request["param"])[1];
    }elseif($request["type"]=="register"){
        $return = register($request);
        $data["success"] = $return[0];
        $data["value"] = $return[1];
    }

    $json_response = json_encode($data);
    echo $json_response;

}

function register($request){
    global $dbName, $dbPass;
    $mysqli = mysqli_connect("localhost", $dbName, $dbPass, "relgie") 
    or die(mysql_error());

    #Validate
    if((getID($request["name"])[0]) || (strlen($request["name"]) <= 2)){
        return [False, "Name unavailable."];
    }elseif(strlen($request["pass"])<6 ){
        return [False, "Password must be longer than 6 characters."];
    }else{ #Validated
        $name = $request["name"];
        $hash = password_hash($request["pass"], PASSWORD_DEFAULT);
        $query = mysqli_query($mysqli, "INSERT INTO Users (name, hash) VALUES ('$name', '$hash');");
        return [True, $request["name"]];
    }

}


//$req["type"] = "register";
//$req["name"] = "Rayuser3";
//$req["pass"] = "abcdef";
//echoJson($req);

// $req["type"] = "password";
// $req["id"] = 3;
// $req["pass"] = "abcdef";
// echoJson($req);

if($_SERVER["REQUEST_METHOD"] == "POST"){
    echoJson($_POST);
}

?>
