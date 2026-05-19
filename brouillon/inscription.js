const formulaireIns = document.querySelector("#formulaire-ins");
const message = document.querySelector("#message");
const boutonVisibiliteMdp = document.querySelector("#bouton-visibilite-mdp");
const mdp = document.querySelector("#mdp");
const email = document.querySelector("#email");

function compteur(donnee, compteur, nbCaractereMax) {
    donnee.addEventListener('input', function() {
        const longueurActuelle = donnee.value.length;
        compteur.textContent = `${longueurActuelle} / ${nbCaractereMax}`;
    });
}

compteur(email, document.querySelector("#compteur-email"), 30);
compteur(mdp, document.querySelector("#compteur-mdp"), 8);

boutonVisibiliteMdp.addEventListener('click', function(){
    if (mdp.type === 'password') {
        mdp.type = 'text';
        boutonVisibiliteMdp.textContent = 'visibility'; 
    } else {
        mdp.type = 'password';
        boutonVisibiliteMdp.textContent = 'visibility_off'; 
    }
});

formulaireIns.addEventListener('submit', async function(e){
    e.preventDefault();
    message.textContent = ""; 

    const nom = formulaireIns.querySelector('[name="nom"]').value.trim();
    const prenom = formulaireIns.querySelector('[name="prenom"]').value.trim();
    const telephone = formulaireIns.querySelector('[name="telephone"]').value.trim();
    const emailValeur = email.value.trim(); 
    const mdpValeur = mdp.value.trim();    
    const adresse = formulaireIns.querySelector('[name="adresse"]').value.trim();
    const etage = formulaireIns.querySelector('[name="etage"]').value.trim();

    if(!nom || !prenom || !emailValeur || !mdpValeur || !adresse) {
        message.style.color = "red";
        message.textContent = "Erreur : Les champs Nom, Prénom, Email, Mot de passe et Adresse sont obligatoires.";
        return; 
    }

    if (!email.checkValidity()) {
        message.style.color = "red";
        message.textContent = "Erreur : L'adresse email n'est pas au bon format.";
        return;
    }

    if (telephone !== "") {
        const validiteTel = /^[0-9]{10}$/;
        if (!validiteTel.test(telephone)) {
            message.style.color = "red";
            message.textContent = "Erreur : Le numéro de téléphone doit contenir exactement 10 chiffres.";
            return;
        }
    }

    if (etage !== "" && isNaN(etage)) {
        message.style.color = "red";
        message.textContent = "Erreur : Le champ étage doit être un nombre .";
        return;
    }
    
    if (mdpValeur.length > 8) {
        message.style.color = "red";
        message.textContent = "Erreur : Le mot de passe ne doit pas dépasser 8 caractères.";
        return;
    }

    try {
        const donneesFormulaire = {
            nom: nom,
            prenom: prenom,
            telephone: telephone,
            email: emailValeur,
            mdp: mdpValeur,
            adresse: adresse,
            etage: etage
        };

        const reponse = await fetch('ins.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(donneesFormulaire) 
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
