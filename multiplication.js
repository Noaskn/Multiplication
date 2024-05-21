document.addEventListener("DOMContentLoaded", function(){

    // Stockage des éléments HTML nécessaires
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
    let finDuJeuButton = document.getElementById("finDuJeu");
    let timerElement = document.getElementById('timer');

    // Initialisation des variables pour le chronomètre et le score
    let debutJeu = Date.now();
    let timerInterval;
    let score = 0;
    let niveau = "";
    let dernierTempsReponse = debutJeu;
    let correctResponses = 0;
    let derniersIncorrects = [];

    // Ajoute un gestionnaire d'événements pour la touche "Entrée" dans le champ de saisie
    document.getElementById("input").addEventListener("keypress", function(event){
        if(event.key === "Enter"){
            event.preventDefault();
            validerButton.click();
        }
    });

    // Fonction pour générer une opération basée sur le niveau choisi
    function genererOperation(tables){
        let choix = Math.random();

        // 90% de chances de choisir une opération normale
        if(choix < 0.9){
            let n = tables[Math.floor(Math.random() * tables.length)];
            let m = Math.floor(Math.random() * 10) + 1;
            return { num1: n, num2: m };
        }

        // 10% de chances de choisir une opération incorrecte précédente
        else if(derniersIncorrects.length > 0){
            let indexIncorrect = Math.floor(Math.random() * derniersIncorrects.length);
            return derniersIncorrects[indexIncorrect];
        }

        // Si pas d'opération incorrecte précédente, choisir une opération normale
        else{
            let n = tables[Math.floor(Math.random() * tables.length)];
            let m = Math.floor(Math.random() * 10) + 1;
            return { num1: n, num2: m };
        }
    }

    // Fonction pour le niveau facile
    function facile(){
        let operation = genererOperation([1, 2, 10]);
        num1.innerText = operation.num1;
        num2.innerText = operation.num2;
        demarrerChronometre();
    }

    // Fonction pour le niveau intermédiaire
    function intermediaire(){
        let operation = genererOperation([5, 3]);
        num1.innerText = operation.num1;
        num2.innerText = operation.num2;
        demarrerChronometre();
    }

    // Fonction pour le niveau difficile
    function difficile(){
        let operation = genererOperation([4, 6, 7, 8, 9]);
        num1.innerText = operation.num1;
        num2.innerText = operation.num2;
        demarrerChronometre();
    }

    // Ajoute un événement sur le bouton "facile" pour démarrer le jeu en mode facile
    facileButton.addEventListener("click", function(){
        appDiv.style.display = "block";
        choixNiveauDiv.style.display = "none";
        facile();
        niveau = "facile";
        if(!timerInterval){
            demarrerChronometre();
        }
    });

    // Ajoute un événement sur le bouton "intermediaire" pour démarrer le jeu en mode intermédiaire
    intermediaireButton.addEventListener("click", function(){
        appDiv.style.display = "block";
        choixNiveauDiv.style.display = "none";
        intermediaire();
        niveau = "intermediaire";
        if(!timerInterval){
            demarrerChronometre();
        }
    });

    // Ajoute un événement sur le bouton "difficile" pour démarrer le jeu en mode difficile
    difficileButton.addEventListener("click", function(){
        appDiv.style.display = "block";
        choixNiveauDiv.style.display = "none";
        difficile();
        niveau = "difficile";
        if(!timerInterval){
            demarrerChronometre();
        }
    });

    // Ajoute un événement sur le bouton "reselectNiveau" pour revenir à la sélection de niveau
    reselectNiveauButton.addEventListener("click", function(){
        appDiv.style.display = "none";
        choixNiveauDiv.style.display = "block";
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

    // Met à jour le lien vers la page de classement avec le score et l'identifiant de l'utilisateur
    function updatelien(){
        let url = "score.php?score=" + score + "&userid=" + uniqid; 
        document.getElementById("finDuJeu").href = url; 
    }

    // Valide la réponse de l'utilisateur et met à jour le score et l'affichage
    validerButton.addEventListener("click", function(){
        event.preventDefault();
        let reponse = document.getElementById("input").value;
        let resultat = parseInt(reponse) === parseInt(num1.innerText) * parseInt(num2.innerText);
        let tempsReponse = Math.floor((Date.now() - dernierTempsReponse) / 1000);
        clearInterval(timerInterval);

        // Calcul du score à ajouter en fonction du temps de réponse
        if(resultat){
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
                document.getElementById("bonusMessage").innerText = "Bonus x2 activé !";
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
            document.getElementById("bonusMessage").innerText = ""; 
            derniersIncorrects.push({ num1: parseInt(num1.innerText), num2: parseInt(num2.innerText) });
        }

        // Met à jour l'affichage du score
        scoreDisplay.innerText = score;
        document.getElementById("input").value = "";
        demarrerChronometre();
        dernierTempsReponse = Date.now();
        updatelien();

        // Génère une nouvelle opération en fonction du niveau actuel
        if(niveau === "facile"){
            facile();
        }
        else if(niveau === "intermediaire"){
            intermediaire();
        }
        else if(niveau === "difficile"){
            difficile();
        }
    });
});
