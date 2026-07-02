window.addEventListener("DOMContentLoaded", () => {

    function fetchCoverL() {
        let coverId = document.getElementById("cover-id").innerHTML;

        let coverImg = document.createElement("img");
        coverImg.className = "cover-detail-book";

        if (coverId) {
            let url_cover = "https://covers.openlibrary.org/b/id/" + coverId + "-L.jpg";

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

    function addToList() {

        let addButton = document.getElementById("button-add-to-list");
            addButton.addEventListener("click", (event) => {
                fetch("index.php?route=add-to-list")
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        if (!data.success) {
                            document.getElementById("error-container").innerText = data.message;
                        }
                    })
                    .catch(error => {
                    })
            })
    }

    fetchCoverL();
    addToList();

})