document.addEventListener("DOMContentLoaded", function() {
    let appDiv = document.querySelector(".app");
    let num1 = document.getElementById("num1");
    let num2 = document.getElementById("num2");
    let inputField = document.getElementById("input");
    let validerButton = document.getElementById("valider");
    let scoreDisplay = document.getElementById("score"); // Sélectionnez l'élément qui affiche le score
    let resultatPara = document.getElementById("resultat");
    let timerElement = document.getElementById('timer');
    let bonusMessage = document.getElementById('bonusMessage');
    let debutJeu = Date.now();
    let timerInterval;
    let dernierTempsReponse = debutJeu;
    let score = 0;
    let correctResponses = 0;

    function genererOperation() {
        let tables;
        switch(niveau) {
            case 'facile':
                tables = [1, 2, 10];
                break;
            case 'intermediaire':
                tables = [3, 5, 10];
                break;
            case 'difficile':
                tables = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
                break;
            default:
                return;
        }
        let index = Math.floor(Math.random() * tables.length);
        let num1Value = tables[index];
        let num2Value = Math.floor(Math.random() * 10) + 1;

        num1.innerText = num1Value;
        num2.innerText = num2Value;
    }

    if (typeof niveau !== 'undefined') {
        genererOperation();
    }

    function updatelien(){
        let url = "classe.php?score=" + score + "&userid=" + uniqid; 
        document.getElementById("classe").href = url; 
    }

    validerButton.addEventListener("click", function(event) {
        event.preventDefault();
        validerReponse();
    });

    inputField.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
            validerReponse();
        }
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

    function validerReponse() {
        let reponse = parseInt(inputField.value);
        let correctResult = parseInt(num1.innerText) * parseInt(num2.innerText);
        let tempsReponse = Math.floor((Date.now() - dernierTempsReponse) / 1000);

        if (reponse === correctResult) {
            let scoreToAdd = 0;
            if (tempsReponse < 3) {
                scoreToAdd = 3;
            } else if (tempsReponse >= 3 && tempsReponse < 5) {
                scoreToAdd = 2;
            } else {
                scoreToAdd = 1;
            }

            if (correctResponses >= 9) {
                scoreToAdd *= 2;
                bonusMessage.innerText = "Bonus x2 activé !";
            }

            score += scoreToAdd;
            correctResponses++;
            resultatPara.innerText = "Bonne réponse !";
        } else {
            resultatPara.innerText = "Mauvaise réponse.";
            correctResponses = 0; 
            let scoreToRemove = 0;
            if (tempsReponse < 3) {
                scoreToRemove = -1;
            } else if (tempsReponse >= 3 && tempsReponse < 5) {
                scoreToRemove = -2;
            } else {
                scoreToRemove = -3;
            }
            score += scoreToRemove;
            bonusMessage.innerText = ""; 
        }

        
        scoreDisplay.textContent = score;
        inputField.value = '';
        genererOperation();
        dernierTempsReponse = Date.now();
        updatelien();
    }

    demarrerChronometre();
    genererOperation();
});
