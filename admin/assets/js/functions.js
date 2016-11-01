$(document).ready(function() {
	$("#currency_from").on("change",function() {
		$("#currency_code_from").html($(this).val());
	});
	
	$("#currency_to").on("change",function() {
		$("#currency_code_to").html($(this).val());
	});
});

function getCurrencies(from) {
	var data_url = "requests/getCurrencies.php?from="+from;
	$.ajax({
		type: "GET",
		url: data_url,
		dataType: "html",
		success: function (data) {
			$("#currency_to").html(data);
			$("#currency_code_to").html($("#currency_to").val());
			getCurrentRate();
		}
	});
}

function getCurrencies2(from) {
	var data_url = "requests/getCurrencies.php?from="+from;
	$.ajax({
		type: "GET",
		url: data_url,
		dataType: "html",
		success: function (data) {
			$("#c_to").html(data);
			$("#c_reserve").html($("#c_to").val());
			getCurrentReserve();
		}
	});
}

function getCurrentReserve() {
	var from = $("#c_from").val();
	var to = $("#c_to").val();
	var data_url = "requests/getCurrentReserve.php?from="+from+"&to="+to;
	$.ajax({
		type: "GET",
		url: data_url,
		dataType: "html",
		success: function (data) {
			$("#current_reserve").val(data);
		}
	});
}