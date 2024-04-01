
document.addEventListener("DOMContentLoaded", function() {
    let num1 = document.getElementById("num1");
    let num2 = document.getElementById("num2");
    let scoreDisplay = document.getElementById("score");
    let facileButton = document.getElementById("facile");
    let intermediaireButton = document.getElementById("intermediaire");
    let difficileButton = document.getElementById("difficile");
    let reselectNiveauButton = document.getElementById("reselectNiveau");
	let choixNiveauDiv = document.getElementById("choixNiveau");
    let appDiv = document.querySelector(".app");
    let validerButton = document.getElementById("valider");
    let resultatPara = document.getElementById("resultat");
    let score = 0;
    let niveau = "";
    let finDuJeuButton = document.getElementById("finDuJeu");
    let timerElement = document.getElementById('timer');
    let debutJeu = Date.now(); // Temps de début du jeu
    let timerInterval;
	let dernierTempsReponse = debutJeu;

    document.getElementById("input").addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            event.preventDefault(); // Empêcher le comportement par défaut de la touche "Entrée"
            validerButton.click(); // Simuler un clic sur le bouton de validation
        }
    });

    function facile() {
		let tables = [1, 2, 10];
		let tableIndex = Math.floor(Math.random() * tables.length);
		let n = tables[tableIndex];
		let m = Math.floor(Math.random() * 10) + 1;
		num1.innerText = n;
		num2.innerText = m;
        demarrerChronometre(); // Démarrer le chronomètre
    }

    function intermediaire() {
		let tables = [5, 3];
		let tableIndex = Math.floor(Math.random() * tables.length);
		let n = tables[tableIndex];
		let m = Math.floor(Math.random() * 10) + 1;
		num1.innerText = n;
		num2.innerText = m;
        demarrerChronometre(); // Démarrer le chronomètre
}

      function difficile() {
		let tables = [4, 6, 7, 8, 9];
		let tableIndex = Math.floor(Math.random() * tables.length);
		let n = tables[tableIndex];
		let m = Math.floor(Math.random() * 10) + 1;
		num1.innerText = n;
		num2.innerText = m;
        demarrerChronometre(); // Démarrer le chronomètre
      }

	facileButton.addEventListener("click", function() {
    appDiv.style.display = "block";
    choixNiveauDiv.style.display = "none";
    facile();
    niveau = "facile";
    if (!timerInterval) {
        demarrerChronometre(); // Démarrer le chronomètre uniquement si ce n'est pas déjà fait
    }
});

intermediaireButton.addEventListener("click", function() {
    appDiv.style.display = "block";
    choixNiveauDiv.style.display = "none";
    intermediaire();
    niveau = "intermediaire";
    if (!timerInterval) {
        demarrerChronometre(); // Démarrer le chronomètre uniquement si ce n'est pas déjà fait
    }
});

difficileButton.addEventListener("click", function() {
    appDiv.style.display = "block";
    choixNiveauDiv.style.display = "none";
    difficile();
    niveau = "difficile";
    if (!timerInterval) {
        demarrerChronometre(); // Démarrer le chronomètre uniquement si ce n'est pas déjà fait
    }
});


    reselectNiveauButton.addEventListener("click", function() {
        appDiv.style.display = "none";
        choixNiveauDiv.style.display = "block";
    });

    finDuJeuButton.addEventListener("click", function() {
       //Faudra envoyer dans une page php
    });

    function afficherTemps(secondes) {
        const minutes = Math.floor(secondes / 60);
        const secondesRestantes = secondes % 60;
        const tempsFormatte = `${minutes < 10 ? '0' : ''}${minutes}:${secondesRestantes < 10 ? '0' : ''}${secondesRestantes}`;
        timerElement.textContent = tempsFormatte;
    }

    function demarrerChronometre() {
        clearInterval(timerInterval); // Arrêter le chronomètre précédent s'il y en a un
        timerInterval = setInterval(() => {
            let maintenant = Date.now();
            let tempsEcoule = Math.floor((maintenant - debutJeu) / 1000); // Temps écoulé depuis le début du jeu en secondes
            afficherTemps(tempsEcoule);
        }, 1000);
    }

let correctResponses = 0;


validerButton.addEventListener("click", function() {
    event.preventDefault();
    let reponse = document.getElementById("input").value;
    let resultat = parseInt(reponse) === parseInt(num1.innerText) * parseInt(num2.innerText);

    // Calcul du temps écoulé depuis la dernière réponse en secondes
    let tempsReponse = Math.floor((Date.now() - dernierTempsReponse) / 1000);

    clearInterval(timerInterval); // Arrêter le chronomètre

    if (resultat) {
        // Calcul du score en fonction du temps de réponse
        let scoreToAdd = 0;
        if (tempsReponse < 3) {
            scoreToAdd = 3;
        } else if (tempsReponse >= 3 && tempsReponse < 5) {
            scoreToAdd = 2;
        } else {
            scoreToAdd = 1;
        }

        // Appliquer le bonus x2 sur le score à ajouter
        if (correctResponses >= 9) {
            scoreToAdd *= 2;
            document.getElementById("bonusMessage").innerText = "Bonus x2 activé !";
        }

        score += scoreToAdd; // Ajouter le score calculé
        correctResponses++; // Incrémentez le nombre de réponses correctes consécutives
        resultatPara.innerText = "Bonne réponse !";
    } else {
        resultatPara.innerText = "Mauvaise réponse.";
        correctResponses = 0; // Réinitialisez le nombre de réponses correctes consécutives
        document.getElementById("bonusMessage").innerText = ""; // Effacer le message en cas de mauvaise réponse
    }

    scoreDisplay.innerText = score;
    document.getElementById("input").value = "";
    demarrerChronometre(); // Redémarrer le chronomètre

    // Enregistrer le moment de la dernière réponse
    dernierTempsReponse = Date.now();
    if (niveau === "facile") {
        facile();
    } else if (niveau === "intermediaire") {
        intermediaire();
    } else if (niveau === "difficile") {
        difficile();
    }
});


});
