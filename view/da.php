<?php

/**
 * Domain availability
 */

?><style>
*,html{margin:0;padding:0;}
</style>
<p>
  <span>Extension: </span>
  <input id="extension" type="text" value="io">
  <button onclick="CHANGE_EXT(document.getElementById('extension').value)">change extension</button>
</p>
<p>
  <span>Domain start: </span>
  <input id="dstart" type="text" value="a">
  <button onclick="CHANGE_DSTART(document.getElementById('dstart').value)">change start domain</button>
</p>
<p>
  <span>Domain end: </span>
  <input id="dend" type="text" value="aaaaaaaa">
  <button onclick="CHANGE_DEND(document.getElementById('dend').value)">change end domain</button>
</p>
<button onclick="START_PROCESS()">start</button>
<button onclick="STOP_PROCESS()">stop</button>
<button onclick="CLEAR_PROCESS()">clear</button>
<div style="position:relative;margin-top:20px;">
  <div id="success_target" style="width:50%;float:left;"></div>
  <div id="target" style="width:50%;float:right;"></div>
</div>



<script type="text/javascript" src="https://cdn.rawgit.com/aaron/window.prototype.ajax/70cc513/index.min.js"></script>
<script>
(function(w){
  var d = w.document;
  var t = d.getElementById('target');
  var st = d.getElementById('success_target');
  var ext = 'io';
  var stop = false;
  var dstart = 'a';
  var dend = 'aaaaaaaa';
  var CURRENT_DOMAIN = dstart;
  var wAjax = w.ajax;

  w.CHANGE_EXT = function(exte){
    ext = exte;
  };
  w.CHANGE_DSTART = function(start){
    dstart = start;
  };
  w.CHANGE_DEND = function(end){
    dend = end;
  };
  w.STOP_PROCESS = function(){
    stop = true;
  };
  w.CLEAR_PROCESS = function(){
    t.innerHTML = '';
  };

  var createTextNode = function(_text,text_,text__){
    var s = d.createElement('p');
    s.innerHTML = _text + '<span style="color:green;">'+text_+'</span><span style="font-size:10px">'+text__+'</span>';

    return s;
  };

  w.START_PROCESS = function(){
    INIT_PROCESS();
  };
  Array.prototype.base_reverse = function(){
    var input = this;
    var ret = new Array;
    for(var i=input.length-1;i>=0;i--)ret.push(input[i]);
    return ret;
  };
  var CHANGE_DOMAIN = function(index){
    var s = 'abcdefghijklmnopqrstuvwxyz-0123456789';
    var sa = s.split('');
    var sar = sa.base_reverse();

    var CURRENT_DOMAIN_ARRAY = CURRENT_DOMAIN.split('');
    var CURRENT_DOMAIN_ARRAY_R = CURRENT_DOMAIN_ARRAY.base_reverse();

    var _index = index;

    index = (index === -1) ? CURRENT_DOMAIN_ARRAY.length-1 : index;

    if(CURRENT_DOMAIN_ARRAY[index] === sar[0]){
      CURRENT_DOMAIN_ARRAY[index] = sa[0];
      CURRENT_DOMAIN = CURRENT_DOMAIN_ARRAY.join('');

      if(index-1 < 0){
        CURRENT_DOMAIN = sa[0] + CURRENT_DOMAIN;
      }else{
        CHANGE_DOMAIN(index-1);
      }

    }else{
      for(var k=0;k<sa.length;k++){
        if(sa[k] === CURRENT_DOMAIN_ARRAY[index]){
          CURRENT_DOMAIN_ARRAY[index] = sa[k+1];
          CURRENT_DOMAIN = CURRENT_DOMAIN_ARRAY.join('');
          break;
        }
      }
    }
  };

  var INIT_PROCESS = function(){
    if(stop){
      stop = false;
      return;
    }
    if(CURRENT_DOMAIN === dend)stop = true;

    wAjax.get('/da_',{domain:CURRENT_DOMAIN,extension:ext},function(r){
      r = JSON.parse(r);

      if(t.innerHTML === ''){
        t.appendChild( createTextNode(r['domain']+': ',r['response']+' - ',r['message']) );
      }else{
        t.insertBefore( createTextNode(r['domain']+': ',r['response']+' - ',r['message']), t.childNodes[0] );
      }

      if(r.response === 'success'){
        if(st.innerHTML === ''){
          st.appendChild( createTextNode(r['domain']+': ',r['response']+' - ',r['message']) );
        }else{
          st.insertBefore( createTextNode(r['domain']+': ',r['response']+' - ',r['message']), st.childNodes[0] );
        }
      }


      CHANGE_DOMAIN(-1);
      INIT_PROCESS();
    });
  };
})(window);
</script>
