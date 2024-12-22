//start product_details page -> increase, decrease items 
var increaseBtn = document.getElementsByClassName('btn-increase');
var decreaseBtn = document.getElementsByClassName('btn-decrease');
var itemsToBuy = document.getElementById('num_of_items');

function increaseValueBtn(){
    var numero = Number(itemsToBuy.value)+1;
    itemsToBuy.value = numero;
}
function decreaseValueBtn(){
    var numero = Number(itemsToBuy.value)-1;
    numero = numero < 0 ? 0 : numero;
    itemsToBuy.value = numero;
}
//end product_details page -> increase, decrease items 
const hamBurger = document.querySelector(".toggle-btn");

hamBurger.addEventListener("click", function () {
  document.querySelector("#sidebar").classList.toggle("expand");
});
