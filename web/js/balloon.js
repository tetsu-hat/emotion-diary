document.addEventListener("DOMContentLoaded", function(){
  //感情を選択するselectを取得
  let emotionsSelect = document.getElementsByClassName('emotionsSelect');
  let balloon = function(i){
    if(document.getElementById('emotions-tooltips') != null){
      let tooltip = document.getElementById('emotions-tooltips');
      tooltip.parentNode.removeChild(tooltip);
    }
    let select = document.getElementsByClassName('emotionsSelect');
    let obj = select[i];
    let element = document.createElement("div");
    let contentSelectArea = document.getElementsByClassName('contentSelectArea');
    element.innerHTML = obj[obj.value].getAttribute('data-text');
    element.id = "emotions-tooltips";
    console.log(element);
    contentSelectArea[i].insertAdjacentElement( "afterend", element);
  }
  emotionsSelect[0].addEventListener("change",function(){
    balloon(0);
  },false);
  emotionsSelect[1].addEventListener("change",function(){
    balloon(1);
  },false);
  emotionsSelect[2].addEventListener("change",function(){
    balloon(2);
  },false);
  emotionsSelect[3].addEventListener("change",function(){
    balloon(3);
  },false);
  emotionsSelect[4].addEventListener("change",function(){
    balloon(4);
  },false);
  let body = document.body;
  body.addEventListener("click", function(){
    if(document.getElementById('emotions-tooltips') != null){
      let tooltip = document.getElementById('emotions-tooltips');
      tooltip.parentNode.removeChild(tooltip);
    }
  },false);
},false);
