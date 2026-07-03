export function fetchCoverM() {

    const divBookCovers = document.getElementsByClassName("div-book-cover");

    for (let divBookCover of divBookCovers) {

        let coverId = divBookCover.querySelector(".cover-id");
        let coverIdContent = coverId.innerHTML;
        let coverImg = document.createElement("img");
        coverImg.className = "cover-detail-book";

        let url_cover = "https://covers.openlibrary.org/b/id/" + coverIdContent + "-M.jpg";

        fetch(url_cover)
            .then(response => response.blob())
            .then(blob => {
                coverImg.src = URL.createObjectURL(blob);
            })
            .catch(error => console.error("Error fetching dynamic image:", error));

            divBookCover.appendChild(coverImg);
    }
}




