document.addEventListener("DOMContentLoaded", function() {
    let num1 = document.getElementById("num1");
    let num2 = document.getElementById("num2");
    let facileButton = document.getElementById("facile");
    let intermediaireButton = document.getElementById("intermediaire");
    let difficileButton = document.getElementById("difficile");

    function facile() {
        let n = Math.floor(Math.random()*2) + 1;
        let m = Math.floor(Math.random()*2) + 1;
        num1.innerText = n;
        num2.innerText = m;
    }

    function intermediaire() {
        let n = Math.floor(Math.random()*5) + 2;
        let m = Math.floor(Math.random()*5) + 2;
        num1.innerText = n;
        num2.innerText = m;
    }

    function difficile() {
        let n = Math.floor(Math.random()*10) + 5;
        let m = Math.floor(Math.random()*10) + 5;
        num1.innerText = n;
        num2.innerText = m;
    }

    facileButton.addEventListener("click", function() {
        facile();
    });

    intermediaireButton.addEventListener("click", function() {
        intermediaire();
    });

    difficileButton.addEventListener("click", function() {
        difficile();
    });
});
