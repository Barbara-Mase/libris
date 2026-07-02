function init() {
    const findBooks = document.getElementById('find-books');
    if(findBooks) {
        findBooks.addEventListener("submit", (event) => {
            event.preventDefault();
            const params = searchParams(event);
            fetchBooks(params);
        });
    }

}

document.addEventListener("DOMContentLoaded", init);

function searchParams(event) {

    const params = new URLSearchParams();
    params.append("q", event.target.elements.search.value);
    params.append("limit", "32");

    return params
}

function fetchBooks(searchParams) {

    fetch(`https://openlibrary.org/search.json?${searchParams}`)

        .then(response => response.json()) // fetch me renvoie une Promise, j'utilise .json() pour faire l'équivalent d'un JSON.parse()
        .then(data => {
            displayBooks(data.docs);
        })
        .catch(err => console.error(err));

}

function displayBooks(docs) {

    const oldContainer = document.querySelector('.result-container');
    if (oldContainer) {
        oldContainer.remove();
    }

    const resultContainer = document.createElement('section');
    resultContainer.classList.add('result-container');

    docs.forEach ((doc) => {
        let bookCard = createBookCard(doc);
        resultContainer.appendChild(bookCard);
    })

    let containerHome = document.getElementById("container-home");
    containerHome.appendChild(resultContainer);


}

function createBookCard(doc) {

    //Création du container des infos du livre
    let bookCard = document.createElement("article");
    bookCard.classList.add("book-card");

    //Création des balises
    let coverImg = document.createElement("img");
    if (doc.cover_i) {
        fetchCoverM(doc.cover_i, coverImg);
    }

    let titleBalise = document.createElement("h3");
    let authorBalise = document.createElement("p")
    let dateBalise = document.createElement("p")

    //Récupération des infos renvoyés par le fetch


    let nodeBookTitle = document.createTextNode(doc.title);
    let nodeAuthor = document.createTextNode(doc.author_name);
    //Puisque substring ne fonctionne que sur des chaines de caractères, on transforme les noeuds en string
    let strBookTitle = nodeBookTitle.textContent;
    let strAuthor = nodeAuthor.textContent;
    // substring permet de tronquer des chaines de caractères
    let truncBookTitle = strBookTitle.substring(0, 20);
    console.log(truncBookTitle)
    let truncAuthor = strAuthor.substring(0, 20);
    console.log(truncAuthor)
    let publishYear = document.createTextNode(doc.first_publish_year)


    //ajout des infos dans les balises
    // Conditions qui permet d'ajouter des points de suspension si le titre tronqué est long de 20 caractères
    if(truncBookTitle.length === 20) {
        titleBalise.textContent = truncBookTitle + "...";
    } else {
        titleBalise.textContent = truncBookTitle;
    }
    if(truncAuthor.length === 20) {
        authorBalise.textContent = truncAuthor + "...";
    } else {
        authorBalise.textContent = truncAuthor;
    }
    dateBalise.appendChild(publishYear);

    //Ajout des balises dans le container
    bookCard.appendChild(coverImg)
    bookCard.appendChild(titleBalise)
    bookCard.appendChild(authorBalise);
    bookCard.appendChild(dateBalise);

    //création du bouton "ajouter"
    let seeMoreButton = document.createElement("button");
    //class du bouton
    seeMoreButton.classList.add("seeMore");
    seeMoreButton.dataset.title = doc.title;
    seeMoreButton.dataset.author = doc.author_name;
    seeMoreButton.dataset.publishYear = doc.first_publish_year;
    seeMoreButton.dataset.coverId = doc.cover_i;
    seeMoreButton.dataset.bookKey = doc.key;
    //Création du texte dans le bouton
    let seeMoreText = document.createTextNode("See more");
    //Ajout du texte
    seeMoreButton.appendChild(seeMoreText);
    //Ajout du bouton dans le container du livre
    bookCard.appendChild(seeMoreButton);
    seeMoreButton.addEventListener("click", addBookToDataBase);

    return bookCard;

}

function fetchCoverM(coverId, img) {
    const url = `https://covers.openlibrary.org/b/id/${coverId}-M.jpg`;

    fetch(url)
        .then(response => response.blob())
        .then(blob => { img.src = URL.createObjectURL(blob); })
        .catch(error => console.error("Error :", error));
}

function addBookToDataBase(event) {

    const formData = new FormData();

    //Ajout dans le formData des infos récupérées depuis l'API
    formData.append("title", event.target.dataset.title);
    formData.append("author", event.target.dataset.author);
    formData.append('publish_year', event.target.dataset.publishYear);
    formData.append("cover_id", event.target.dataset.coverId);
    formData.append("key", event.target.dataset.bookKey);

    fetch("index.php?route=check-create-book", {
        method: 'POST',
        body: formData,
    })
        .then(response => response.text())

        .then( id => {
            window.location.assign('index.php?route=detail-book&id=' + id);
        })
        .catch(error => {
            console.error('Erreur lors du fetch:', error);
        });
}


