document.addEventListener("DOMContentLoaded", function() {
    let num1 = document.getElementById("num1");
    let num2 = document.getElementById("num2");
    let facileButton = document.getElementById("facile");
    let intermediaireButton = document.getElementById("intermediaire");
    let difficileButton = document.getElementById("difficile");
	let choixNiveauDiv = document.getElementById("choixNiveau");
    let appDiv = document.querySelector(".app");

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
    });

    intermediaireButton.addEventListener("click", function() {
        appDiv.style.display = "block";
        choixNiveauDiv.style.display = "none";
        intermediaire();
    });

    difficileButton.addEventListener("click", function() {
        appDiv.style.display = "block";
        choixNiveauDiv.style.display = "none";
        difficile();
    });
});
