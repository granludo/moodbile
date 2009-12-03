/**
 * touch for jQuery
 * 
 * Copyright (c) 2008 Peter Schmalfeldt (ManifestInteractive.com) <manifestinteractive@gmail.com>
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details. 
 *
 * @license http://www.gnu.org/licenses/gpl.html 
 * @project jquery.touch
 */

// DEFINE DEFAULT VARIABLES
var _intval=null, _intval2=null, _animy=null, _target=null;
var _dragging=false, _mask=false;
var _height=0, _top=0, _yspeed=0, _lastTop=0, _curTop=0, _initTop=0, _parentHeight=0, _thisHeight=0;

jQuery.fn.flick = function() {

	// ADD METHODS TO OBJECT
	this.each(function(){
		this.ontouchstart = touchstart;
		this.ontouchend = touchend;
		this.ontouchmove = touchmove;
	});
};

function touchstart(e){
	
	_target = this.id;
	_yspeed = 0;
	
	if(!_intval && !_mask) {
		_intval = setInterval(loadMask, 500);
		_intval2 = setInterval(removeMask, 500);
	}

	$(e.changedTouches).each(function(){
									  
		var tch = e.touches[0];
		
		_curTop = ($('#'+_target).css("top") == 'auto') ? tch.pageY : parseInt($('#'+_target).css("top"));
		_initTop = ($('#'+_target).css("top") == 'auto') ? tch.pageY : (tch.pageY-parseInt($('#'+_target).css("top")));
		_thisHeight = $('#'+_target).height();
		_parentHeight = $('#'+_target).parent().height();
		
		$('#'+_target).parent().css({ position: "relative" });
		$('#'+_target).css({ position: "relative" });
		
		if(!_dragging){
			_top = (tch.pageY-_curTop);
			_dragging = [_top];
		}
	});
};
function touchmove(e){

	if(_dragging) {	
		_lastTop = (isNaN(parseInt($('#'+_target).css("top")))) ? 0:parseInt($('#'+_target).css("top"));
	}
	$(e.changedTouches).each(function(){
									  
		var tch = e.touches[0];
		
		e.preventDefault();
		
		_top = (tch.pageY-_initTop);
		
		if(_dragging) {
			_yspeed = Math.round((_yspeed + Math.round( _top - _lastTop))/1.25);
			$('#'+_target).css({ top: _top+"px" });
			
			if(_mask) clearInterval(_intval2);
			e.cancelBubble=true;
			e.stopPropagation();
		}
	});
};
function touchend(e){
	
	if(_mask) _intval2 = setInterval(removeMask, 500);
	
	$(e.changedTouches).each(function(){
		if(!e.targetTouches.length){
			
			_dragging = false;
			_lastTop = (isNaN(parseInt($('#'+_target).css("top")))) ? 0:parseInt($('#'+_target).css("top"));
			_animy = (_lastTop+_yspeed)+"px";

			if(parseInt(_animy) > 0) _animy = "0px";
			else if(parseInt(_animy) < (_parentHeight-_thisHeight)) _animy = (_parentHeight-_thisHeight)+"px";
			$('#'+_target).animate({ top: _animy }, "fast");	
		}
	});	
};
function removeMask(){
	clearInterval(_intval2);
	$('#touchMoveLayerMask').remove();
	_intval2 = null;
	_mask = false;
};
function loadMask(){
	clearInterval(_intval);
	_intval = null;
	if(!_mask){
		$('#'+_target).prepend("<div id='touchMoveLayerMask' style='display: block; width: 100%; height: 100%; position: absolute; top: 0px; left: 0px; background-color: #000; opacity:0; z-index: 9999;'>&nbsp;</div>");
		_mask = true;
	}
}
