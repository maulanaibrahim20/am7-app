$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
!function(t){"function"==typeof define&&define.amd?define(t):t()}(function(){"use strict";function t(t,r){return function(t){if(Array.isArray(t))return t}(t)||function(t,e){var r=null==t?null:"undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(null==r)return;var n,o,a=[],l=!0,i=!1;try{for(r=r.call(t);!(l=(n=r.next()).done)&&(a.push(n.value),!e||a.length!==e);l=!0);}catch(t){i=!0,o=t}finally{try{l||null==r.return||r.return()}finally{if(i)throw o}}return a}(t,r)||function(t,r){if(!t)return;if("string"==typeof t)return e(t,r);var n=Object.prototype.toString.call(t).slice(8,-1);"Object"===n&&t.constructor&&(n=t.constructor.name);if("Map"===n||"Set"===n)return Array.from(t);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return e(t,r)}(t,r)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function e(t,e){(null==e||e>t.length)&&(e=t.length);for(var r=0,n=new Array(e);r<e;r++)n[r]=t[r];return n}for(var r={theme:{localStorage:"tablerTheme",default:"light"},"menu-position":{localStorage:"tablerMenuPosition",default:"top"},"menu-behavior":{localStorage:"tablerMenuBehavior",default:"sticky"},"container-layout":{localStorage:"tablerContainerLayout",default:"boxed"}},n={},o=0,a=Object.entries(r);o<a.length;o++){var l=t(a[o],2),i=l[0],c=l[1],u=localStorage.getItem(c.localStorage);n[i]=u||c.default}var f=function(){document.body.classList.remove("theme-dark","theme-light"),document.body.classList.add("theme-".concat(n.theme))};!function(){for(var t=window.location.search.substring(1).split("&"),e=0;e<t.length;e++){var o=t[e].split("="),a=o[0],l=o[1];r[a]&&(localStorage.setItem(r[a].localStorage,l),n[a]=l)}}(),f();var s=document.querySelector("#offcanvasSettings");s&&(s.addEventListener("submit",function(e){e.preventDefault(),function(e){for(var o=0,a=Object.entries(r);o<a.length;o++){var l=t(a[o],2),i=l[0],c=l[1],u=e.querySelector('[name="settings-'.concat(i,'"]:checked')).value;localStorage.setItem(c.localStorage,u),n[i]=u}f(),window.dispatchEvent(new Event("resize")),new bootstrap.Offcanvas(e).hide()}(s)}),function(e){for(var o=0,a=Object.entries(r);o<a.length;o++){var l=t(a[o],2),i=l[0];l[1];var c=e.querySelector('[name="settings-'.concat(i,'"][value="').concat(n[i],'"]'));c&&(c.checked=!0)}}(s))});

$(document).ready(function() {
	
	$('.multisel').selectpicker();
	$('.multiselect').multiselect({
		enableFiltering: true,
		includeSelectAllOption: true,
		enableCaseInsensitiveFiltering: true,
		maxHeight: 350,
	});
	
	var table = $('#dataTbl,#dataTblPop').DataTable({
		"iCookieDuration": 60*10, // in second // Aku set 10 menit saja
		"scrollX": true,
		"scrollY": false,
		// "colReorder": true,
		"fnDrawCallback": function() {
            
			var api = this.api(); 
			if(api.context[0].json.data.length <=0 && api.context[0].json.recordsTotal > 0 && api.context[0].json.input.search.value==null){
				console.log("Clear state");
				api.state.clear();			
				// reload page
				location.href=window.location.href;				
			}
			
			if($(".data_dt_1").length != 0){ $(".data_dt_1").html(api.context[0].json.data_dt_1);	}
			if($(".data_dt_2").length != 0){ $(".data_dt_2").html(api.context[0].json.data_dt_2);	}
			if($(".data_dt_3").length != 0){ $(".data_dt_3").html(api.context[0].json.data_dt_3);	}
			if($(".data_dt_4").length != 0){ $(".data_dt_4").html(api.context[0].json.data_dt_4);	}
			if($(".data_dt_5").length != 0){ $(".data_dt_5").html(api.context[0].json.data_dt_5);	}
			if($(".data_dt_6").length != 0){ $(".data_dt_6").html(api.context[0].json.data_dt_6);	}
			if($(".data_dt_7").length != 0){ $(".data_dt_7").html(api.context[0].json.data_dt_7);	}
			if($(".data_dt_8").length != 0){ $(".data_dt_8").html(api.context[0].json.data_dt_8);	}
			if($(".data_dt_9").length != 0){ $(".data_dt_9").html(api.context[0].json.data_dt_9);	}
			if($(".data_dt_10").length != 0){ $(".data_dt_10").html(api.context[0].json.data_dt_10);	}
			if($(".data_dt_11").length != 0){ $(".data_dt_11").html(api.context[0].json.data_dt_11);	}
			if($(".data_dt_12").length != 0){ $(".data_dt_12").html(api.context[0].json.data_dt_12);	}
			
        },
		//"columnDefs" : [ { width: '1px', targets: 0 } ]
	});  
	
	/* submit the form  */
	$(document).on('submit', '#ajxForm, #ajxForm2',function(e) { 
		e.preventDefault();
		$(this).ajaxSubmit({
			"error" : showError,
			"success" : showResponse
		}); 
		return false; 
	});

	$(document).on('submit', '#ajxFormConfirm',function(e) { 
		$(this).ajaxSubmit({
			"beforeSubmit" : showConfirm,
			"error" : showError,
			"success" : showResponse
		}); 
		return false; 
	});

	/* Select ALl Checkbox */
	$('#select-all').on('click', function(){
		if(this.checked) { $(':checkbox').each(function() {this.checked = true; });  } else { $(':checkbox').each(function() {this.checked = false; }); }
	});

	/* BS Modal Handler */
	$(document).on('click', '[data-toggle="ajaxModal"]', function(e) {
		e.preventDefault();
		var $this = $(this), $remote = $this.data('remote') || $this.attr('href');
		$('#ajaxModal .modal-title').html($this.data('title'));
		$('#ajaxModal .modal-dialog').addClass($this.data('class'));
		$('#ajaxModal .modal_content').load($remote);
		$('#ajaxModal').modal({backdrop: 'static', keyboard: false});
		$('#ajaxModal').modal('show'); 
		if($this.data('reloadParent')){
			$('#ajaxModal').addClass('reload-parent');
		}
		
		if($("#ajxForm_message").length != 0){
		var $errElm = $("#ajxForm_message");	
		$errElm.html('');
		}
		if($("#modal-message").length != 0){
		var $errElm = $("#modal-message");	
		$errElm.html('');
		}
	
		return false; 
	});	
	
	$('#ajaxModal').on('hidden.bs.modal', function (e) {
		e.preventDefault();
		$('#ajaxModal .modal_content').html('<center><img id="img-loader" src="'+location.protocol+'//'+location.hostname+'/assets/svg/loading.svg" height="40" alt="Loading.." /></center>');
		if($(this).hasClass('reload-parent')){
			location.reload();
		}
	})
	
	/* Simple Ajax */
	$(document).on('click', '.liteAjax', function(e) {
		var url = $(this).data('url'),
			target = $(this).data('target');
		// console.log('[LiteAjax]');
		// console.log('[URL]:'+url);
		if(url && url.length != 0){
			if(target && target.length != 0){
				// console.log('[target]:'+target);
				el_target = $(target);
				el_target.html('loading..');	
				$.get(url,function(result) {
					// console.log(result);
					el_target.html(result);
				}).fail(function() {
					alert( "Request failed!! Please contact your Administrator.." );				
				}).done(function() {
					// console.log(result);					
				}); 
			}
		}
	});
});  	
function _reload_datatables(){
	$('#dataTbl').DataTable({
		"iCookieDuration": 60*10, // in second // Aku set 10 menit saja
		"bDestroy":true,
		"fnDrawCallback": function() {            
			var api = this.api(); 			
			if($(".data_dt_1").length != 0){ $(".data_dt_1").html(api.context[0].json.data_dt_1);	}
			if($(".data_dt_2").length != 0){ $(".data_dt_2").html(api.context[0].json.data_dt_2);	}
			if($(".data_dt_3").length != 0){ $(".data_dt_3").html(api.context[0].json.data_dt_3);	}
			if($(".data_dt_4").length != 0){ $(".data_dt_4").html(api.context[0].json.data_dt_4);	}
			if($(".data_dt_5").length != 0){ $(".data_dt_5").html(api.context[0].json.data_dt_5);	}
			if($(".data_dt_6").length != 0){ $(".data_dt_6").html(api.context[0].json.data_dt_6);	}
			if($(".data_dt_7").length != 0){ $(".data_dt_7").html(api.context[0].json.data_dt_7);	}
			if($(".data_dt_8").length != 0){ $(".data_dt_8").html(api.context[0].json.data_dt_8);	}
			if($(".data_dt_9").length != 0){ $(".data_dt_9").html(api.context[0].json.data_dt_9);	}
			if($(".data_dt_10").length != 0){ $(".data_dt_10").html(api.context[0].json.data_dt_10);	}
			if($(".data_dt_11").length != 0){ $(".data_dt_11").html(api.context[0].json.data_dt_11);	}
			if($(".data_dt_12").length != 0){ $(".data_dt_12").html(api.context[0].json.data_dt_12);	}			
        },
	});
}
function showError(){alert('Error Occurred : Ajax Error or Form action did not exist!'); return false;}
function showConfirm(){if (!confirm("Are you sure?")){return false;}}
function showResponse(data) { 
    var $formElm = $("#ajxForm");
	var $elModal = $("#ajaxModal");
	var $errElm = $("#ajxForm_message");	
	$errElm.show().html('');
    if(!$.isEmptyObject(data.error)){
		if($elModal.length && $('body').hasClass('modal-open')) {
			/* bila ada modal */
			$errElm = $("#modal-message");
			$errElm.show().html('');
		}		
		/* Gagal */
		var msg = data.error; 
		var x = "";; 
		$.each( msg, function( key, value ) {
			x = x + '<li>' + value + '</li>';
		});
		if ($errElm.length){$errElm.append('<div class="alert alert-danger alert-important"><ul>'+x+'</ul></div>');}
	} else {
		var msg = data.message; 
		$.each( msg, function( key, value ) {	
			if ($errElm.length){$errElm.append('<div class="alert alert-success alert-important">'+value+'</div>');}
		});
		
		/* bool - form reset */
		if($formElm.length && $formElm.attr('data-ajxForm-reset')!="false"){$formElm[0].reset();}
		
		/* Bila ada datatables */
		if($("#dataTbl").length != 0){	_reload_datatables();	}		
		
		/* Bila ada datatables fixed*/
		if($("#dataTblFixed").length != 0){	_reload_datatables();}		
		
		/* Bila ada modal - Hide */
		if($elModal.length && $('body').hasClass('modal-open')) {$elModal.modal('toggle');	$('body').removeClass("modal-open");}	
		
	}
	
	// redirect exists
	if(data.redirect_to.length){
		// console.log(data.redirect_to);
		 // window.location=data.redirect_to;
		window.open(data.redirect_to, '_blank');
	}
	
	$errElm.delay(4000).fadeOut('slow');
}
