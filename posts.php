<?php


$mysqli = mysqli_connect("localhost", "root", $dbPass, "relgie")
or die(mysql_error());

#$query = mysqli_query($mysqli, "create table Posts(id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, rating TINYINT, author INT(6), text MEDIUMTEXT, date DATE, movie VARCHAR(20));");

function getPostsByMovie($req){ #LOOKS FOR REVIEWS IN THE DATABASE, $req["movie"] HOLDS TITLE ID. RETURNS IN ASSOC. ARRAY.
    global $dbName, $dbPass;

    $mysqli = mysqli_connect("localhost", "root", $dbPass, "relgie")
    or die(mysql_error());
    $movie = $req["movie"];
    $query = mysqli_query($mysqli, "SELECT id, rating, author, text, date FROM Posts WHERE movie='$movie' ");
    echo mysqli_error($mysqli);
    if (mysqli_num_rows($query) > 0) {

        $list = [];
        while($result = mysqli_fetch_assoc($query)){
            $arr = ["id" => $result["id"], "rating" => $result["rating"], "author" => $result["author"], "text" => $result["text"], "date" => $result["date"]];
            array_push($list, $arr);
            #echo "Id: " . $arr["id"] . "\n";
        }
        return [True, $list];
    } else {
        return [False, "No posts"];
    }

}

function getPostsByUser($req){ #LOOKS FOR USER'S REVIEWS IN THE DATABASE, $req["user"] HOLDS AUTHOR. RETURNS IN ASSOC. ARRAY.
        global $dbName, $dbPass;

        $mysqli = mysqli_connect("localhost", "root", $dbPass, "[Redacted]")
        or die(mysql_error());
        $user = $req["user"];

        $query = mysqli_query($mysqli, "SELECT id, rating, author, text, date, movie FROM Posts WHERE author='$user' ");
        if (mysqli_num_rows($query) > 0){
                $list = [];
                while($result = mysqli_fetch_assoc($query)){
                        $arr = ["id" => $result["id"], "rating" => $result["rating"], "author" => $result["author"], "text" => $result["text"], "date" => $result["date"], , "movie" => $result["movie"]];
                        array_push($list, $arr);
                }
                return [True, $list];
        }else{
                return [False, "No posts"];
        }


}

function addPostToMovie($req){ #ADDS A REVIEW TO DATABASE, GIVEN TITLE ID, USERID, RATING, REVIEW TEXT.
    global $dbName, $dbPass;

    $mysqli = mysqli_connect("localhost", "root", $dbPass, "[Redacted]")
    or die(mysql_error());

    $movie = $req["movie"];
    $user = $req["userid"];
    $rating = $req["rating"];
    $text = $req["text"];
    $query = mysqli_query($mysqli, "INSERT INTO Posts (rating, author, text, date, movie) VALUES ($rating, $user, '$text', NOW(), '$movie');");

    return [True, $movie];

}

function echoJson($req){ #SENDS POST DATA TO METHOD, GETS BACK JSON & PRINTS IT
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json; charset=utf-8');
        if($req["type"]=="getPosts"){
                $return = getPostsByMovie($req);
                $data["success"] = $return[0];
                $data["value"] = $return[1];
        }
        if($req["type"]=="makePost"){
                $a = addPostToMovie($req);
                $data["success"] = $a[0];
                $data["value"] = $a[1];
        }
        echo json_encode($data);
}

 $req["movie"] = "[Redacted]";
# echoJson($req);

//$req["userid"] = 1;
//$req["rating"] = 3;
//$req["text"] = "I forget watching it, honestly.";
//addPostToMovie($req);

if($_SERVER["REQUEST_METHOD"] == "POST"){ #UPON POST DATA, ECHO JSON DATA
    echoJson($_POST);
}

?>
