
export function fetchCoverL() {
    const divDetailBookCovers = document.getElementsByClassName("div-detail-book-cover");

    for (let divDetailBookCover of divDetailBookCovers) {

        let coverId = divDetailBookCover.querySelector(".cover-id-detail-book");
        if (!coverId) {
            console.warn("Error : cover-id not found");
            continue;
        }

        let coverIdContent = coverId.innerHTML;
        let coverImg = document.createElement("img");
        coverImg.className = "cover-detail-book";

        let url_cover = "https://covers.openlibrary.org/b/id/" + coverIdContent + "-L.jpg";

        fetch(url_cover)
            .then(response => response.blob())
            .then(blob => {
                coverImg.src = URL.createObjectURL(blob);
            })
            .catch(error => console.error("Error fetching dynamic image:", error));

        divDetailBookCover.appendChild(coverImg);
    }
}

    export function addToList() {

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
                    .catch(error => {console.error("Error adding book to list")
                    })
            })
    }
