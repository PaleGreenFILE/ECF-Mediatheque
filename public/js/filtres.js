// Chercher le form
// selectionner input
// plaver un event listenner sur change Event

window.onload = () => {
    const formFilter = document.querySelector('#filtre')
    console.log('Fichier chargé');

    // ? Boucler sur les input du formulaire
    document.querySelectorAll('#filtre input').forEach((input) => {
        input.addEventListener('change', () => {
            // ? Récupérer les données du formuaire
            const form = new FormData(formFilter)

            // ? Génrér Url de requête ajax
            const requete = new URLSearchParams();

            form.forEach((value, key) => {
                requete.append(key, value);
            })

            // ? Récupérer Url de la page actuelle
            const url = new URL(window.location.href)

            // ? Exécuter la requête ajax
            fetch(url.pathname + "?" + requete.toString() + "&ajax=1", {
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            }).then(response => response.json()
            ).then(data => {
                // Cibler ma div
                const contentAjax = document.querySelector("#content")
                // Remplacer mon contenu
                contentAjax.innerHTML = data.contentAjax
            }).catch(error => alert(error))

        })
    })
}