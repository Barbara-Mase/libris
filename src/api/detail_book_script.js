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
     let divBookCover = document.getElementsByClassName("div-book-cover");

    for (let div of divBookCover) {
        div.appendChild(coverImg);
    }

    let addButton = document.getElementsByClassName("button-add-to-list");

    for(let button of addButton) {

        button.addEventListener("click", (event) => {

            fetch("index.php?route=add-to-list")
                .then(response => response.text)
                .then(data => {
                    //Gérer l'erreur 'si le user n'est pas connecté)

                })
                .catch(error => {

                })
        })
    }



})