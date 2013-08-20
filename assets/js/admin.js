(function ($) {
	"use strict";
	$(function () {
		

	var wwkwapp = {};

	wwkwapp = {

		el:{

			redirects: $('.redirects'),
			redirect_table: $('.redirect_table')
		},

		events: function(){

			// User click on delete redirect
			this.el.redirects.on('click','.delete_redirect',function(e){

				wwkwapp.delete_redirect($(this));

				e.preventDefault();

			});

			// User click on delete redirect
			this.el.redirects.on('click','.add_redirect',function(e){

				wwkwapp.add_redirect($(this));

				e.preventDefault();

			});

			// User click on delete redirect
			$('.cancel_changes').on('click',function(e){

				wwkwapp.refresh_page();

				e.preventDefault();

			});


			

		},

		delete_redirect: function(el){

			el.parents('tr').remove();

		},

		add_redirect: function(){

			var template = $(_.template($('#redirect').html())());

			this.el.redirect_table.append(template);

		},

		refresh_page: function(){

			location.reload();

		}

	};

	// Init user events
	wwkwapp.events();


	});
}(jQuery));