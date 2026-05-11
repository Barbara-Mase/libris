
window.addEventListener("DOMContentLoaded", () => {

        //sélection du formulaire par l'id
        let form = document.getElementById('form');

        //écoute du formulaire
        form.addEventListener("submit", (event) => {
        event.preventDefault()

        //Création de l'url et de ses paramètres de recherche
        const params = new URLSearchParams();
        params.append("q", event.target.elements.search.value);
        params.append("limit", "10");


        //Fetch de l'API avec les paramètres plus haut
        fetch(`https://openlibrary.org/search.json?${params}`)

            //bien réviser le fetch
            .then(response => response.json()) // fetch me renvoie une Promise, j'utilise .json() pour faire l'équivalent d'un JSON.parse()
            .then(data => {

                //mettre un id pour vérifier si elle existe déjà et l'enlever à la prochaine requête
                let resultContainer = document.createElement('section');

                //Pour chaque élément de l'objet renvoyé par l'api
                for (let i = 0; i < data.docs.length; i++) {

                    //création du container
                    let bookArticle = document.createElement("article");
                    bookArticle.classList.add("book-card");

                    //Création de la variable contenant l'id de couverture
                    let cover_id = data.docs[i].cover_i;

                    let img = document.createElement("img");
                    bookArticle.appendChild(img);

                    //s'il existe une couverture
                     if(cover_id) {
                         //on créé la requête à l'API
                         let url_cover = "https://covers.openlibrary.org/b/id/" + cover_id + "-M.jpg";

                         fetch(url_cover)
                             .then(response => response.blob())

                             //réviser blob
                             .then(blob => {
                                 img.src = URL.createObjectURL(blob);
                             })
                             .catch(error => console.error("Error fetching dynamic image:", error));
                     }

                    let h2 = document.createElement("h2");
                    let p_author = document.createElement("p")
                    let p_date = document.createElement("p")

                    let bookTitle = document.createTextNode(data.docs[i].title)
                    let author = document.createTextNode(data.docs[i].author_name)
                    let publish_year = document.createTextNode(data.docs[i].first_publish_year)

                    let string_bookTitle = data.docs[i].title
                    let string_author = data.docs[i].author_name;
                    let string_publish_year = data.docs[i].first_publish_year;
                    let key = data.docs[i].key;

                    h2.appendChild(bookTitle);
                    bookArticle.appendChild(h2);
                    p_author.appendChild(author);
                    bookArticle.appendChild(p_author);
                    p_date.appendChild(publish_year);
                    bookArticle.appendChild(p_date);


                    let addButton = document.createElement("button");
                    addButton.classList.add("addButton");
                    addButton.dataset.title = string_bookTitle;
                    addButton.dataset.author = string_author;
                    addButton.dataset.publishYear = string_publish_year;
                    //Stocker le cover_id ou l'url ?
                    addButton.dataset.coverId = cover_id;
                    addButton.dataset.bookKey = key;
                    let addText = document.createTextNode("Ajouter");
                    addButton.appendChild(addText);
                    bookArticle.appendChild(addButton);
                    resultContainer.appendChild(bookArticle);
                    resultContainer.classList.add("result-container");
                    document.body.appendChild(resultContainer);

                }

                let buttonListeners = document.querySelectorAll('.addButton');

                // Attention getElementByClassName renvoie un tableau
                for (let buttonListener of buttonListeners) {

                    buttonListener.addEventListener('click', (event) => {

                        //création du formData
                        const formData = new FormData();

                        //Ajout dans le formData des infos récupérées depuis l'API
                        formData.append("title", event.target.dataset.title);
                        formData.append("author", event.target.dataset.author);
                        formData.append('publish_year', event.target.dataset.publishYear);
                        formData.append("cover_id", event.target.dataset.coverId);
                        formData.append("key", event.target.dataset.bookKey);

                        console.log(formData);

                        fetch("index.php?route=check-create-book", {
                            method: 'POST',
                            body: formData
                        })

                            //vérifier l'info récupéré via ce response via response.ok par exemple
                            //mettre la gestion d'erreur ici
                            .then(response => response.text())
                            .then(data => {

                            })
                            .catch(error => {
                                console.error('Erreur lors du fetch:', error);
                            });
                    });

                }

            })

            .catch(err => console.error(err));

   })




});