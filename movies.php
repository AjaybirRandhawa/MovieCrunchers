<?php


$dbName = "relgie"; 
$dbPass = "X"; //Change pass to yours

$mysqli = mysqli_connect("localhost", "root", $dbPass, "relgie") //Change root to your username, relgie to your db name. Also do this in the other connections.
or die(mysql_error());

#$query = mysqli_query($mysqli, "create table Movies(name VARCHAR(70), posterImg varchar(200), released VARCHAR(20), genre VARCHAR(40), plot MEDIUMTEXT, id VARCHAR(15));");

function getOrGenMovieData($req){ #THIS FUNCTION TAKES AN IMDB MOVIE ID, AND WILL RETURN DATA ABOUT IT IN JSON. IF WE DONT HAVE IT, IT PUTS IT IN THE DATABASE.
    $movie = $req["movie"];
    global $dbName;
    global $dbPass;

    $mysqli = mysqli_connect("localhost", "root", $dbPass, "relgie")
    or die(mysql_error());

    $query = mysqli_query($mysqli, "SELECT name, posterImg, released, genre, plot, id FROM Movies WHERE id='$movie' ");

    if (mysqli_num_rows($query) > 0) {
        
        $result = mysqli_fetch_assoc($query);

        return [True, $result];

    } else {
        #Find from OMBDB

        $curl = curl_init("http://www.omdbapi.com/?i=$movie&apikey=2f97c78a");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);   
        curl_close($curl);
        $response = json_decode($response, true);
        $data["name"] = $response["Title"];
        $data["posterImg"] = $response["Poster"];
        $data["released"] = $response["Released"];
        $data["genre"] = $response["Genre"];
        $data["plot"] = $response["Plot"];
        $data["id"] = $movie;


        $name = $data["name"];
        $posterImg = $data["posterImg"];
        $released = $data["released"];
        $genre =  $data["genre"];
        $plot = $data["plot"];
        $id = $data["id"];
        
    
        mysqli_query($mysqli, "INSERT INTO Movies(name,posterImg,released,genre,plot,id) VALUES ('$name', '$posterImg', '$released', '$genre', '$plot', '$id');");


        return [True, $data];
        
    }

}

function echoJson($req){ #CONVERT ASSOC. ARRAY INTO JSON.
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json; charset=utf-8');
    $data["success"] = getOrGenMovieData($req)[0];
    $data["value"] = getOrGenMovieData($req)[1];
    echo json_encode($data);
}

#$req["movie"] = "tt3896198";
#echoJson($req);


if($_SERVER["REQUEST_METHOD"] == "POST"){ #UPON POST REQUEST, RETURN DATA
    echoJson($_POST);
}

?>
