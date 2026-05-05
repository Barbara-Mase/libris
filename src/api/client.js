window.addEventListener("DOMContentLoaded", () => {

    //let url = 'https://openlibrary.org/search.json?author=tamsyn+muir&limit=3';
    let url = 'https://openlibrary.org/search.json?q=creative+cognition';

    fetch(url) // j'interroge l'URL
        .then(response => response.json()) // fetch me renvoie une Promise, j'utilise .json() pour faire l'équivalent d'un JSON.parse()
        .then(data => {



            for(let i = 0; i < data.docs.length; i++) {

                let container = document.createElement("div");
                document.body.appendChild(container);

                let cover_id = data.docs[i].cover_i;
                console.log(cover_id)
                let url_cover = "https://covers.openlibrary.org/b/id/" + cover_id + "-M.jpg";

                fetch(url_cover)
                    .then(response => response.blob())
                    .then(blob => {
                        let img = document.createElement("img");
                        img.src = URL.createObjectURL(blob);
                        container.appendChild(img);
                    })
                    .catch(error => console.error("Error fetching dynamic image:", error));

                let h2 = document.createElement("h2");

                let p_author = document.createElement("p")

                let p_date = document.createElement("p")
                let bookTitle = document.createTextNode(data.docs[i].title)
                let author = document.createTextNode(data.docs[i].author_name)
                let publish_year = document.createTextNode(data.docs[i].first_publish_year)

                h2.appendChild(bookTitle);
                container.appendChild(h2)
                p_author.appendChild(author)
                container.appendChild(p_author)
                p_date.appendChild(publish_year)
                container.appendChild(p_date)


           }








        }) // response.json() => son resolve m'envoie les données


        .catch(err => console.error(err)); // response.json() => son reject m'envoie une erreur



});