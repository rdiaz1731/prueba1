/***
 * Contains basic SlickGrid formatters.
 * 
 * NOTE:  These are merely examples.  You will most likely need to implement something more
 *        robust/extensible/localizable/etc. for your use!
 * 
 * @module Formatters
 * @namespace Slick
 */

(function ($) {
  // register namespace
  $.extend(true, window, {
    "Slick": {
      "Formatters": {
          "Date":DateFormatter,
        "PercentComplete": PercentCompleteFormatter,
        "PercentCompleteBar": PercentCompleteBarFormatter,
        "YesNo": YesNoFormatter,
        "Checkmark": CheckmarkFormatter
      }
    }
  });
  function DateFormatter(row, cell, value, columnDef, dataContext) {
      params = value.split("-");
      date = new Date(params[0],params[1]-1,params[2]);
      return ((date.getDate()<10)?"0"+date.getDate():date.getDate())+"/"+((date.getMonth()<8)?"0"+(date.getMonth()+1):(date.getMonth()+1))+"/"+(date.getYear()+1900);
  }
  function PercentCompleteFormatter(row, cell, value, columnDef, dataContext) {
    if (value == null || value === "") {
      return "-";
    } else if (value < 50) {
      return "<span style='color:red;font-weight:bold;'>" + value + "%</span>";
    } else {
      return "<span style='color:green'>" + value + "%</span>";
    }
  }

  function PercentCompleteBarFormatter(row, cell, value, columnDef, dataContext) {
    if (value == null || value === "") {
      return "";
    }

    var color;

    if (value < 30) {
      color = "red";
    } else if (value < 70) {
      color = "silver";
    } else {
      color = "green";
    }

    return "<span class='percent-complete-bar' style='background:" + color + ";width:" + value + "%'></span>";
  }

  function YesNoFormatter(row, cell, value, columnDef, dataContext) {
    return value ? "Yes" : "No";
  }

  function CheckmarkFormatter(row, cell, value, columnDef, dataContext) {
	if(columnDef.imageTrue!=null)
		imageTrue="<img src='"+columnDef.imageTrue+"'>";
	else
		imageTrue="<img src='css/images/tick.png'>";
	if(columnDef.imageFalse!=null)
		imageFalse="<img src='"+columnDef.imageFalse+"'>";
	else
		imageFalse="<img src='css/images/tick.png'>";
    return value ? imageTrue : imageFalse;
  }
})(jQuery);
