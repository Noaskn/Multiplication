// Fonction pour générer un code aléatoire de 8 chiffres
function genererCode(){
            var code = "";
            var chiffres = "0123456789";
            
            // Boucle pour générer 8 chiffres aléatoires
            for(var i = 0; i < 8; i++){
                code += chiffres.charAt(Math.floor(Math.random() * chiffres.length));
            }

            // Affectation du code généré à l'élément avec l'ID "code"
            document.getElementById("code").value = code;
        }

// Fonction pour enregistrer le code généré avec le niveau sélectionné
function enregistrer(){
    var code = document.getElementById("code").value;
    var niveau = document.querySelector('input[name="niveau"]:checked').value;

    // Vérification que le code n'est pas vide
    if(code.trim() === ""){
        alert("Veuillez générer un code avant de continuer.");
        return; 
    }

     // Création d'une requête XMLHttpRequest pour envoyer les données au serveur
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function(){

        // Vérification de l'état de la requête
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status === 200){

                // Affichage du message de succès si l'enregistrement a réussi
                var messageDiv = document.getElementById("message");
                messageDiv.innerText = "Le code a été enregistré avec succès.";
                messageDiv.style.display = "block";
            }
            else{

                // Affichage d'une alerte en cas d'erreur lors de l'enregistrement
                alert('Une erreur s\'est produite lors de l\'enregistrement.');
            }
        }
    };

    // Configuration de la requête pour un envoi POST
    xhr.open("POST", "professeur.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // Envoi des données (code et niveau) au serveur
    xhr.send("code=" + code + "&niveau=" + niveau);
}
