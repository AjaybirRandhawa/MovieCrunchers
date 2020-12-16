/*This is a little more complex so I will try and split it up more */
let requests = {
        //First we have some simple api endpoints.
        omdbApi: "https://www.omdbapi.com?apikey=2f97c78a&",
        movieEndpoint: "https://raynorelgie.com/MovieCrunchers/movies.php",
        userEndpoint: "https://raynorelgie.com/MovieCrunchers/users.php",
        postEndpoint: "https://raynorelgie.com/MovieCrunchers/posts.php",
        //Then we have the searchPage(which increases on each new search call)
        //and searchResults(which stores the current number of search results)
        searchResults: 0,
        searchPage: 1,
        //Search calls the omdbApi to get searches, then loops through each search and appends the items.
        search: function (searchStr) {
            $.get(this.omdbApi + "s=" + searchStr + "&page=" + this.searchPage, (data) => {
                if (this.searchPage == 1) this.searchResults = Math.max(0, data.totalResults - 10);
                data.Search.forEach(el => {
                    appendComponent("components/search-items.html", "#searches", {
                        "SOURCE": el.Poster,
                        "TITLE": el.Title,
                        "ID": el.imdbID,
                        "TYPE": el.Type.toUpperCase(),
                        "DATE": el.Year
                    })
                });
                this.searchPage++;
            });
        },
        //Gets options, post request to movie endpoint, append using options we got.
        movie: function (movieStr, option, review, editReview, callback = null) {
            var id = option.ID ? option.ID : "-"
            var addc = (review == "collapse" && option.accountOn != "collapse") ? "-" : "collapse"
            var getReviews = (review == "collapse") ? "-" : "collapse"
            var showReview = (editReview == "collapse") ? "-" : "collapse"
            $.post(this.movieEndpoint, { movie: movieStr}, data => {
                if (data.success) {
                    el = data.value;
                    appendComponent("components/movies-items.html", "#movieData", {
                        "SOURCE": el.posterImg,
                        "TITLE": el.name,
                        "GENRE": el.genre,
                        "DATE": el.released,
                        "PLOT": el.plot,
                        "ID": movieStr,
                        "USER": id,
                        "ADDC": addc,
                        "GETR": getReviews,
                        "REVIEW": review,
                        "EDITR": editReview,
                        "SHOWR": showReview
                    }, call => { if (callback) callback()})
                }
            })
        },
        getID: function (user, callback) {
            $.post(this.userEndpoint, {type: "id",param: user}, data => callback(data.value))
        },
        account: function (type, user, pass, callback) {
            $.post(this.userEndpoint, {type: type,pass: pass,name: user}, data => callback(data.value))
        },
        postReview: function (text, rating, movieId, userId) {
            $.post(this.postEndpoint, 
                {type: "makePost",movie: movieId,userid: userId,rating: rating,text: text}, data => {
                if (data.success) location.replace("account.html?u=" + userId)
            });
        },
        readReview: function (movieId, userId, callback) {
            $.post(this.postEndpoint, {type: "getPosts", movie: movieId}, data => {
                if (data.success) {
                    data.value.forEach(el => {
                        if (el.author == userId) callback(el)
                    })
                }
            })
        },
        //Post request to post endpoint, loop through each review, find the unique reviews(list 1 per user)
        readReviews: function (movieId) {
            $.post(this.postEndpoint, { type: "getPosts", movie: movieId }, data => {
                if (data.success) {
                    var count = 0
                    var users=[]
                    data.value.forEach(el => {
                        $.post(this.userEndpoint, { type: "name", param: el.author }, userEl => {
                            var currEl = data.value[count]
                            var foundUser=false
                            users.forEach(user=>{
                                if (userEl.value==user) foundUser=true
                            })
                            if(!foundUser){
                            appendComponent("components/review-items.html", "#reviews", {
                                "CODE": movieId,
                                "ID": currEl.author,
                                "NAME": userEl.value
                            })
                            users.push(userEl.value)
                            }
                            count++;
                        })
                     })
                }
            })
        },
        //Make request to post, loop through add to dictonary, loop through dictonary
        //Make request to movie for each review/post get the poster and append poster to favs or other id.
        userReviews: function (userId) {
            $("#recText").hide()
            $("#favText").hide()
            $.post(this.postEndpoint, {type: "byUser",user: userId}, data => {
                if (data.success) {
                    var foundMovie = {}
                    data.value.forEach(el => {if (el.movie != "") foundMovie[el.movie] = el});
                    for (var key in foundMovie) {
                        $.post(this.movieEndpoint, {movie: key}, movieEl => {
                            var el = movieEl.value
                            if (foundMovie[el.id].rating == 5) {
                                appendComponent("components/poster-items.html", "#favs", {
                                    "SOURCE": el.posterImg,
                                    "CODE": el.id,
                                    "ID": userId
                                });
                                $("#favText").show()
                            } else {
                                appendComponent("components/poster-items.html", "#other", {
                                    "SOURCE": el.posterImg,
                                    "CODE": el.id,
                                    "ID": userId
                                });
                                $("#recText").show()
                            }
                        });
                    }
                }
            })
        }
}