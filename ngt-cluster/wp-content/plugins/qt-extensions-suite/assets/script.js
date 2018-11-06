(function($){
	"use strict";



	$(document).ready(function(){

		$(".cbp-vm-switcher").on("click","a.cbp-vm-grid",function(e){
			e.preventDefault();
			//$(this).closest(".cbp-vm-switcher").removeClass("cbp-vm-view-list").addClass("cbp-vm-view-grid");
			/*
			*
			*	Note: this switches configuration for all the grids. Same for the button below
			*
			*
			*/
			$(".cbp-vm-switcher").removeClass("cbp-vm-view-list").addClass("cbp-vm-view-grid");


			$("a.cbp-vm-grid").addClass("active");
			$("a.cbp-vm-list").removeClass("active");
			$(this).addClass("active");
		});
		$(".cbp-vm-switcher").on("click","a.cbp-vm-list",function(e){
			e.preventDefault();
			//$(this).closest(".cbp-vm-switcher").removeClass("cbp-vm-view-grid").addClass("cbp-vm-view-list");

			$(".cbp-vm-switcher").removeClass("cbp-vm-view-grid").addClass("cbp-vm-view-list");

			$("a.cbp-vm-list").addClass("active");
			$("a.cbp-vm-grid").removeClass("active");
		});

		/*
		*	Function for mobile tabs to select
		*
		*/

		$( "#qwShowDropdown" ).change(function() {
			$("a#"+$(this).attr("value")).click();
//			  alert($(this).attr("value") );
		});



		/*
		*
		*	Dynamic width for the labels of the grid

		*
		*/
		$("[data-dynamicwidth]").each(function(i,c){
			var target = $(this).attr("data-target");
			//$("body").append('<style type="text/css">'+target+'{width:'+$(this).attr("data-dynamicwidth")+'%;}</style>');

		});

	});


})(jQuery);