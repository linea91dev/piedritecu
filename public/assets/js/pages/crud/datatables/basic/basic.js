"use strict";
var KTDatatablesSearchOptionsAdvancedSearch = (function () {
	var initTable1 = function () {
		// begin first table
		var table = $("#kt_datatable").DataTable({
			responsive: true,
			// Pagination settings
			dom: `<'row'<'col-sm-12'tr>>
			<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
			// read more: https://datatables.net/examples/basic_init/dom.html

			lengthMenu: [5, 10, 25, 50],

			pageLength: 10,

			language: {
				lengthMenu: "Mostrar _MENU_",
				infoFiltered: "(filtrado de _MAX_ entradas totales)",
				emptyTable: "No hay datos disponibles en la tabla",
				zeroRecords: "No se encontraron coincidencias",
				loadingRecords: "Cargando...",
				processing: "Procesando...",
			},

			searchDelay: 500,
			processing: true,
		});

		var filter = function () {
			var val = $.fn.dataTable.util.escapeRegex($(this).val());
			table
				.column($(this).data("col-index"))
				.search(val ? val : "", false, false)
				.draw();
		};

		var asdasd = function (value, index) {
			var val = $.fn.dataTable.util.escapeRegex(value);
			table.column(index).search(val ? val : "", false, true);
		};

		$("#kt_search").on("click", function (e) {
			e.preventDefault();
			var params = {};
			$(".datatable-input").each(function () {
				var i = $(this).data("col-index");
				if (params[i]) {
					params[i] += "|" + $(this).val();
				} else {
					params[i] = $(this).val();
				}
			});
			$.each(params, function (i, val) {
				table.column(i).search(val ? val : "", false, false);
			});
			table.table().draw();
		});

		$("#kt_reset").on("click", function (e) {
			e.preventDefault();
			$(".datatable-input").each(function () {
				$(this).val("");
				table.column($(this).data("col-index")).search("", false, false);
			});
			table.table().draw();
		});

		$.fn.datepicker.dates['es'] = {
			days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Sábado", "Domingo"],
			daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
			daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
			months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Dicembre"],
			monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
			today: "Hoy",
			clear: "Limpiar",
			format: "mm/dd/yyyy",
			titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
			weekStart: 0
		};

		$("#kt_datepicker").datepicker({
			language: "es",
			todayHighlight: true,
			templates: {
				leftArrow: '<i class="la la-angle-left"></i>',
				rightArrow: '<i class="la la-angle-right"></i>',
			},
		});

		$("#kt_datepicker_1").datepicker({
			language: "es",
			todayHighlight: true,
			templates: {
				leftArrow: '<i class="la la-angle-left"></i>',
				rightArrow: '<i class="la la-angle-right"></i>',
			},
		});

		$("#kt_datepicker_2").datepicker({
			language: "es",
			todayHighlight: true,
			templates: {
				leftArrow: '<i class="la la-angle-left"></i>',
				rightArrow: '<i class="la la-angle-right"></i>',
			},
		});

		$("#kt_datepicker_3").datepicker({
			language: "es",
			todayHighlight: true,
			templates: {
				leftArrow: '<i class="la la-angle-left"></i>',
				rightArrow: '<i class="la la-angle-right"></i>',
			},
		});
		
		$("#kt_datepicker_s").datepicker({
			language: "es",
			todayHighlight: true,
			templates: {
				leftArrow: '<i class="la la-angle-left"></i>',
				rightArrow: '<i class="la la-angle-right"></i>',
			},
		});
	};

	return {
		//main function to initiate the module
		init: function () {
			initTable1();
		},
	};
})();

jQuery(document).ready(function () {
	KTDatatablesSearchOptionsAdvancedSearch.init();
});
