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

function init_barchart(json_barchart){
  //init data
  var json = json_barchart;
  //end
  var json2 = {
      
  };
    //init BarChart
    var barChart = new $jit.BarChart({
      //id of the visualization container
      injectInto: 'infovis2',
      //whether to add animations
      animate: true,
      //horizontal or vertical barcharts
      orientation: 'vertical',
      //bars separation
      barsOffset: 10,
      //visualization offset
      Margin: {
        top:5,
        left: 5,
        right: 5,
        bottom:5
      },
      //labels offset position
      labelOffset: 5,
      //bars style
      type: 'stacked',
      //whether to show the aggregation of the values
      showAggregates:false,
      //whether to show the labels for the bars
      showLabels:true,
      //labels style
      Label: {
        type: labelType, //Native or HTML
        size: 13,
        //family: 'Arial',
        color: 'black'
      },
      //add tooltips
      Tips: {
        enable: true,
        onShow: function(tip, elem) {
          tip.innerHTML = "<b>" + elem.label + "</b>: " + format_my_number(elem.value, 0, ".", ",") + " €";
        }
      }
    });
    //load JSON data.
    barChart.loadJSON(json);
    //end
    var list = $jit.id('id-list'),
        button = $jit.id('update'),
        orn = $jit.id('switch-orientation');
    //update json on click 'Update Data'
    if(button) {
	    $jit.util.addEvent(button, 'click', function() {
	      var util = $jit.util;
	      if(util.hasClass(button, 'gray')) return;
	      util.removeClass(button, 'white');
	      util.addClass(button, 'gray');
	      barChart.updateJSON(json2);
	    });
    }
    //dynamically add legend to list
    var legend = barChart.getLegend(),
        listItems = [];
    for(var name in legend) {
      listItems.push('<div class=\'query-color\' style=\'background-color:'
          + legend[name] +'\'>&nbsp;</div>' + name);
    }
    if(list) {
    	list.innerHTML = '<li>' + listItems.join('</li><li>') + '</li>';
    }
}
