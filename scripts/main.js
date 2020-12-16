/* On ready check if user is signed in via session token, change options accordingly, update header */
$(document).ready(() => {
    if (!token) options = {"accountOn": "collapse"}
    else {
        var user = sessionStorage.getItem("I" + token);
        options = {"accountOff": "collapse","ID": user}
    }
    if ($("#no-banner").length) options["noBanner"] = "collapse";
    appendComponent("components/header.html", "header", options,call=>{$("#searchMobile").hide()})
    readParams(options)
});
/* Get token and url parameters */
let params = new URLSearchParams(location.search);
var token = sessionStorage.getItem("Token")

/* Shows and hides certain elements for the search bar on mobile */
function searchMobile(self){
    if(self.value!="\uf00d"){
        $("#brand").hide()
        $("#searchMobile").show()
        $("#search2").focus()
        self.value='\uf00d'
    }
    else {
        self.value='\uf002'
        $("#brand").show()
        $("#searchMobile").hide()  
    } 
}
/* Focused on the current search bar for the button on the splash page */
function focusOnSearch(){
    if($("#searchMobile").is(':visible')) $("#search2").focus()
    else $("#search").focus()
}

/* Reads every parameter found in the url parameter */
function readParams(options) {
    //If parameter s(for search) exists do search
    if (params.has("s")) {
        $("#searchTitle").text("SEARCH: " + params.get("s").toUpperCase());
        requests.search(params.get("s"));
    }
    //If parameter u(for user account) exists get reviews
    if(params.has("u")) requests.userReviews(params.get("u"))

    //Set setting for if made/currently working on the review.
    var collapse = "collapse"
    if (params.has("ru")) {
        if (params.get("ru") == options.ID) collapse = "-"
    }
    //If parameter t(for movies omdb uses t) exists request movie and read reviews for that movie
    if (params.has("t")) {
        requests.movie(params.get("t"), options, "collapse", "collapse",call=>{
            requests.readReviews(params.get("t"));
        });
    }
    //If parameter r(for reviews) exists request movie read review and update accordingly
    if (params.has("r")) {
        requests.movie(params.get("r"), "collapse", "-", collapse,call=>{
            requests.readReview(params.get("r"),params.get("ru"),el=>{
                $("#reviewTextEdit").val(el.text)
                $("#reviewText").val(el.text)
                updateSize("#reviewText")
                $updateSize("#reviewTextEdit")
                if(el.rating==5) $("#notFav").attr("id","fav");
                else $("#fav").attr("id","notFav");
            })
        });
    }
}

/* Update the size of the review box */
function updateSize(elem) {
    elem.style.height = "";
    elem.style.height = Math.min(elem.scrollHeight, 800) + "px"
    reviewCut(elem)
}

/* Recursive function that ensures that the review fits under a certain line limit */
function reviewCut(elem) {
    if (elem.scrollHeight > 900)
        elem.value = elem.value.slice(0, -100)
    else if (elem.scrollHeight > 800)
        elem.value = elem.value.slice(0, -1)
    if (elem.scrollHeight > 800)
        reviewCut(elem)
}

/* Called when you hit the submit button double checks that you are the creator of the post
    Then makes request to post review. */
function postReview() {
    if (params.has("ru")) {
        if (params.get("ru") == sessionStorage.getItem("I" + token)) {
            var text = $("#reviewTextEdit").val()
            var rating = ($("#fav").length == 1) ? 5 : 1
            requests.postReview(text, rating,params.get("r"),params.get("ru"));
        }
    }

}

function addFav(elem) {
    if (elem.id == "fav") elem.id = "notFav";
    else elem.id = "fav";
}

function clearSession() {
    sessionStorage.clear()
}

/* Takes a html path gets the html code, replaces key words with predefined options
    Then appends to a given id, optional callback provided */
function appendComponent(url, id, replaceParams,callback=null) {
    $.get(url, (data) => {
        for (var k in replaceParams)
            data = data.replaceAll(k, replaceParams[k]);
        $(id).append(data);
        if(callback)callback()
    })
}
/* Pageination for scrolling once at bottom of search page call search again */

$(window).scroll(function () {
    if (requests.searchResults > 0 && params.get("s")) {
        if ($(window).scrollTop() + $(window).height() == $(document).height()) {
            requests.search(params.get("s"));
        }
    }
});
