var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var $frmCreateAdvertisement = $("#frmCreateAdvertisement"),
			$frmUpdateAdvertisement = $("#frmUpdateAdvertisement"),
			$dialogDelete = $("#dialogDeleteImage"),
			$dialogShowStatus = $('#dialogShowStatus'),
			dialog = ($.fn.dialog !== undefined),
			validate = ($.fn.validate !== undefined),
			datagrid = ($.fn.datagrid !== undefined),
			remove_arr = new Array();
		
		$(".field-int").spinner({
			min: 0
		});
		
		function setPrices()
		{
			var index_arr = new Array();
				
			$('#fd_size_list').find(".fd-size-row").each(function (index, row) {
				index_arr.push($(row).attr('data-index'));
			});
			$('#index_arr').val(index_arr.join("|"));
		}
		
		
		if ($dialogShowStatus.length > 0 && dialog) {
			$dialogShowStatus.dialog({
				autoOpen: false,
				modal: true,
				resizable: false,
				draggable: false,
				open: function () {
					$dialogShowStatus
						.find(".bxShowStatusFail, .bxShowStatusEnd, .bxShowStatusDuplicate").hide().end()
						.find(".bxShowStatusStart").show();
				},
				close: function () {
					$(this).dialog("option", "buttons", {});
					//window.location.reload();
				},
				buttons: {}
			});
		}
		if ($frmCreateAdvertisement.length > 0 || $frmUpdateAdvertisement.length > 0) {
			$.validator.addMethod('positiveNumber',
				function (value) { 
		        	return Number(value) > 0;
		    	}, 
		    myLabel.duration_greater_zero);
		}
		if ($frmCreateAdvertisement.length > 0 && validate) {
			tinymce.init({
			    selector: "textarea.mceEditor",
			    theme: "modern",
			    relative_urls : false,
				remove_script_host : false,
				convert_urls : true,
			    width: 500,
			    plugins: [
			         "advlist autolink link image lists charmap print preview hr anchor pagebreak",
			         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
			         "save table contextmenu directionality emoticons template paste textcolor"
				],
				toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons"
			});
			$frmCreateAdvertisement.validate({
				rules:{
					"duration": {
						positiveNumber: true
					}
				},
				messages: {
					"duration":{
						positiveNumber: myLabel.duration_greater_zero
					}
				},
				errorPlacement: function (error, element) {
					if(element.attr('name') == 'duration')
					{
						error.insertAfter(element.parent().parent());
					}else{
						error.insertAfter(element.parent());
					}
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em",
				ignore: "",
				invalidHandler: function (event, validator) {
					var localeId = $(validator.errorList[0].element, this).attr('lang');
					if(localeId != undefined)
					{
						$(".pj-multilang-wrap").each(function( index ) {
							if($(this).attr('data-index') == localeId)
							{
								$(this).css('display','block');
							}else{
								$(this).css('display','none');
							}
						});
						$(".pj-form-langbar-item").each(function( index ) {
							if($(this).attr('data-index') == localeId)
							{
								$(this).addClass('pj-form-langbar-item-active');
							}else{
								$(this).removeClass('pj-form-langbar-item-active');
							}
						});
					}
				},
				submitHandler: function(form){
					var valid = true,
						localeId = null;
					
					setPrices();
					
					$("#frmCreateAdvertisement .fdRequired").each(function() {
						if($(this).val() == '')
						{
							valid = false;
					    	$(this).addClass('pj-error-field');
					    	if(localeId == null)
					    	{
					    		localeId = $(this).attr('lang');
					    	}
					    	
						}else{
							$(this).removeClass('pj-error-field');
						}
					});
					if(localeId != null)
					{
						$(".pj-multilang-wrap").each(function( index ) {
							if($(this).attr('data-index') == localeId)
							{
								$(this).css('display','block');
							}else{
								$(this).css('display','none');
							}
						});
						$(".pj-form-langbar-item").each(function( index ) {
							if($(this).attr('data-index') == localeId)
							{
								$(this).addClass('pj-form-langbar-item-active');
							}else{
								$(this).removeClass('pj-form-langbar-item-active');
							}
						});
					}
					
					
					if(valid == true)
					{
						form.submit();
					}
				}
			});
			
			if(myLabel.locale_array.length > 0)
			{
				var locale_array = myLabel.locale_array;
				for(var i = 0; i < locale_array.length; i++)
				{
					var title = $("#i18n_title_" + locale_array[i]),
						description = $("#i18n_description_" + locale_array[i]);
					title.rules('add', {
						messages: {
					    	required: myLabel.field_required
					    }
					});
					description.rules('add', {
						messages: {
					    	required: myLabel.field_required
					    }
					});
				}
			}
		}
		if ($frmUpdateAdvertisement.length > 0 && validate) {
			tinymce.init({
			    selector: "textarea.mceEditor",
			    theme: "modern",
			    relative_urls : false,
				remove_script_host : false,
				convert_urls : true,
			    width: 500,
			    plugins: [
			         "advlist autolink link image lists charmap print preview hr anchor pagebreak",
			         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
			         "save table contextmenu directionality emoticons template paste textcolor"
				],
				toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons"
			});
			$frmUpdateAdvertisement.validate({
				rules:{
					"duration": {
						positiveNumber: true
					}
				},
				messages: {
					"duration":{
						positiveNumber: myLabel.duration_greater_zero
					}
				},
				errorPlacement: function (error, element) {
					if(element.attr('name') == 'duration')
					{
						error.insertAfter(element.parent().parent());
					}else{
						error.insertAfter(element.parent());
					}
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em",
				ignore: "",
				invalidHandler: function (event, validator) {
					var localeId = $(validator.errorList[0].element, this).attr('lang');
					if(localeId != undefined)
					{
						$(".pj-multilang-wrap").each(function( index ) {
							if($(this).attr('data-index') == localeId)
							{
								$(this).css('display','block');
							}else{
								$(this).css('display','none');
							}
						});
						$(".pj-form-langbar-item").each(function( index ) {
							if($(this).attr('data-index') == localeId)
							{
								$(this).addClass('pj-form-langbar-item-active');
							}else{
								$(this).removeClass('pj-form-langbar-item-active');
							}
						});
					}
				},
				submitHandler: function(form){
					var valid = true,
						localeId = null;
					
					setPrices();
					$("#frmUpdateProduct .fdRequired").each(function() {
						if($(this).val() == '')
						{
							valid = false;
					    	$(this).addClass('pj-error-field');
					    	if(localeId == null)
					    	{
					    		localeId = $(this).attr('lang');
					    	}
					    	
						}else{
							$(this).removeClass('pj-error-field');
						}
						
					});
					if(localeId != null)
					{
						$(".pj-multilang-wrap").each(function( index ) {
							if($(this).attr('data-index') == localeId)
							{
								$(this).css('display','block');
							}else{
								$(this).css('display','none');
							}
						});
						$(".pj-form-langbar-item").each(function( index ) {
							if($(this).attr('data-index') == localeId)
							{
								$(this).addClass('pj-form-langbar-item-active');
							}else{
								$(this).removeClass('pj-form-langbar-item-active');
							}
						});
					}
					
					if(valid == true)
					{
						form.submit();
					}
				}
			});
			if(myLabel.locale_array.length > 0)
			{
				var locale_array = myLabel.locale_array;
				for(var i = 0; i < locale_array.length; i++)
				{
					var title = $("#i18n_title_" + locale_array[i]),
						description = $("#i18n_description_" + locale_array[i]);
					title.rules('add', {
						messages: {
					    	required: myLabel.field_required
					    }
					});
					description.rules('add', {
						messages: {
					    	required: myLabel.field_required
					    }
					});
				}
			}
		}
		
		if ($dialogDelete.length > 0 && dialog) 
		{
			$dialogDelete.dialog({
				modal: true,
				autoOpen: false,
				resizable: false,
				draggable: false,
				width: 400,
				buttons: (function () {
					var buttons = {};
					buttons[tbApp.locale.button.delete] = function () {
						$.ajax({
							type: "GET",
							dataType: "json",
							url: $dialogDelete.data('href'),
							success: function (res) {
								if(res.code == 200){
									$('#image_container').remove();
									$dialogDelete.dialog('close');
								}
							}
						});
					};
					buttons[tbApp.locale.button.cancel] = function () {
						$dialogDelete.dialog("close");
					};
					
					return buttons;
				})()
			});
		}
		
		
		function onBeforeShow (obj) {
			if (parseInt(obj.cnt_confirmed, 10) > 0) {
				return false;
			}
			return true;
		}
		function formatImage (str, obj) {
			var src = str ? str : 'app/web/img/backend/80x116.png';
			return ['<a href="admin.php?controller=pjAdminAdvertisements&action=pjActionUpdate&id=', obj.id ,'"><img src="', src, '" style="width: 80px; display:block;" /></a>'].join("");
		}
		if ($("#grid").length > 0 && datagrid) {
			//console.log(datagrid);
			var $grid = $("#grid").datagrid({
				buttons: [{type: "edit", url: "admin.php?controller=pjAdminAdvertisements&action=pjActionUpdate&id={:id}"},
				          {type: "delete", url: "admin.php?controller=pjAdminAdvertisements&action=pjActionDeleteAdvertisement&id={:id}", beforeShow: onBeforeShow},
				          ],
				columns: [
					{text: myLabel.image, type: "text", sortable: false, editable: false, width: 100, renderer: formatImage},
				    {text: myLabel.title, type: "text", sortable: true, editable: false, width: 300},
					{text: myLabel.status, type: "select", sortable: true, editable: true, width: 90, 
						options: [
				                    {label: myLabel.active, value: "T"}, 
				            		{label: myLabel.inactive, value: "F"}
								], applyClass: "pj-status"}
				],
				dataUrl: "admin.php?controller=pjAdminAdvertisements&action=pjActionGetAdvertisements",
				dataType: "json",
				fields: ['image', 'name', 'status'],
				paginator: {
					actions: [
					   {text: myLabel.delete_selected, url: "admin.php?controller=pjAdminAdvertisements&action=pjActionDeleteAdvertisementBulk", render: true, confirmation: myLabel.delete_confirmation}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "admin.php?controller=pjAdminAdvertisements&action=pjActionSaveAdvertisement&id={:id}",
				select: {
					field: "id",
					name: "record[]"
				}
			});

			
		}
		

		
		$(document).on("click", ".btn-all", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$(this).addClass("pj-button-active").siblings(".pj-button").removeClass("pj-button-active");
			var content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			$.extend(cache, {
				status: "",
				q: ""
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "admin.php?controller=pjAdminAdvertisements&action=pjActionGetAdvertisements", "created", "DESC", content.page, content.rowCount);
			return false;
		}).on("click", ".btn-filter", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this),
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache"),
				obj = {};
			$this.addClass("pj-button-active").siblings(".pj-button").removeClass("pj-button-active");
			obj.status = "";
			obj[$this.data("column")] = $this.data("value");
			$.extend(cache, obj);
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "admin.php?controller=pjAdminAdvertisements&action=pjActionGetAdvertisements", "created", "DESC", content.page, content.rowCount);
			return false;
		}).on("click", ".pj-status-1", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			return false;
		}).on("click", ".pj-status-0", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$.post("admin.php?controller=pjAdminAdvertisements&action=pjActionSetActive", {
				id: $(this).closest("tr").data("object")['id']
			}).done(function (data) {
				$grid.datagrid("load", "admin.php?controller=pjAdminAdvertisements&action=pjActionGetAdvertisements");
			});
			return false;
		}).on("submit", ".frm-filter", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this),
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			$.extend(cache, {
				q: $this.find("input[name='q']").val()
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "admin.php?controller=pjAdminAdvertisements&action=pjActionGetAdvertisements", "created", "DESC", content.page, content.rowCount);
			return false;
		}).on("click", '.pj-add-size', function(e){
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var clone_text = $('#fd_size_clone').html(),
				index = Math.ceil(Math.random() * 999999),
				number_of_sizes = $('#fd_size_list').find(".fd-size-row").length;
			clone_text = clone_text.replace(/\{INDEX\}/g, 'fd_' + index);
			$('#fd_size_list').append(clone_text);
		}).on("click", '.pj-remove-size', function(e){
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $size = $(this).parent().parent(),
				id = $size.attr('data-index');
			if(id.indexOf("fd") == -1)
			{
				remove_arr.push(id);
			}
			$('#remove_arr').val(remove_arr.join("|"));
			$size.remove();
			
		}).on("click", ".pj-delete-image", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$dialogDelete.data('href', $(this).data('href')).dialog("open");
		}).on("click", ".pj-form-field-icon-date", function (e) {
			var $dp = $(this).parent().siblings("input[type='text']");
			if ($dp.hasClass("hasDatepicker")) {
				$dp.datepicker("show");
			} else {
				if(!$dp.is('[disabled=disabled]'))
				{
					$dp.trigger("focusin").datepicker("show");
				}
			}
		}).on("focusin", ".datetimepick", function (e) {
			var $this = $(this),
				custom = {},
				o = {
					firstDay: $this.attr("rel"),
					dateFormat: $this.attr("rev"),
					timeFormat: $this.attr("lang"),
					stepMinute: 5,
					onClose: function(){
						getSeats($this.attr('data-index'));
					}
			};
			$(this).datetimepicker($.extend(o, custom));
		}).on("click", ".btnAddShow", function () {
			var $c = $("#tblShowClone tbody").clone(),
			r = $c.html().replace(/\{INDEX\}/g, 'new_' + Math.ceil(Math.random() * 99999));
			$(this).closest("form").find("table").find("tbody").append(r);
			
			
		}).on("click", ".lnkRemoveShow", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $tr = $(this).closest("tr");
			$tr.css("backgroundColor", "#FFB4B4").fadeOut("slow", function () {
				$tr.remove();
			});			
			return false;
		}).on("change", ".tbVenueSelector", function (e) {
			getSeats($(this).attr('data-index'));
			return false;
		}).on("click", ".lnkDeleteShow", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			return false;
		}).on("click", ".pj-table-icon-menu", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var diff, lf,
				$this = $(this),
				$list = $this.siblings(".pj-menu-list-wrap");
			diff = Math.ceil( ($list.outerWidth() - $this.outerWidth()) / 2 );
			if (diff > 0) {
				lf = $this.offset().left - diff;
				if (lf < 0) {
					lf = 0;
				}
			} else {
				lf  = $this.offset().left + diff;
			}
			$list.css({
				"top": $this.offset().top + $this.outerHeight() + 2,
				"left": lf
			});
		
			$list.toggle();
			$(".pj-menu-list-wrap").not($list).hide();
			return false;
		}).on("change", "#date", function (e) {
			$('#frmEventBooking').submit();
		}).on("change", "#time", function (e) {
			$('#frmEventBooking').submit();
		});
		
		$(document).on("click", "*", function (e) {
			if(!$(e.target).hasClass('pj-table-icon-menu'))
			{
				$('.pj-menu-list-wrap').hide();
			}
		}).on("change", "#export_period", function (e) {
			var period = $(this).val();
			if(period == 'last')
			{
				$('#last_label').show();
				$('#next_label').hide();
			}else{
				$('#last_label').hide();
				$('#next_label').show();
			}
		}).on("click", "#file", function (e) {
			$('#abSubmitButton').val(myLabel.btn_export);
			$('.abFeedContainer').hide();
			$('.abPassowrdContainer').hide();
		}).on("click", "#feed", function (e) {
			$('.abPassowrdContainer').show();
			$('#abSubmitButton').val(myLabel.btn_get_url);
		}).on("focus", "#movies_feed", function (e) {
			$(this).select();
		});
		
		
	});
})(jQuery_1_8_2);