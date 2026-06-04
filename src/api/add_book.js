window.addEventListener("DOMContentLoaded", () => {

    let addButton = document.getElementById(".addButton");

    addButton.addEventListener("click", ()=> {

        const formData = new FormData()

        fetch("index.php?route=add-to-list", {
            method: 'POST',
            body: formData
        })
            .then(response.json)
            .then(data => {

                }
            )
            .catch(error => {
                console.error('Erreur lors du fetch:', error);
            });


    })
})