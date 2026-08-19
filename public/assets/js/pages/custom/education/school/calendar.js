"use strict";

var KTAppsEducationSchoolCalendar = (function () {
	return {
		//main function to initiate the module
		init: function () {
			var todayDate = moment().startOf("day");
			var YM = todayDate.format("YYYY-MM");
			var YESTERDAY = todayDate.clone().subtract(1, "day").format("YYYY-MM-DD");
			var TODAY = todayDate.format("YYYY-MM-DD");
			var TOMORROW = todayDate.clone().add(1, "day").format("YYYY-MM-DD");

			var calendarEl = document.getElementById("kt_calendar");
			var calendar = new FullCalendar.Calendar(calendarEl, {
				plugins: ["bootstrap", "interaction", "dayGrid", "timeGrid", "list"],
				themeSystem: "bootstrap",

				isRTL: KTUtil.isRTL(),

				header: {
					left: "prev,next today",
					center: "title",
					right: "dayGridMonth,timeGridWeek,timeGridDay",
				},
				timeZone: "America/Guatemala",
				height: 800,
				contentHeight: 780,
				aspectRatio: 3,
				timeFormat: "h:mma",
				displayEventTime: true,
				displayEventEnd: true,
				nowIndicator: true,
				now: TODAY + "T00:00:00.0+0100",

				views: {
					dayGridMonth: { buttonText: "Mes" },
					timeGridWeek: { buttonText: "Semana" },
					timeGridDay: { buttonText: "Día" },
				},

				defaultView: "dayGridMonth",
				defaultDate: TODAY,
				selectable: true,
				allDay: false,
				selectHelper: true,
				editable: true,
				eventLimit: true,
				navLinks: true,
				select: function (start, end) {
					showModal(base_url + "modal/popup/calendario_add/" + start.startStr + "/" + start.endStr);
				},
				events: data,

				eventRender: function (info) {
					var element = $(info.el);

					element.bind("dblclick", function () {
						showAjaxModal(base_url + "modal/popup/calendario_edit/" + info.event.id);
					});

					if (info.event.extendedProps && info.event.extendedProps.description) {
						if (element.hasClass("fc-day-grid-event")) {
							element.data("content", info.event.extendedProps.description);
							element.data("placement", "top");
							KTApp.initPopover(element);
						} else if (element.hasClass("fc-time-grid-event")) {
							element
								.find(".fc-title")
								.append(
									'<div class="fc-description">' + info.event.extendedProps.description + "</div>"
								);
						} else if (element.find(".fc-list-item-title").lenght !== 0) {
							element
								.find(".fc-list-item-title")
								.append(
									'<div class="fc-description">' + info.event.extendedProps.description + "</div>"
								);
						}
					}
				},
				eventDrop: function (info, delta, revertFunc) {
					edit(info);
				},
				eventResize: function (info, dayDelta, minuteDelta, revertFunc) {
					edit(info);
				},
			});
			function edit(info) {
				function convert(str) {
					var date = new Date(str),
						mnth = ("0" + (date.getMonth() + 1)).slice(-2),
						day = ("0" + date.getDate()).slice(-2);
					return [date.getFullYear(), mnth, day].join("-");
				}

				let start = convert(info.event.start);
				let end = convert(info.event.end);

				let id = info.event.id;

				$.ajax({
					url: base_url + "admin/calendario/update2/" + id,
					type: "POST",
					data: { start: start, end: end },
					success: function (rep) {
						const Toast = Swal.mixin({
							toast: true,
							position: "top-end",
							showConfirmButton: false,
							timer: 4000,
							timerProgressBar: true,
							didOpen: (toast) => {
								toast.addEventListener("mouseenter", Swal.stopTimer);
								toast.addEventListener("mouseleave", Swal.resumeTimer);
							},
						});
						Toast.fire({
							icon: "success",
							type: "success",
							title: "Evento actualizado correctamente.",
						});
					},
				});
			}
			calendar.render();
		},
	};
})();

jQuery(document).ready(function () {
	KTAppsEducationSchoolCalendar.init();
});
