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
    contentSelectArea[i].insertAdjacentElement( "afterend", element);
  }
  for(let i=0; i < emotionsSelect.length; i++){
    emotionsSelect[i].addEventListener("change",function(){
      balloon(i);
    },false);
  }
  let body = document.body;
  body.addEventListener("click", function(){
    if(document.getElementById('emotions-tooltips') != null){
      let tooltip = document.getElementById('emotions-tooltips');
      tooltip.parentNode.removeChild(tooltip);
    }
  },false);
},false);
