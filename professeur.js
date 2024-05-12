function genererCode() {
            var code = "";
            var chiffres = "0123456789";
            for (var i = 0; i < 8; i++) {
                code += chiffres.charAt(Math.floor(Math.random() * chiffres.length));
            }
            document.getElementById("code").value = code;
        }

        function enregistrer() {
    var code = document.getElementById("code").value;
    var niveau = document.querySelector('input[name="niveau"]:checked').value;

    // Vérifier si le champ du code est vide
    if (code.trim() === "") {
        alert("Veuillez générer un code avant de continuer.");
        return; // Stoppe l'exécution de la fonction
    }

    // Envoyer les données uniquement si le champ du code n'est pas vide
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var messageDiv = document.getElementById("message");
                messageDiv.innerText = "Le code a été enregistré avec succès.";
                messageDiv.style.display = "block";
            } else {
                alert('Une erreur s\'est produite lors de l\'enregistrement.');
            }
        }
    };
    xhr.open("POST", "professeur.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("code=" + code + "&niveau=" + niveau);
}
