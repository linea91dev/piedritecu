"use strict";

// Shared Colors Definition
const primary = "#6993FF";
const success = "#1BC5BD";
const info = "#8950FC";
const warning = "#FFA800";
const danger = "#F64E60";

// Class definition
function generateBubbleData(baseval, count, yrange) {
	var i = 0;
	var series = [];
	while (i < count) {
		var x = Math.floor(Math.random() * (750 - 1 + 1)) + 1;
		var y = Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;
		var z = Math.floor(Math.random() * (75 - 15 + 1)) + 15;

		series.push([x, y, z]);
		baseval += 86400000;
		i++;
	}
	return series;
}

function generateData(count, yrange) {
	var i = 0;
	var series = [];
	while (i < count) {
		var x = "w" + (i + 1).toString();
		var y = Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;

		series.push({
			x: x,
			y: y,
		});
		i++;
	}
	return series;
}

var KTApexChartsDemo = (function () {
	// Private functions

	return {
		// public functions
		init: function () {
			_cambios();
		},
	};
})();

jQuery(document).ready(function () {
	KTApexChartsDemo.init();
});
