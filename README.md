# MovieCrunchers
Final project done in collaboration with 3 others.

## Goal
Welcome to the newly created MovieCrunchers, the world’s preeminent destination for movie criticism, commentary and community.
        MovieCrunchers is currently one of the latest movie reviewing sites that are available on the internet. Since most of the 
        internet users around the world are using online services to watch movies online, we inspired ourselves to make a place
        that allows you to review the latest movies you've watched. If you have ever felt the need for reviewing movies and TV series, 
        here your search comes to an end. As an up and coming website, we're constantly looking forward to working with our userbase and
        helping our users feel more welcomed.

## API Documentation
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

- Get Movie's Data: **Polls database, if no movie found in DB polls OMDB and adds it to DB.**  
-F 'type'='getData' (Field unused, may be used if movies.php API expanded Can be omitted until then)  
-F 'movie'='(Movie ID, as string)'  

Eg: Will return name, poster, release date, genre, & plot of a movie:  

``curl https://raynorelgie.com/MovieCrunchers/movies.php -F 'type'='getdata' -F 'movie'='tt3896198'``

This will return:

``{"success":true,"value":{"name":"Guardians of the Galaxy Vol. 2","posterImg":"https:\/\/m.media-amazon.com\/images\/M\/MV5BNjM0NTc0NzItM2FlYS00YzEwLWE0YmUtNTA2ZWIzODc2OTgxXkEyXkFqcGdeQXVyNTgwNzIyNzg@._V1_SX300.jpg","released":"05 May 2017","genre":"Action, Adventure, Comedy, Sci-Fi","plot":"The Guardians struggle to keep together as a team while dealing with their personal family issues, notably Star-Lord's encounter with his father the ambitious celestial being Ego.","id":"tt3896198"}}``



# Authors
This is a project developed by [Ajaybir Randhawa](https://github.com/AjaybirRandhawa), [Raynor Elgie](https://raynorelgie.com/),[Finn Huynh](https://www.linkedin.com/in/finn-huynh/), and [Maximillian Bellevile](https://www.linkedin.com/in/belleville-max/). We hope you check out our site at: https://raynorelgie.com/MovieCrunchers/
