function format_my_number(num, decPlaces, thouSeparator, decSeparator) {
    var n = num;
    decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
    decSeparator = decSeparator == undefined ? "." : decSeparator,
    thouSeparator = thouSeparator == undefined ? "," : thouSeparator,
    sign = n < 0 ? "-" : "",
    i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
    j = (j = i.length) > 3 ? j % 3 : 0;
    return sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
}

var labelType, useGradients, nativeTextSupport, animate;

(function() {
  var ua = navigator.userAgent,
      iStuff = ua.match(/iPhone/i) || ua.match(/iPad/i),
      typeOfCanvas = typeof HTMLCanvasElement,
      nativeCanvasSupport = (typeOfCanvas == 'object' || typeOfCanvas == 'function'),
      textSupport = nativeCanvasSupport 
        && (typeof document.createElement('canvas').getContext('2d').fillText == 'function');
  //I'm setting this based on the fact that ExCanvas provides text support for IE
  //and that as of today iPhone/iPad current text support is lame
  labelType = (!nativeCanvasSupport || (textSupport && !iStuff))? 'Native' : 'HTML';
  nativeTextSupport = labelType == 'Native';
  useGradients = nativeCanvasSupport;
  animate = !(iStuff || !nativeCanvasSupport);
})();

var Log = {
  elem: false,
  write: function(text){
    if (!this.elem) 
      this.elem = document.getElementById('log');
    this.elem.innerHTML = text;
    this.elem.style.left = (500 - this.elem.offsetWidth / 2) + 'px';
  }
};


function init(json, json_barchart, json_kat){
  //init data
  //var json = json;
  //end
  //init TreeMap
  var tm = new $jit.TM.Squarified({
    //where to inject the visualization
    injectInto: 'infovis',
    //parent box title heights
    titleHeight: 45,
    //enable animations
    animate: animate,
    //box offsets
    offset: 1,
    //Attach left and right click events
    Events: {
      enable: true
      //onClick: function(node) {
		/*console.log(node);
		if(node != undefined) {
			if(node.data["entry_key"] != undefined) {
        		window.location.href = node.data["entry_key"];
			}
		}
        //if(node) tm.enter(node);*/
      //},
      //onRightClick: function() {
		/*console.log(node);
		if(node != undefined) {
			if(node.data["entry_key_parent"] != undefined) {
        		window.location.href = node.data["entry_key_parent"];
			}
		}*/
        //tm.out();
      //}
    },
    duration: 1000,
    //Enable tips
    Tips: {
      enable: true,
      //add positioning offsets
      offsetX: 20,
      offsetY: 20,
      //implement the onShow method to
      //add content to the tooltip when a node
      //is hovered
      onShow: function(tip, node, isLeaf, domElement) {
        	var html = "<div class=\"tip-title\">" + node.name + "</div><div class=\"tip-text\">";
        var data = node.data;
        if(data.value) {
          html += "Wert: " + format_my_number(data.value, 0, ".", ",") + " €";
        }
        /*if(data.image) {
          html += "<img src=\""+ data.image +"\" class=\"album\" />";
        }*/
        tip.innerHTML =  html; 
      }  
    },
    //Add the name of the node in the correponding label
    //This method is called once, on label creation.
    onCreateLabel: function(domElement, node){
		domElement.onclick = function() {
				if(node.data["entry_key"] != undefined) {
        		window.location.href = node.data["entry_key"];
				}
        };

		domElement.onrightclick = function() {
				if(node.data["entry_key_parent"] != undefined) {
        		window.location.href = node.data["entry_key_parent"];
				}
        };
		domElement.oncontextmenu = function() {
				if(node.data["entry_key_parent"] != undefined) {
        		window.location.href = node.data["entry_key_parent"];
				}
        };

        domElement.innerHTML = node.name;
        var style = domElement.style;
        style.display = '';
        style.border = '1px solid transparent';
		if(node.id == "root") {
          	//style.border = '1px solid #FF8800';
          	//style.backgroundColor = '#FF8800';
			domElement.onclick = function() {
				if(node.data["entry_key_parent"] != undefined) {
        		window.location.href = node.data["entry_key_parent"];
				}
   		    };
			domElement.onrightclick = function() {
				if(node.data["entry_key_parent"] != undefined) {
        		window.location.href = node.data["entry_key_parent"];
				}
   		    };
			domElement.onrightclick = function() {
				if(node.data["entry_key_parent"] != undefined) {
        		window.location.href = node.data["entry_key_parent"];
				}
   		    };
			domElement.oncontextmenu = function() {
				if(node.data["entry_key_parent"] != undefined) {
        		window.location.href = node.data["entry_key_parent"];
				}
        	};
			return;
		}
		padding = style.padding;
		bc = style.backgroundColor;
        domElement.onmouseover = function() {
          //style.border = '1px solid #9FD4FF';
		  style.padding = '0px';
		  style.backgroundColor = '#FF8800';
        };
        domElement.onmouseout = function() {
          //style.border = '1px solid transparent';
		  style.padding = padding;
		  style.backgroundColor = bc;
        };
    }
  });
  tm.loadJSON(json);
  tm.refresh();
init_kat(json_kat, json_barchart);
  //end
  //add events to radio buttons
  var sq = $jit.id('r-sq'),
      st = $jit.id('r-st'),
      sd = $jit.id('r-sd');
  var util = $jit.util;
  util.addEvent(sq, 'change', function() {
    if(!sq.checked) return;
    util.extend(tm, new $jit.Layouts.TM.Squarified);
    tm.refresh();
  });
  util.addEvent(st, 'change', function() {
    if(!st.checked) return;
    util.extend(tm, new $jit.Layouts.TM.Strip);
    tm.layout.orientation = "v";
    tm.refresh();
  });
  util.addEvent(sd, 'change', function() {
    if(!sd.checked) return;
    util.extend(tm, new $jit.Layouts.TM.SliceAndDice);
    tm.layout.orientation = "v";
    tm.refresh();
  });
  //add event to the back button
  var back = $jit.id('back');
  $jit.util.addEvent(back, 'click', function() {
    tm.out();
  });

}
