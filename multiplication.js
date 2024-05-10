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
    let debutJeu = Date.now();
    let timerInterval;
    let dernierTempsReponse = debutJeu;
    let correctResponses = 0;
    let derniersIncorrects = [];

    document.getElementById("input").addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
            validerButton.click();
        }
    });

    function genererOperation(tables) {
        let choix = Math.random();
        if (choix < 0.9) {
            let n = tables[Math.floor(Math.random() * tables.length)];
            let m = Math.floor(Math.random() * 10) + 1;
            return { num1: n, num2: m };
        }
        else if (derniersIncorrects.length > 0) {
            let indexIncorrect = Math.floor(Math.random() * derniersIncorrects.length);
            return derniersIncorrects[indexIncorrect];
        }
        else {
            let n = tables[Math.floor(Math.random() * tables.length)];
            let m = Math.floor(Math.random() * 10) + 1;
            return { num1: n, num2: m };
        }
    }

    function facile() {
        let operation = genererOperation([1, 2, 10]);
        num1.innerText = operation.num1;
        num2.innerText = operation.num2;
        demarrerChronometre();
    }

    function intermediaire() {
        let operation = genererOperation([5, 3]);
        num1.innerText = operation.num1;
        num2.innerText = operation.num2;
        demarrerChronometre();
    }

    function difficile() {
        let operation = genererOperation([4, 6, 7, 8, 9]);
        num1.innerText = operation.num1;
        num2.innerText = operation.num2;
        demarrerChronometre();
    }

    facileButton.addEventListener("click", function() {
        appDiv.style.display = "block";
        choixNiveauDiv.style.display = "none";
        facile();
        niveau = "facile";
        if (!timerInterval) {
            demarrerChronometre();
        }
    });

    intermediaireButton.addEventListener("click", function() {
        appDiv.style.display = "block";
        choixNiveauDiv.style.display = "none";
        intermediaire();
        niveau = "intermediaire";
        if (!timerInterval) {
            demarrerChronometre();
        }
    });

    difficileButton.addEventListener("click", function() {
        appDiv.style.display = "block";
        choixNiveauDiv.style.display = "none";
        difficile();
        niveau = "difficile";
        if (!timerInterval) {
            demarrerChronometre();
        }
    });

    reselectNiveauButton.addEventListener("click", function() {
        appDiv.style.display = "none";
        choixNiveauDiv.style.display = "block";
    });




    function afficherTemps(secondes) {
        const minutes = Math.floor(secondes / 60);
        const secondesRestantes = secondes % 60;
        const tempsFormatte = `${minutes < 10 ? '0' : ''}${minutes}:${secondesRestantes < 10 ? '0' : ''}${secondesRestantes}`;
        timerElement.textContent = tempsFormatte;
    }

    function demarrerChronometre() {
        clearInterval(timerInterval);
        timerInterval = setInterval(() => {
            let maintenant = Date.now();
            let tempsEcoule = Math.floor((maintenant - debutJeu) / 1000);
            afficherTemps(tempsEcoule);
        }, 1000);
    }
	
	function updatelien(){
		let url = "score.php?score=" + score + "&userid=" + uniqid; 
        document.getElementById("finDuJeu").href = url; 
	}

    validerButton.addEventListener("click", function() {
        event.preventDefault();
        let reponse = document.getElementById("input").value;
        let resultat = parseInt(reponse) === parseInt(num1.innerText) * parseInt(num2.innerText);
        let tempsReponse = Math.floor((Date.now() - dernierTempsReponse) / 1000);

        clearInterval(timerInterval);

        if (resultat) {
            let scoreToAdd = 0;
            if (tempsReponse < 3) {
                scoreToAdd = 3;
            }
            else if (tempsReponse >= 3 && tempsReponse < 5) {
                scoreToAdd = 2;
            }
            else {
                scoreToAdd = 1;
            }

            if (correctResponses >= 9) {
                scoreToAdd *= 2;
                document.getElementById("bonusMessage").innerText = "Bonus x2 activé !";
            }

            score += scoreToAdd;
            correctResponses++;
            resultatPara.innerText = "Bonne réponse !";
        }
        
        else {
            resultatPara.innerText = "Mauvaise réponse.";
            correctResponses = 0; 
            let scoreToRemove = 0;
            if (tempsReponse < 3) {
                scoreToRemove = -1;
            }
            else if (tempsReponse >= 3 && tempsReponse < 5) {
                scoreToRemove = -2;
            }
            else {
                scoreToRemove = -3;
            }
            score += scoreToRemove;
            document.getElementById("bonusMessage").innerText = ""; 
            derniersIncorrects.push({ num1: parseInt(num1.innerText), num2: parseInt(num2.innerText) });
        }

        scoreDisplay.innerText = score;
        document.getElementById("input").value = "";
        demarrerChronometre();
        dernierTempsReponse = Date.now();
		updatelien();



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
