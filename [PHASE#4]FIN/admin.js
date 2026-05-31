async function actionAdministrateur(id, action) {
    const message = document.getElementById("message-admin");
    const etatBloque = document.getElementById("etat-bloque");
    const etatAvantages = document.getElementById("etat-avantages");

    message.textContent = "";

    try {
        const reponse = await fetch("action_administrateur.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                id: id,
                action: action
            })
        });

        const donnees = await reponse.json();

        if (donnees.succes === true) {
            message.style.color = "green";
            message.textContent = donnees.message;

            if (donnees.bloque === "oui") {
                etatBloque.textContent = "Bloqué";
            } else {
                etatBloque.textContent = "Actif";
            }

            etatAvantages.innerHTML =
                "VIP : " + donnees.vip + "<br>" +
                "Premium : " + donnees.premium + "<br>" +
                "Remise : " + donnees.remise + "%";

        } else {
            message.style.color = "red";
            message.textContent = donnees.message;
        }

    } catch (erreur) {
        message.style.color = "red";
        message.textContent = "Erreur : impossible de contacter le serveur.";
    }
}
