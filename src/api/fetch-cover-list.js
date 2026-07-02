function fetchCoverM() {
    let coverId = document.getElementById("cover-id").innerHTML;

    let coverImg = document.createElement("img");
    coverImg.className = "cover-detail-book";

    if (coverId) {
        let url_cover = "https://covers.openlibrary.org/b/id/" + coverId + "-M.jpg";

        fetch(url_cover)
            .then(response => response.blob())
            .then(blob => {
                coverImg.src = URL.createObjectURL(blob);
            })
            .catch(error => console.error("Error fetching dynamic image:", error));
    }
    let divBookCover = document.getElementsByClassName("div-book-cover");

    for (let div of divBookCover) {
        div.appendChild(coverImg);
    }
}