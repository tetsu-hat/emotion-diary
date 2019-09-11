document.addEventListener("DOMContentLoaded", function(){
let hamburgerBtn = document.getElementById('hamburgerBtn');
let sideMenu = document.getElementById('sideMenu');
let surface = document.getElementById('surface');

hamburgerBtn.addEventListener('click',openClose);

function openClose(){
  if(sideMenu.classList.contains('nav-open')){
    navClose();
  }else{
    navOpen();
  };
};

function navClose(){
  sideMenu.classList.remove('nav-open');
  surface.classList.remove('shadow');
};

function navOpen(){
sideMenu.classList.add('nav-open');
surface.classList.add('shadow');
};
}, false);
