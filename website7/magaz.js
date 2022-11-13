var btn = document.querySelector('#btn'),
    out = document.querySelector('#out'),
    weight = document.querySelector('#weight'),
    vol = document.querySelector('#vol'),
    load = document.querySelector('#load'),
    del = document.querySelector('#del'),
    frag = document.querySelector('#frag'),
    range = document.querySelector('#range'),
    rasstoyanie = document.querySelector('.rasstoyanie').innerHTML = 500;
    weight = document.querySelector('#weight'),
    vol = document.querySelector('#vol'),
    kg = 5.5,
    kub = 3500,
    km = 2;

 // range slider
    range.onchange = function(){
      var rasstoyanie = document.querySelector('.rasstoyanie').innerHTML = range.value;
    }
    
    $(".js-range-slider").ionRangeSlider({
        skin:"round",
        min: 100,
        max: 1000,
        step: 10,
        grid: true
    });
  // Basic function  
btn.onclick = function(){
  if (weight.value != '' && vol.value != '') {
      if (load.checked){ 
          load.value = 1500; }
        else 
        {
          load.value = 0;
      }
      if (del.checked){ 
          del.value = 3500; }
        else 
        {
          del.value = 0;
      }
      if (frag.checked){ 
          frag.value = 2000; }
        else 
        {
          frag.value = 0;
      }             
 
var sum = (weight.value * kg) + (vol.value * kub) + Number(load.value) + + Number(del.value) + Number(frag.value) + (range.value * km);
      out.innerHTML = sum;
  }else{
alert('Введите вес и объем груза');
}
}
