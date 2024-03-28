let num1 = document.getElementById("num1");
let num2 = document.getElementById("num2");

function setNum() {
    let n = Math.floor(Math.random()*10) + 1;
    let m = Math.floor(Math.random()*10) + 1;
    num1.innerText = n;
    num2.innerText = m;
}

document.addEventListener("DOMContentLoaded", function() {
    setNum();
});
