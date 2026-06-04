window.addEventListener("DOMContentLoaded", () => {

    let coverId = document.getElementById("cover-id").innerHTML;
    console.log(coverId);
    let coverImg = document.createElement("img");

    if (coverId) {
        let url_cover = "https://covers.openlibrary.org/b/id/" + coverId + "-L.jpg";

        fetch(url_cover)
            .then(response => response.blob())

            //réviser blob
            .then(blob => {
                coverImg.src = URL.createObjectURL(blob);
            })
            .catch(error => console.error("Error fetching dynamic image:", error));
    }
     let containerBookCover = document.getElementsByClassName("container-book-cover");

    for (let container of containerBookCover) {
        container.appendChild(coverImg);
    }

    let

})