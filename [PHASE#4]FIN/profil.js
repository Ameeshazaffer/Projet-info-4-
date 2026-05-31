document.addEventListener("DOMContentLoaded", () => {
    const formulaire = document.getElementById("formulaire-profil");
    const message = document.getElementById("message-profil");

    const boutonModifier = document.getElementById("bouton-modifier");
    const boutonEnregistrer = document.getElementById("bouton-enregistrer");
    const boutonAnnuler = document.getElementById("bouton-annuler");

    const nom = document.getElementById("nom");
    const prenom = document.getElementById("prenom");
    const email = document.getElementById("email");
    const telephone = document.getElementById("telephone");
    const adresse = document.getElementById("adresse");
    const etage = document.getElementById("etage");
    const codeInterphone = document.getElementById("code_interphone");

    const compteurNom = document.getElementById("compteur-nom");
const compteurPrenom = document.getElementById("compteur-prenom");

   const champs = [nom, prenom, email, telephone, adresse, etage, codeInterphone];

    let ancienneValeurNom = nom.value;
    let ancienneValeurPrenom = prenom.value;
    let ancienneValeurTelephone = telephone.value;
    let ancienneValeurAdresse = adresse.value;
    let ancienneValeurEtage = etage.value;
    let ancienneValeurCode = codeInterphone.value;

    function mettreAJourCompteur(champ, compteur) {
        const nombre = champ.value.length;
        compteur.textContent = nombre + " caractère" + (nombre > 1 ? "s" : "");
    }

    function afficherCompteurs() {
    mettreAJourCompteur(nom, compteurNom);
    mettreAJourCompteur(prenom, compteurPrenom);
}
    function activerModification() {
        for (let champ of champs) {
            champ.disabled = false;
        }

        boutonModifier.style.display = "none";
        boutonEnregistrer.style.display = "inline-block";
        boutonAnnuler.style.display = "inline-block";
        message.textContent = "";
    }

    function annulerModification() {
        nom.value = ancienneValeurNom;
        prenom.value = ancienneValeurPrenom;
        telephone.value = ancienneValeurTelephone;
        adresse.value = ancienneValeurAdresse;
        etage.value = ancienneValeurEtage;
        codeInterphone.value = ancienneValeurCode;

        for (let champ of champs) {
            champ.disabled = true;
        }

        afficherCompteurs();

        boutonModifier.style.display = "inline-block";
        boutonEnregistrer.style.display = "none";
        boutonAnnuler.style.display = "none";

        message.textContent = "";
    }

    afficherCompteurs();

    nom.addEventListener("input", () => {
        mettreAJourCompteur(nom, compteurNom);
    });

    prenom.addEventListener("input", () => {
        mettreAJourCompteur(prenom, compteurPrenom);
    });

    
    telephone.addEventListener("input", () => {
        telephone.value = telephone.value.replace(/[^0-9]/g, "");

        if (telephone.value.length > 10) {
            telephone.value = telephone.value.substring(0, 10);
        }
    });

    boutonModifier.addEventListener("click", activerModification);
    boutonAnnuler.addEventListener("click", annulerModification);

    formulaire.addEventListener("submit", async (evenement) => {
        evenement.preventDefault();

        message.textContent = "";

        const nomValeur = nom.value.trim();
        const prenomValeur = prenom.value.trim();
        const telephoneValeur = telephone.value.trim();
        const adresseValeur = adresse.value.trim();
        const etageValeur = etage.value.trim();
        const codeValeur = codeInterphone.value.trim();

        if (nomValeur === "") {
            message.style.color = "red";
            message.textContent = "Erreur : le nom est obligatoire.";
            return;
        }

        if (prenomValeur === "") {
            message.style.color = "red";
            message.textContent = "Erreur : le prénom est obligatoire.";
            return;
        }

        if (!email.checkValidity()) {
            message.style.color = "red";
            message.textContent = "Erreur : email invalide.";
            return;
        }

        if (telephoneValeur.length !== 10) {
            message.style.color = "red";
            message.textContent = "Erreur : le téléphone doit contenir exactement 10 chiffres.";
            return;
        }

        if (etageValeur !== "" && isNaN(etageValeur)) {
            message.style.color = "red";
            message.textContent = "Erreur : l'étage doit être un nombre.";
            return;
        }

        try {
            const reponse = await fetch("modifier_profil.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    nom: nomValeur,
                    prenom: prenomValeur,
                    telephone: telephoneValeur,
                    adresse: adresseValeur,
                    etage: etageValeur,
                    code_interphone: codeValeur
                })
            });

            const donnees = await reponse.json();

            if (donnees.succes === true) {
                message.style.color = "green";
                message.textContent = donnees.message;

                ancienneValeurNom = nomValeur;
                ancienneValeurPrenom = prenomValeur;
                ancienneValeurTelephone = telephoneValeur;
                ancienneValeurAdresse = adresseValeur;
                ancienneValeurEtage = etageValeur;
                ancienneValeurCode = codeValeur;

                for (let champ of champs) {
                    champ.disabled = true;
                }

                boutonModifier.style.display = "inline-block";
                boutonEnregistrer.style.display = "none";
                boutonAnnuler.style.display = "none";

            } else {
                message.style.color = "red";
                message.textContent = donnees.message;
            }

        } catch (erreur) {
            message.style.color = "red";
            message.textContent = "Erreur : impossible de contacter le serveur.";
        }
    });
});
