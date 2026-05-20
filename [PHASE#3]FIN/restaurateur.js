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
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(informationsStatut)
        });

        if (reponseServeur.ok) {
            const donnees = await reponseServeur.json();

            if (donnees.succes === true) {
                const ligneTableau   = document.getElementById(`ligne-commande-${idCommande}`);
                const celluleStatut  = ligneTableau.querySelector(".cellule-statut");
                const celluleActions = ligneTableau.querySelector(".cellule-actions");

                if (actionDemandee === "preparer") {
                    celluleStatut.textContent = "En préparation";

                    const selectHTML = window.listeLivreurs.map(l =>
                        `<option value="${l.email}">${l.nom} ${l.prenom}</option>`
                    ).join('');

                    celluleActions.innerHTML = `
                        <div class="zone-assignation">
                            <select class="select-livreur" id="select-livreur-${idCommande}">
                                <option value="">-- Choisir un livreur --</option>
                                ${selectHTML}
                            </select>
                            <button class="btn-statut btn-prete"
                                onclick="gererStatut(this, ${idCommande}, 'prete')">
                                Prête & Assigner
                            </button>
                        </div>`;

                } else if (actionDemandee === "prete") {
                    celluleStatut.textContent = "Prête";
                    celluleActions.innerHTML = `<span style="color: green; font-style: italic;">Prête & livreur notifié</span>`;
                }

            } else {
                alert(donnees.message || "L'action n'a pas pu être enregistrée.");
            }
        } else {
            alert("Erreur de communication avec le serveur.");
        }

    } catch (erreur) {
        alert("Impossible de joindre le serveur. Réessayez plus tard.");
        console.error(erreur);
    }
}
