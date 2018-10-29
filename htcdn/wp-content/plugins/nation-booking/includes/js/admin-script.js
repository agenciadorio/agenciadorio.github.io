(function($) {
	$(document).ready(function() {
		
		if ( !$.cookie("calendar-month") ) {
			var d = new Date();
			n = d.getMonth()+1;
			$.cookie("calendar-month",n);
		}
		
		if (!$.cookie("calendar-year") ) {
			var d = new Date();
			y = d.getFullYear();
			$.cookie("calendar-year",y);
		}
		
		$(".cal-edit").click(function(e) {
			$(this).parents(".row-wrap").find(".content-row").css("display","none");
			$(this).parents(".row-wrap").find(".edit-row").css("display","table-cell");
		});
		
		
		$(".cal_edit_cancel").click(function(e) {
			$(this).parents(".row-wrap").find(".content-row").css("display","table-cell");
			$(this).parents(".row-wrap").find(".edit-row").css("display","none");
		});
		
		
		$(".cal-delete-button").click(function(e) {
			var answer = confirm( bookingOption.deleteCalText );
			if ( !answer ) {
				return false;
			}
		});
		
		
		var ajaxurl = bookingOption.ajaxurl; 
		$("#select-calendar-reservation").change(function(){
			$.post(ajaxurl, {action: 'nation_show_reservations', cal_id: $(this).val()}, function(data){
				$("#show-reservation-table").html(data);
			});
			$.cookie("calendar",$(this).val());
		});
		
		
		if ( $.cookie("calendar") != undefined && $("#select-calendar-reservation").length ) {
			$("#select-calendar-reservation").val($.cookie("calendar"));
			$.post(ajaxurl, {action: 'nation_show_reservations', cal_id: $.cookie("calendar")}, function(data){
				$("#show-reservation-table").html(data);
			});
		}
		
		
		$("#select-calendar-price").change(function(){
			$.post(ajaxurl, {action: 'nation_show_calendar', cal_id: $(this).val()}, function(data){
				$("#show-price-calendar").html(data);
			});
			$.cookie("calendar",$(this).val());
			
			if ( bookingOption.dateformat == "european" ) {
				defaultDate = "01-" + $.cookie("calendar-month") + "-" + $.cookie("calendar-year");
			} else if ( bookingOption.dateformat == "american" ) {
				defaultDate = $.cookie("calendar-month") + "/01/" + $.cookie("calendar-year");
			}
			
			$( ".availty-price .check-in-date, .availty-price .check-out-date" ).datepicker( "option", "defaultDate", defaultDate );
			
			location.reload();
			
		});
		
		
		
		if ( $.cookie("calendar") != undefined && $("#select-calendar-price").length ) {
			$("#select-calendar-price").val($.cookie("calendar"));
			
			if ( $.cookie("calendar-month") != undefined && $.cookie("calendar-year") != undefined ) {
				$("#calendar-month").val($.cookie("calendar-month"));
				$("#calendar-year").val($.cookie("calendar-year"));
			
				$.post(ajaxurl, {action: 'nation_show_calendar', cal_id: $.cookie("calendar"), calendar_month: $.cookie("calendar-month"), calendar_year: $.cookie("calendar-year")}, 
				function(data){
					$("#show-price-calendar").html(data);
				});
			} else if ( $.cookie("calendar-month") != undefined ) {
				$.post(ajaxurl, {action: 'nation_show_calendar', cal_id: $.cookie("calendar"), calendar_month: $.cookie("calendar-month")}, 
				function(data){
					$("#show-price-calendar").html(data);
				});
			} else if ( $.cookie("calendar-year") != undefined ) {
				$.post(ajaxurl, {action: 'nation_show_calendar', cal_id: $.cookie("calendar"), calendar_year: $.cookie("calendar-year")}, 
				function(data){
					$("#show-price-calendar").html(data);
				});
			} else { 
				$.post(ajaxurl, {action: 'nation_show_calendar', cal_id: $.cookie("calendar")}, function(data){
					$("#show-price-calendar").html(data);
				});
						
			}
		}
		
		
		$(document).on("change", "#calendar-month", function(){
			$.post(ajaxurl, { action: 'nation_show_calendar', cal_id: $("#select-calendar-price").val(), calendar_month: $("#calendar-month").val(), calendar_year: $("#calendar-year").val() }, function(data){
				$("#show-price-calendar").html(data);
			});
			$.cookie( "calendar-month", $(this).val() );
			$.cookie( "calendar-year", $("#calendar-year").val() );
					
			if ( bookingOption.dateformat == "european" ) {
				defaultDate = "01-" + $.cookie("calendar-month") + "-" + $.cookie("calendar-year");
			} else if ( bookingOption.dateformat == "american" ) {
				defaultDate = $.cookie("calendar-month") + "/01/" + $.cookie("calendar-year");
			}
			
			$( ".availty-price .check-in-date, .availty-price .check-out-date" ).datepicker( "option", "defaultDate", defaultDate );
			
			location.reload();
			
		});
		
		
		$(document).on("change", "#calendar-year", function(){
			$.post(ajaxurl, { action: 'nation_show_calendar', cal_id: $("#select-calendar-price").val(), calendar_month: $("#calendar-month").val(), calendar_year: $("#calendar-year").val() }, function(data){
				$("#show-price-calendar").html(data);
			});
			$.cookie( "calendar-month", $("#calendar-month").val() );
			$.cookie( "calendar-year", $(this).val() );
					
			if ( bookingOption.dateformat == "european" ) {
				defaultDate = "01-" + $.cookie("calendar-month") + "-" + $.cookie("calendar-year");
			} else if ( bookingOption.dateformat == "american" ) {
				defaultDate = $.cookie("calendar-month") + "/01/" + $.cookie("calendar-year");
			}
			
			$( ".availty-price .check-in-date, .availty-price .check-out-date" ).datepicker( "option", "defaultDate", defaultDate );
			
			location.reload();
		});
		
		
		$(document).on("submit", "#reservation-add", function(){
			$("#reservation-add").append("<input type='hidden' name='cal_id' value='"+$("#select-calendar-reservation").val()+"'>");
		});
		
		var dateFormat = "";
		var defaultDate = "";
		
		if ( bookingOption.dateformat == "european" ) {
			dateFormat = "dd-mm-yy";
			defaultDate = "01-" + $.cookie("calendar-month") + "-" + $.cookie("calendar-year");
		} else if ( bookingOption.dateformat == "american" ) {
			dateFormat = "mm/dd/yy";
			defaultDate = $.cookie("calendar-month") + "/01/" + $.cookie("calendar-year");
		}
		
		var monthLong,monthShort,dayNames,dayNamesShort,dayNamesMin = [];
	
		monthLong = bookingOption.monthLongNames.split(",");
		monthShort = bookingOption.monthShortNames.split(",");
		dayNames = bookingOption.dayLongNames.split(",");
		dayNamesShort = bookingOption.dayShortNames.split(",");
		dayNamesMin = bookingOption.dayMicroNames.split(",");
		
		
		// parse a date in yyyy-mm-dd format
		function parseDate(input,format) {
			if ( format == "european" ) {
				var parts = input.split('-');
			} else if ( format == "american" ) {
				var parts = input.split('/');
			}
			// new Date(year, month [, day [, hours[, minutes[, seconds[, ms]]]]])
			if ( format == "european" ) {
				return new Date(parts[2], parts[1]-1, parts[0]); // Note: months are 0-based
			} else if ( format == "american" ) {
				return new Date(parts[2], parts[0]-1, parts[1]); // Note: months are 0-based
			}
		}
		
				
		$('body').on("focus","#create-reservation-table .check-in-date",function(){
			$(this).datepicker({ 
			
				dateFormat: dateFormat,
				defaultDate: defaultDate,
				
				dayNames:dayNames,
				dayNamesShort:dayNamesShort,
				dayNamesMin:dayNamesMin,
				monthNames:monthLong,
				monthNamesShort:monthShort,
				
				onSelect: function(dateText,inst) {
					var check = $("#create-reservation-table .check-out-date").val();
					if (check) {
						var checkOut = parseDate(check,bookingOption.dateformat);
						var checkIn = parseDate(dateText,bookingOption.dateformat);

						if (checkIn >= checkOut) {
							alert(bookingOption.resCheckInText);
							$(this).val("");
						}
					}
				}	
			
			});
		});			
		
		$('body').on("focus","#create-reservation-table .check-out-date",function(){
			$(this).datepicker({ 
			
				dateFormat: dateFormat,
				defaultDate: defaultDate,
				
				dayNames:dayNames,
				dayNamesShort:dayNamesShort,
				dayNamesMin:dayNamesMin,
				monthNames:monthLong,
				monthNamesShort:monthShort,
				
				onSelect: function(dateText,inst) {
					var check = $("#create-reservation-table .check-in-date").val();
					if (check) {
						var checkIn = parseDate(check,bookingOption.dateformat);
						var checkOut = parseDate(dateText,bookingOption.dateformat);
						if (checkOut <= checkIn) {
							alert(bookingOption.resCheckOutText);
							$(this).val("");
						}
					}
				}
			
			});
		});
		
		
		$('body').on("focus",".set-data .check-in-date, .set-data .check-out-date", function(){
			$(this).datepicker({ 
			
				dateFormat: dateFormat,
				defaultDate: defaultDate,
				
				dayNames:dayNames,
				dayNamesShort:dayNamesShort,
				dayNamesMin:dayNamesMin,
				monthNames:monthLong,
				monthNamesShort:monthShort
			
			});
		});
		
		
		//Check calendars form fields
		$("#add-calendar-form").submit(function(){
			var calName = $("#add-calendar-form #cal-name");
		
			if ( calName.val() == "" ) {
				calName.addClass('highlight');
				return false;
			} else {
				calName.removeClass('highlight');
			}

		});
		
		
		//Check reservation form fields
		$('body').on("submit", "#reservation-add", function(){
			var checkIn = $("#reservation-add .check-in-date");
			var checkOut = $("#reservation-add .check-out-date");
			var roomNumber = $("#reservation-add #room-number");
			var email = $("#reservation-add #email");
			var adults = $("#reservation-add #adults");
			var children = $("#reservation-add #children");
			var cardholder = $("#reservation-add #cardholder");
			var cardnumber = $("#reservation-add #cardnumber");
			
			var name = $("#reservation-add #name");
			var surname = $("#reservation-add #surname");
			
			if ( checkIn.val() == "" ) {
				checkIn.addClass('highlight');
				return false;
			} else {
				checkIn.removeClass('highlight');
			} if ( checkOut.val() == "" ) {
				checkOut.addClass('highlight');
				return false;
			} else {
				checkOut.removeClass('highlight');
			} if ( roomNumber.val() == "" ) {
				roomNumber.addClass('highlight');
				return false;
			} else {
				roomNumber.removeClass('highlight');
			} if ( email.val() == "" ) {
				email.addClass('highlight');
				return false;
			} else {
				email.removeClass('highlight');
			} if ( adults.val() == "" ) {
				adults.addClass('highlight');
				return false;
			} else {
				adults.removeClass('highlight');
			} if ( children.val() == "" ) {
				children.addClass('highlight');
				return false;
			} else {
				children.removeClass('highlight');
			} if ( name.val() == "" ) {
				name.addClass('highlight');
				return false;
			} else {
				name.removeClass('highlight');
			} if ( surname.val() == "" ) {
				surname.addClass('highlight');
				return false;
			} else {
				surname.removeClass('highlight');
			} if ( cardholder.val() == "" ) {
				cardholder.addClass('highlight');
				return false;
			} else {
				cardholder.removeClass('highlight');
			} if ( cardnumber.val() == "" ) {
				cardnumber.addClass('highlight');
				return false;
			} else {
				cardnumber.removeClass('highlight');
			}
			
		});
		
		$('body').on("submit", "#set-edit-price", function(){
			var checkIn = $("#set-edit-price .check-in-date");
			var checkOut = $("#set-edit-price .check-out-date");
			var roomNumber = $("#set-edit-price #room-number");
			var priceRoom = $("#set-edit-price #price-per-room");
		
			if ( checkIn.val() == "" ) {
				checkIn.addClass('highlight');
				return false;
			} else {
				checkIn.removeClass('highlight');
			}
			if ( checkOut.val() == "" ) {
				checkOut.addClass('highlight');
				return false;
			} else {
				checkOut.removeClass('highlight');
			}
			if ( roomNumber.val() == "" ) {
				roomNumber.addClass('highlight');
				return false;
			} else {
				roomNumber.removeClass('highlight');
			}
			if ( priceRoom.val() == "" ) {
				priceRoom.addClass('highlight');
				return false;
			} else {
				priceRoom.removeClass('highlight');
			}
			
		});
		
		
		//Editing reservations scripts
		$('body').on("click", ".edit-reservation-button", function(){
			var roomNumber = $(this).parents(".row-wrap").find(".edit-row").find("#reservation-room-number-edit").val();
			
			for (var i=1;i<=roomNumber;i++) {
				$(this).parents(".row-wrap").find(".edit-row").find(".reservation-edit-room-"+i).css('display','block');
			}
			
			$(this).parents(".row-wrap").find(".content-row").css("display","none");
			$(this).parents(".row-wrap").find(".edit-row").css("display","table-cell");
			
			$(this).parents(".row-wrap").find(".edit-row .add-datepicker-indication").datepicker({ dateFormat: dateFormat });
		});
		
		$('body').on("click", ".cancel-edit-reservation", function(){
			$(this).parents(".row-wrap").find(".content-row").css("display","table-cell");
			$(this).parents(".row-wrap").find(".edit-row").css("display","none");
		});
			
		$('body').on("change", "#reservation-room-number-edit", function(){
			var roomNumber = $(this).val();
			
			for (var i=1;i<=10;i++) {
				$(this).parents(".edit-row").find(".reservation-edit-room-"+i).css('display','none');
			}
			
			for (var i=1;i<=roomNumber;i++) {
				$(this).parents(".edit-row").find(".reservation-edit-room-"+i).css('display','block');
			}
		});
		
		$('body').on("click", ".approve-button", function(e){
			var answer = confirm( bookingOption.approveReservationText );
			if ( !answer ) {
				return false;
			}
		});
		
		$('body').on("click", ".reject-button", function(e){
			var answer = confirm( bookingOption.rejectReservationText );
			if ( !answer ) {
				return false;
			}
		});
		
		$('body').on("click", ".cancel-button", function(e){
			var answer = confirm( bookingOption.cancelReservationText );
			if ( !answer ) {
				return false;
			}
		});
		
		$('body').on("click", ".delete-button", function(e){
			var answer = confirm( bookingOption.deleteReservationText );
			if ( !answer ) {
				return false;
			}
		});
		
		
	});
})(jQuery);