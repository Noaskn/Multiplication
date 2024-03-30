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
    let timerInterval;
    let secondesEcoulees = 0;

    function facile() {
		let tables = [1, 2, 10];
		let tableIndex = Math.floor(Math.random() * tables.length);
		let n = tables[tableIndex];
		let m = Math.floor(Math.random() * 10) + 1;
		num1.innerText = n;
		num2.innerText = m;
	}

    function intermediaire() {
		let tables = [5, 3];
		let tableIndex = Math.floor(Math.random() * tables.length);
		let n = tables[tableIndex];
		let m = Math.floor(Math.random() * 10) + 1;
		num1.innerText = n;
		num2.innerText = m;
	}

	function difficile() {
		let tables = [4, 6, 7, 8, 9];
		let tableIndex = Math.floor(Math.random() * tables.length);
		let n = tables[tableIndex];
		let m = Math.floor(Math.random() * 10) + 1;
		num1.innerText = n;
		num2.innerText = m;
	}

	facileButton.addEventListener("click", function() {
        appDiv.style.display = "block";
        choixNiveauDiv.style.display = "none";
        facile();
        niveau="facile";
    });

    intermediaireButton.addEventListener("click", function() {
        appDiv.style.display = "block";
        choixNiveauDiv.style.display = "none";
        intermediaire();
        niveau="intermediaire";
    });

    difficileButton.addEventListener("click", function() {
        appDiv.style.display = "block";
        choixNiveauDiv.style.display = "none";
        difficile();
        niveau="difficile";
    });

    reselectNiveauButton.addEventListener("click", function() {
        appDiv.style.display = "none";
        choixNiveauDiv.style.display = "block";
    });

    finDuJeuButton.addEventListener("click", function() {
       //Faudra envoyer dans une page php
    });

    //Faudra changer le timer il est pété pour l'instant il tourne juste tout seul
    function afficherTemps(secondes) {
        const minutes = Math.floor(secondes / 60);
        const secondesRestantes = secondes % 60;
        const tempsFormatte = `${minutes < 10 ? '0' : ''}${minutes}:${secondesRestantes < 10 ? '0' : ''}${secondesRestantes}`;
        timerElement.textContent = tempsFormatte;
    }
    
    function demarrerChronometre() {
        timerInterval = setInterval(() => {
            afficherTemps(secondesEcoulees);
            secondesEcoulees++;
        }, 1000);
    }

    demarrerChronometre();

    validerButton.addEventListener("click", function() {
        event.preventDefault();
        let reponse = document.getElementById("input").value;
        let resultat = parseInt(reponse) === parseInt(num1.innerText) * parseInt(num2.innerText);
        if (resultat) {
            resultatPara.innerText = "Bonne réponse !";
            score++;
            scoreDisplay.innerText = score;
        }
        else {
            resultatPara.innerText = "Mauvaise réponse.";
        }
        document.getElementById("input").value = "";
        if (niveau === "facile") {
            facile();
        }
        else if (niveau === "intermediaire") {
            intermediaire();
        }
        else if (niveau === "difficile") {
            difficile();
        }
    });
});
