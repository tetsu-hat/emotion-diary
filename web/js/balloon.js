document.addEventListener("DOMContentLoaded", function(){
  //感情を選択するselectを取得
  let emotionsSelect = document.getElementsByClassName('emotionsSelect');
  /////以下が1セット//////////
  emotionsSelect[0].addEventListener("change", function(){
    if(document.getElementById('emotions-tooltips') != null){
      let tooltip = document.getElementById('emotions-tooltips');
      tooltip.parentNode.removeChild(tooltip);
    }
    console.log('change発火');
    let element = document.createElement("div");
    let contentSelectArea = document.getElementsByClassName('contentSelectArea');
    element.innerHTML = this[this.value].getAttribute('data-text');
    element.id = "emotions-tooltips";
    console.log(element);
    contentSelectArea[0].insertAdjacentElement( "afterend", element );
  }, false);
  /////以上が1セット//////////

  /////以下がセット//////////
  emotionsSelect[1].addEventListener("change", function(){
    if(document.getElementById('emotions-tooltips') != null){
      let tooltip = document.getElementById('emotions-tooltips');
      tooltip.parentNode.removeChild(tooltip);
    }
    console.log('change発火');
    let element = document.createElement("div");
    let contentSelectArea = document.getElementsByClassName('contentSelectArea');
    element.innerHTML = this[this.value].getAttribute('data-text');
    element.id = "emotions-tooltips";
    console.log(element);
    contentSelectArea[1].insertAdjacentElement( "afterend", element );
  }, false);
  /////以上がセット//////////

  /////以下がセット//////////
  emotionsSelect[2].addEventListener("change", function(){
    if(document.getElementById('emotions-tooltips') != null){
      let tooltip = document.getElementById('emotions-tooltips');
      tooltip.parentNode.removeChild(tooltip);
    }
    console.log('change発火');
    let element = document.createElement("div");
    let contentSelectArea = document.getElementsByClassName('contentSelectArea');
    element.innerHTML = this[this.value].getAttribute('data-text');
    element.id = "emotions-tooltips";
    console.log(element);
    contentSelectArea[2].insertAdjacentElement( "afterend", element );
  }, false);
  /////以上がセット//////////

  /////以下がセット//////////
  emotionsSelect[3].addEventListener("change", function(){
    if(document.getElementById('emotions-tooltips') != null){
      let tooltip = document.getElementById('emotions-tooltips');
      tooltip.parentNode.removeChild(tooltip);
    }
    console.log('change発火');
    let element = document.createElement("div");
    let contentSelectArea = document.getElementsByClassName('contentSelectArea');
    element.innerHTML = this[this.value].getAttribute('data-text');
    element.id = "emotions-tooltips";
    console.log(element);
    contentSelectArea[3].insertAdjacentElement( "afterend", element );
  }, false);
  /////以上がセット//////////

  /////以下がセット//////////
  emotionsSelect[4].addEventListener("change", function(){
    if(document.getElementById('emotions-tooltips') != null){
      let tooltip = document.getElementById('emotions-tooltips');
      tooltip.parentNode.removeChild(tooltip);
    }
    console.log('change発火');
    let element = document.createElement("div");
    let contentSelectArea = document.getElementsByClassName('contentSelectArea');
    element.innerHTML = this[this.value].getAttribute('data-text');
    element.id = "emotions-tooltips";
    console.log(element);
    contentSelectArea[4].insertAdjacentElement( "afterend", element );
  }, false);
  /////以上がセット//////////

  let body = document.body;

  body.addEventListener("click", function(){
    if(document.getElementById('emotions-tooltips') != null){
      let tooltip = document.getElementById('emotions-tooltips');
      tooltip.parentNode.removeChild(tooltip);
    }
  },false);

},false);
