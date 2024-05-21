document.addEventListener("DOMContentLoaded", function(){

    // Stockage des éléments HTML nécessaires
    let appDiv = document.querySelector(".app");
    let num1 = document.getElementById("num1");
    let num2 = document.getElementById("num2");
    let inputField = document.getElementById("input");
    let validerButton = document.getElementById("valider");
    let scoreDisplay = document.getElementById("score");
    let resultatPara = document.getElementById("resultat");
    let timerElement = document.getElementById('timer');
    let bonusMessage = document.getElementById('bonusMessage');

    // Initialisation des variables pour le chronomètre et le score
    let debutJeu = Date.now();
    let timerInterval;
    let dernierTempsReponse = debutJeu;
    let score = 0;
    let correctResponses = 0;

    // Fonction pour générer une opération mathématique en fonction du niveau de difficulté
    function genererOperation(){
        let tables;
        switch(niveau){
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

        // Affiche les valeurs générées dans les éléments HTML correspondants
        num1.innerText = num1Value;
        num2.innerText = num2Value;
    }

    // Génère une opération si la variable 'niveau' est définie
    if(typeof niveau !== 'undefined'){
        genererOperation();
    }

    // Met à jour le lien vers la page de classement avec le score et l'identifiant de l'utilisateur
    function updatelien(){
        let url = "classe.php?score=" + score + "&userid=" + uniqid; 
        document.getElementById("classe").href = url; 
    }

    // Ajoute un gestionnaire d'événements pour le bouton de validation
    validerButton.addEventListener("click", function(event){
        event.preventDefault();
        validerReponse();
    });

    // Ajoute un gestionnaire d'événements pour la touche "Entrée" dans le champ de saisie
    inputField.addEventListener("keypress", function(event){
        if(event.key === "Enter"){
            event.preventDefault();
            validerReponse();
        }
    });

    // Affiche le temps écoulé dans le format mm:ss
    function afficherTemps(secondes){
        const minutes = Math.floor(secondes / 60);
        const secondesRestantes = secondes % 60;
        const tempsFormatte = `${minutes < 10 ? '0' : ''}${minutes}:${secondesRestantes < 10 ? '0' : ''}${secondesRestantes}`;
        timerElement.textContent = tempsFormatte;
    }

    // Démarre le chronomètre et met à jour l'affichage du temps écoulé toutes les secondes
    function demarrerChronometre(){
        clearInterval(timerInterval);
        timerInterval = setInterval(() => {
            let maintenant = Date.now();
            let tempsEcoule = Math.floor((maintenant - debutJeu) / 1000);
            afficherTemps(tempsEcoule);
        }, 1000);
    }

    // Valide la réponse de l'utilisateur et met à jour le score et l'affichage
    function validerReponse(){
        let reponse = parseInt(inputField.value);
        let correctResult = parseInt(num1.innerText) * parseInt(num2.innerText);
        let tempsReponse = Math.floor((Date.now() - dernierTempsReponse) / 1000);

        // Calcul du score à ajouter en fonction du temps de réponse
        if(reponse === correctResult){
            let scoreToAdd = 0;
            if(tempsReponse < 3){
                scoreToAdd = 3;
            }
            else if(tempsReponse >= 3 && tempsReponse < 5){
                scoreToAdd = 2;
            }
            else{
                scoreToAdd = 1;
            }

            // Double le score si 10 réponses correctes d'affilée sont obtenues
            if(correctResponses >= 9){
                scoreToAdd *= 2;
                bonusMessage.innerText = "Bonus x2 activé !";
            }
            score += scoreToAdd;
            correctResponses++;
            resultatPara.innerText = "Bonne réponse !";
        }

        // Réinitialise les réponses correctes consécutives en cas de mauvaise réponse et calcul du score à enlever en fonction du temps de réponse
        else{
            resultatPara.innerText = "Mauvaise réponse.";
            correctResponses = 0; 
            let scoreToRemove = 0;
            if(tempsReponse < 3){
                scoreToRemove = -1;
            }
            else if(tempsReponse >= 3 && tempsReponse < 5){
                scoreToRemove = -2;
            }
            else{
                scoreToRemove = -3;
            }
            score += scoreToRemove;
            bonusMessage.innerText = ""; 
        }

        // Met à jour l'affichage du score
        scoreDisplay.textContent = score;
        inputField.value = '';
        genererOperation();
        dernierTempsReponse = Date.now();
        updatelien();
    }

    // Démarre le chronomètre et génère une opération au chargement de la page
    demarrerChronometre();
    genererOperation();
});
