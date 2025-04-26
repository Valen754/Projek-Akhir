document.addEventListener("DOMContentLoaded", function () {
    var coll = document.getElementsByClassName("collapsible");
    for (var i = 0; i < coll.length; i++) {
        coll[i].addEventListener("click", function () {
            this.classList.toggle("active");
            var content = this.querySelector(".content");
            if (content.style.display === "block") {
                content.style.display = "none";
            } else {
                content.style.display = "block";
            }
        });
    }
});

// SEARCH JADI NAVBAR
document.addEventListener("DOMContentLoaded", function() {
    const searchBox = document.querySelector(".search-box");
    const mainContent = document.querySelector(".main-content");
    const searchBoxOffsetTop = searchBox.offsetTop;

    window.addEventListener("scroll", function() {
        if (window.pageYOffset > searchBoxOffsetTop) {
            searchBox.classList.add("fixed");
            mainContent.classList.add("fixed-padding");
        } else {
            searchBox.classList.remove("fixed");
            mainContent.classList.remove("fixed-padding");
        }
    });
});