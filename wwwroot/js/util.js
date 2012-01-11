"use strict";

function editingMessage(msg, options) {
	$.jGrowl(msg, options);
}
function editingMessageError(msg, options) {
	$.jGrowl(msg, options);
}

function getControllerHtml(url, params, callback) {
  $.get(url, params, function(context) {
    var contextObj = jQuery.parseJSON(context);
    if (contextObj.code == 'OK') {
      if (callback != null) {
//        var extra = jQuery.parseJSON(contextObj.extra);
        callback(contextObj.data, contextObj.extra);
      }
      if (contextObj.message != null) {
        editingMessage(contextObj.message);
      }
    }
    else editingMessageError(contextObj.message);
  });
}

function getControllerJson(url, params, callback) {
  $.get(url, $.param(params), function(context) {
    var contextObj = jQuery.parseJSON(context);
    if (contextObj.code == 'OK') {
      if (callback != null) {
        var jsonObj = jQuery.parseJSON(contextObj.data);
        var extra = jQuery.parseJSON(contextObj.extra);
        callback(jsonObj, extra);
      }
    }
    else editingMessageError(contextObj.message);
  });
}
