document.addEventListener("DOMContentLoaded", () => {

  let tousLesPlats = [];

  // Charger le fichier JSON
  fetch("plats.json")
    .then(reponse => reponse.json())
    .then(data => {

      tousLesPlats = data.plat;

      afficherPlats();
    });


  // Quand on change un filtre ou le tri
  document.querySelectorAll("input, .select-tri").forEach(element => {

    element.addEventListener("change", afficherPlats);

  });


  function afficherPlats() {

    
    // Récupération des filtres
    

    let categories = [];
    let types = [];
    let allergenes = [];


    // On récupère toutes les cases cochées
    document.querySelectorAll("input[type='checkbox']:checked")
      .forEach(caseCochee => {

        let valeur = caseCochee.value;


        // ----- Catégories -----

        if (["entree", "plat", "dessert"].includes(valeur)) {
          categories.push(valeur);
        }


        // ----- Types -----

        if (["viande", "poisson", "vegetarien", "vegan"].includes(valeur)) {
          types.push(valeur);
        }


        // ----- Allergènes -----

        if (valeur === "sans-gluten") {
          allergenes.push("gluten");
        }

        if (valeur === "sans-lactose") {
          allergenes.push("lactose");
        }

        if (valeur === "sans-fruits-a-coque") {
          allergenes.push("fruits_a_coque");
        }

        if (valeur === "sans-oeuf") {
          allergenes.push("oeuf");
        }

      });


    
    // Filtrage des plats
    

    let platsFiltres = tousLesPlats.filter(plat => {

      // Filtre catégorie
      if (
        categories.length > 0 &&
        !categories.includes(plat.type)
      ) {
        return false;
      }


      // Filtre type
      if (
        types.length > 0 &&
        !types.includes(plat.typeViande)
      ) {
        return false;
      }


      // Filtre allergènes
      for (let allergene of allergenes) {

        if (plat.allergenes.includes(allergene)) {
          return false;
        }

      }

      return true;

    });


    
    // Tri
    

    let tri = document.querySelector(".select-tri").value;


    if (tri === "Prix croissant") {

      platsFiltres.sort((a, b) => a.prix - b.prix);

    }


    if (tri === "Prix décroissant") {

      platsFiltres.sort((a, b) => b.prix - a.prix);

    }


    
    // Compteur
    

    let nb = platsFiltres.length;

    document.querySelector(".compteur").textContent =
      nb +
      " plat" +
      (nb > 1 ? "s" : "") +
      " trouvé" +
      (nb > 1 ? "s" : "");


    
    // Affichage des cartes
    

    let grille = document.querySelector(".grille-plats");


    // Aucun résultat
    if (platsFiltres.length === 0) {

      grille.innerHTML =
        "<p style='text-align:center;padding:3rem;font-family:Montserrat,sans-serif;'>Aucun plat ne correspond à votre sélection.</p>";

      return;
    }


    let html = "";


    platsFiltres.forEach(plat => {

      // ----- Badges -----

      let badge = "";


      if (plat.nom === "Soupe VGE") {

        badge = '<div class="badge-plat">SIGNATURE</div>';

      }


      if (plat.nom === "Beef Wellington") {

        badge = '<div class="badge-plat">PLAT DU JOUR</div>';

      }


      if (plat.nom === "Poularde de Bresse en Vessie") {

        badge = '<div class="badge-plat">POPULAIRE</div>';

      }


      // ----- Carte HTML -----

      html += `

        <div class="carte-plat">

          <div class="conteneur-image-plat">

            <img src="${plat.image}" alt="${plat.nom}">

            ${badge}

          </div>


          <div class="infos-plat">

            <h3 class="nom-plat">
              ${plat.nom}
            </h3>


            <p class="description-plat">
              ${plat.description}
            </p>


            <div class="pied-plat">

              <span class="prix-plat">
                ${plat.prix}€
              </span>


              <form action="commander.php" method="post">

                <input
                  type="hidden"
                  name="nom_plat"
                  value="${plat.nom}"
                >

                <input
                  type="hidden"
                  name="prix"
                  value="${plat.prix}"
                >


                <button
                  type="submit"
                  class="bouton-ajouter-panier"
                >
                  Commander
                </button>

              </form>

            </div>

          </div>

        </div>

      `;

    });


    // Injection du HTML
    grille.innerHTML = html;

  }

});
