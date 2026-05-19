document.addEventListener("DOMContentLoaded", function() {
    const tableBody = document.querySelector("table tbody");
    const notification = document.querySelector("#notification-statut");

    function afficherMessage(texte, estSucces) {
        notification.textContent = texte;
        notification.style.color = estSucces ? "green" : "red";
        setTimeout(() => { notification.textContent = ""; }, 4000);
    }

    tableBody.addEventListener("click", async function(e) {
        if (!e.target.classList.contains("btn-statut")) return;

        const bouton = e.target;
        const idCommande = bouton.getAttribute("data-id");
        const action = bouton.getAttribute("data-action");
        let livreurEmail = null;

        if (action === "prete") {
            const selectLivreur = document.querySelector(`#select-livreur-${idCommande}`);
            livreurEmail = selectLivreur.value;

            if (!livreurEmail) {
                afficherMessage("Erreur : Veuillez sélectionner un livreur avant de valider.", false);
                return;
            }
        }

        try {
            const donneesStatut = {
                id: idCommande,
                action: action,
                livreur: livreurEmail
            };

            const reponse = await fetch('modifier_statut_commande.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(donneesStatut)
            });

            if (reponse.ok) {
                const data = await reponse.json();

                if (data.succes === true) {
                    afficherMessage(data.message, true);
                    
                    const ligne = document.querySelector(`#ligne-commande-${idCommande}`);
                    const celluleStatut = ligne.querySelector(".cellule-statut");
                    const celluleActions = ligne.querySelector(".cellule-actions");

                    if (action === "preparer") {
                        celluleStatut.textContent = "en préparation";
                        celluleActions.innerHTML = `<span style="color: orange; font-style: italic;">Prête au prochain rafraîchissement</span>`;
                    } else if (action === "prete") {
                        celluleStatut.textContent = "en attente";
                        celluleActions.innerHTML = `<span style="color: gray; font-style: italic;">Assignée et prête</span>`;
                    }
                } else {
                    afficherMessage(data.message, false);
                }
            } else {
                afficherMessage(`Erreur du serveur : ${reponse.status}`, false);
            }

        } catch (erreur) {
            afficherMessage("Impossible de joindre le serveur. Réessayez plus tard.", false);
            console.error(erreur);
        }
    });
});
