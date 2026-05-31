document.addEventListener("DOMContentLoaded", () => {
    const formulaireCo = document.querySelector("#formulaire-co");
    const message = document.querySelector("#message");
    const boutonVisibiliteMdp = document.querySelector("#bouton-visibilite-mdp");
    const saisieMdp = document.querySelector("#mdp");
    const saisieEmail = document.querySelector("#email");

    boutonVisibiliteMdp.addEventListener('click', function() {
        if (saisieMdp.type === 'password') {
            saisieMdp.type = 'text';
            boutonVisibiliteMdp.textContent = 'visibility';
        } else {
            saisieMdp.type = 'password';
            boutonVisibiliteMdp.textContent = 'visibility_off';
        }
    });

    formulaireCo.addEventListener('submit', async function(evenement) {
        evenement.preventDefault();
        message.textContent = "";

        if (saisieEmail.value.trim() === "") {
            message.style.color = "red";
            message.textContent = "Erreur : Veuillez saisir votre adresse email.";
            return;
        }
        const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!regexEmail.test(saisieEmail.value.trim())) {
            message.style.color = "red";
            message.textContent = "Erreur : L'adresse email n'est pas au bon format.";
            return;
        }

        if (saisieMdp.value.trim() === "") {
            message.style.color = "red";
            message.textContent = "Erreur : Veuillez saisir votre mot de passe.";
            return;
        }

        try {
            const reponse = await fetch('co.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    email: saisieEmail.value.trim(),
                    mdp: saisieMdp.value.trim()
                })
            });

            const donneesRenvoyees = await reponse.json();

            if (donneesRenvoyees.succes === true) {
                message.style.color = "green";
                message.textContent = donneesRenvoyees.message;
                formulaireCo.reset();
                setTimeout(() => {
                    window.location.href = donneesRenvoyees.redirection;
                }, 2000);
            } else {
                message.style.color = "red";
                message.textContent = donneesRenvoyees.message;
            }

        } catch (erreur) {
            message.style.color = "red";
            message.textContent = "Impossible de joindre le serveur. Réessayez plus tard.";
            console.error(erreur);
        }
    });
});
