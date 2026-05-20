async function gererStatut(boutonClique, idCommande, actionDemandee) {
    
    let emailLivreur = null;

    if (actionDemandee === "prete") {
        const listeLivreurs = document.getElementById(`select-livreur-${idCommande}`);
        emailLivreur = listeLivreurs.value;

        if (!emailLivreur) {
            alert("Erreur : Veuillez sélectionner un livreur avant de valider.");
            return;
        }
    }

    try {
        const informationsStatut = {
            id: idCommande,
            action: actionDemandee,
            livreur: emailLivreur
        };

        const reponseServeur = await fetch('modifier_statut_commande.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(informationsStatut)
        });

        if (reponseServeur.ok) {
            const donnees = await reponseServeur.json();

            if (donnees.succes === true) {
                const ligneTableau = document.getElementById(`ligne-commande-${idCommande}`);
                const celluleStatut = ligneTableau.querySelector(".cellule-statut");
                const celluleActions = ligneTableau.querySelector(".cellule-actions");

                if (actionDemandee === "preparer") {
                    celluleStatut.textContent = "en préparation";
                    celluleActions.innerHTML = `<span style="color: orange; font-style: italic;">Prête au prochain rafraîchissement</span>`;
                } else if (actionDemandee === "prete") {
                    celluleStatut.textContent = "en attente";
                    celluleActions.innerHTML = `<span style="color: gray; font-style: italic;">Assignée et prête</span>`;
                }
            } else {
                alert("L'action n'a pas pu être enregistrée. Règles non respectées ou erreur.");
            }
        } else {
            alert("Erreur de communication avec le serveur.");
        }

    } catch (erreur) {
        alert("Impossible de joindre le serveur. Réessayez plus tard.");
        console.error(erreur);
    }
}
