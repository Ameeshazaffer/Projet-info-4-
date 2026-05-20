document.addEventListener("DOMContentLoaded", () => {
    const formulaireIns = document.querySelector("#formulaire-ins");
    const message = document.querySelector("#message");
    const boutonVisibilite = document.querySelector("#bouton-visibilite-mdp");
    const saisieMdp = document.querySelector("#mdp");
    const saisieEmail = document.querySelector("#email");

    boutonVisibilite.addEventListener('click', function () {
        if (saisieMdp.type === 'password') {
            saisieMdp.type = 'text';
            boutonVisibilite.textContent = 'visibility';
        } else {
            saisieMdp.type = 'password';
            boutonVisibilite.textContent = 'visibility_off';
        }
    });

    formulaireIns.addEventListener('submit', async function (e) {
        e.preventDefault();
        message.textContent = "";

        const nom = formulaireIns.querySelector('[name="nom"]').value.trim();
        const prenom = formulaireIns.querySelector('[name="prenom"]').value.trim();
        const telephone = formulaireIns.querySelector('[name="telephone"]').value.trim();
        const emailValeur = saisieEmail.value.trim();
        const mdpValeur = saisieMdp.value.trim();
        const adresse = formulaireIns.querySelector('[name="adresse"]').value.trim();
        const etage = formulaireIns.querySelector('[name="etage"]').value.trim();
        const code_interphone = formulaireIns.querySelector('[name="code_interphone"]').value.trim();
        const commentaires = formulaireIns.querySelector('[name="commentaires"]').value.trim();

        if (!nom || !prenom || !emailValeur || !mdpValeur || !adresse) {
            message.style.color = "red";
            message.textContent = "Erreur : Les champs Nom, Prénom, Email, Mot de passe et Adresse sont obligatoires.";
            return;
        }

        const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!regexEmail.test(emailValeur)) {
            message.style.color = "red";
            message.textContent = "Erreur : L'adresse email n'est pas au bon format.";
            return;
        }

        if (telephone !== "") {
            const regexTel = /^[0-9]{10}$/;
            if (!regexTel.test(telephone)) {
                message.style.color = "red";
                message.textContent = "Erreur : Le numéro de téléphone doit contenir exactement 10 chiffres.";
                return;
            }
        }

        if (etage !== "" && isNaN(etage)) {
            message.style.color = "red";
            message.textContent = "Erreur : Le champ étage doit être un nombre.";
            return;
        }

        if (mdpValeur.length > 8) {
            message.style.color = "red";
            message.textContent = "Erreur : Le mot de passe ne doit pas dépasser 8 caractères.";
            return;
        }

        try {
            const reponse = await fetch('ins.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    nom, prenom, telephone,
                    email: emailValeur,
                    mdp: mdpValeur,
                    adresse, etage, code_interphone, commentaires
                })
            });

            if (reponse.ok) {
                const data = await reponse.json();
                if (data.succes === true) {
                    message.style.color = "green";
                    message.textContent = data.message;
                    formulaireIns.reset();
                    setTimeout(() => {
                        window.location.href = "connexion.php";
                    }, 2000);
                } else {
                    message.style.color = "red";
                    message.textContent = data.message;
                }
            } else {
                message.style.color = "red";
                message.textContent = `Erreur du serveur : ${reponse.status}`;
            }

        } catch (erreur) {
            message.style.color = "red";
            message.textContent = "Impossible de joindre le serveur. Réessayez plus tard.";
            console.error(erreur);
        }
    });
});