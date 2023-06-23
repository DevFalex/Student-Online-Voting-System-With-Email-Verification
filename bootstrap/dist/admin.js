
  window.onload=onInit();
  window.addEventListener("resize",onInit,false);
  function onInit(){ 
    content.style.height=(window.innerHeight-navbarz.offsetHeight)+"px";
    sidebarz.style.height=(content.offsetHeight+60)+"px";
  }
  function toggleBurger(){ 
    document.getElementsByClassName('burgerx')[0].classList.toggle('open');
    sidebarz_mobile.classList.toggle('show');
  }
  
  function redirectTo(url){
    window.location=url;
}     