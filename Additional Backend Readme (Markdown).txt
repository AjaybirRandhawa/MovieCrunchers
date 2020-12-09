# MovieCrunchersApi
API for the final project for cps530 for ryerson university

## USAGE


*The '-f' before each field is used to denote a POST field follows, & to be consistent with curl on the commandline*

---
**posts.php**  
Make a request to posts.php w/ fields:

- To get posts by movie:  
Fields are below:  
-F 'type'='getPosts'  
-F 'movie'=(Movie ID, as string)  

Eg: Will get posts for Guardians of the Galaxy Vol. 2:

``curl https://raynorelgie.com/MovieCrunchers/posts.php -F 'type'='getPosts' -F 'movie'='tt3896198'``


Here is a return when there was posts for a movie.
``{"success":true,"value":[{"id":"1","rating":"4","author":"2","text":"It was alright I suppose","date":"2020-11-15"},{"id":"2","rating":"5","author":"1","text":"It was good.","date":"2020-11-15"}]}``

- To make a post:  
Fields are below:  
-F 'type'='makePost'  
-F 'movie'=(Movie ID, as string)  
-F 'userid'=(User id, as int)   
-F 'rating'=(Rating out of 5, as int)  
-F 'text'='review text as string'  

Eg: Will make a post to Guardians of the Galaxy Vol. 2:

``curl https://raynorelgie.com/MovieCrunchers/posts.php -F 'type'='makePost' -F 'movie'='tt3896198' -F 'userid'='2' -F 'rating'=4 -F 'text'='It was alright I suppose'``

Returned json will have field success=true if the post was made.

---
**users.php**  
Find sample login/registration form at sampleLogin.php

- Registration:  
-F 'type'='register'  
-F 'pass'='(password)'  
-F 'name'='(username)'

- Login:  
-F 'type'='login'  
-F 'pass'='(password)'  
-F 'name'='(username)'

---
**movies.php**  
Not all methods present at time of this document being written.

- Get Movie's Data: **Polls database, if no movie found in DB polls OMDB and adds it to DB.**  
~~-F 'type'='getData'~~ (Field unused until another method written. Can be omitted until then)  
-F 'movie'='(Movie ID, as string)'  

Eg: Will return name, poster, release date, genre, & plot of a movie.  

``curl https://raynorelgie.com/MovieCrunchers/movies.php -F 'type'='getdata' -F 'movie'='tt3896198'``

This will return:

``{"success":true,"value":{"name":"Guardians of the Galaxy Vol. 2","posterImg":"https:\/\/m.media-amazon.com\/images\/M\/MV5BNjM0NTc0NzItM2FlYS00YzEwLWE0YmUtNTA2ZWIzODc2OTgxXkEyXkFqcGdeQXVyNTgwNzIyNzg@._V1_SX300.jpg","released":"05 May 2017","genre":"Action, Adventure, Comedy, Sci-Fi","plot":"The Guardians struggle to keep together as a team while dealing with their personal family issues, notably Star-Lord's encounter with his father the ambitious celestial being Ego.","id":"tt3896198"}}``
