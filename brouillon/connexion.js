const formulaireCo = document.querySelector("#formulaire-co");
const message = document.querySelector("#message");
const boutonVisibiliteMdp = document.querySelector("#bouton-visibilite-mdp");
const inputMdp = document.querySelector("#mdp");
const inputEmail = document.querySelector("#email");

function compteur(donnee, compteur, nbCaractereMax) {
    donnee.addEventListener('input', function() {
        const longueurActuelle = donnee.value.length;
        compteur.textContent = `${longueurActuelle} / ${nbCaractereMax}`;
    });
}

compteur(inputEmail, document.querySelector("#compteur-email"), 30);
compteur(inputMdp, document.querySelector("#compteur-mdp"), 8);

boutonVisibiliteMdp.addEventListener('click', function(){
    if (inputMdp.type === 'password') {
        inputMdp.type = 'text';
        boutonVisibiliteMdp.textContent = 'visibility'; 
    } else {
        inputMdp.type === 'password';
        inputMdp.type = 'password';
        boutonVisibiliteMdp.textContent = 'visibility_off'; 
    }
});

formulaireCo.addEventListener('submit', async function(e){
    e.preventDefault(); 
    message.textContent = ""; 

    const email = inputEmail.value.trim();
    const mdp = inputMdp.value.trim();

    if(!email || !mdp) {
        message.style.color = "red";
        message.textContent = "Erreur : Veuillez remplir tous les champs.";
        return; 
    }

    if (!inputEmail.checkValidity()) {
        message.style.color = "red";
        message.textContent = "Erreur : L'adresse email n'est pas au bon format.";
        return;
    }

    if (mdp.length > 8) {
        message.style.color = "red";
        message.textContent = "Erreur : Le mot de passe ne doit pas dépasser 8 caractères.";
        return;
    }

    try {
        const donneesConnexion = {
            email: email,
            mdp: mdp
        };

        const reponse = await fetch('co.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(donneesConnexion)
        });

        if (reponse.ok) {
            const data = await reponse.json();

            if (data.succes === true) {
                message.style.color = "green";
                message.textContent = data.message;
                formulaireCo.reset(); 
                
                setTimeout(() => {
                    window.location.href = data.redirection;
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
