export function fetchCover(coverId, img) {
    const url = `https://covers.openlibrary.org/b/id/${coverId}-M.jpg`;

    fetch(url)
        .then(response => response.blob())
        .then(blob => { img.src = URL.createObjectURL(blob); })
        .catch(error => console.error("Erreur image :", error));
