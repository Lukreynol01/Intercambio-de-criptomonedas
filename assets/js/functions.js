$(document).ready(function() {
	$("#exchange_button").hover(
		function() {
			$(this).find("i").addClass("fa-spin");
		},
		function() {
			$(this).find("i").removeClass("fa-spin");
		}
	);
	
	$("#exchange_form #exchange_button").hover(
		function() {
			$(this).find("i").addClass("fa-spin");
		},
		function() {
			$(this).find("i").removeClass("fa-spin");
		}
	);
	
	var buyName = $("#mainCompanySend").val();
	mainChangeSellCompanyImage(buyName);
	var sellName = $("#mainReceiveList").val();
	mainChangeBuyCompanyImage(sellName);
	loadSellCurrencies();
	$("#exchange_form #company_from").val(buyName);
	$("#exchange_form #company_to").val(sellName);
});

function mainChangeSellValue(value) {
	$("#exchange_form #amount_from").val(value);
	calculateBuy();
}

function mainChangeSellCompany(value) {
	$("#exchange_form #company_from").val(value);
	$("#exchange_form #formCompanyFrom").html(value);
	mainChangeSellCompanyImage(value);
	loadReceiveList(value);
	mainChangeSellValue("0");
	mainChangeBuyValue("0");
        
} 

function mainChangeSellCompanyImage(value) {
	var url = $("#url").val();
	var path = "assets/icons/";
	if(value == "PayPal") { var icon = 'PayPal.png'; }
        else if(value == "MercadoPago") { var icon = 'logo-mp.png'; }
	else if(value == "Skrill") { var icon = 'Skrill.png'; }
	else if(value == "WebMoney") { var icon = 'WebMoney.png'; }
	else if(value == "Payeer") { var icon = 'Payeer.png'; }
	else if(value == "Perfect Money") { var icon = 'PerfectMoney.png'; }
	else if(value == "AdvCash") { var icon = 'AdvCash.png'; }
	else if(value == "OKPay") { var icon = 'OKPay.png'; }
	else if(value == "Entromoney") { var icon = 'Entromoney.png'; }
	else if(value == "SolidTrust Pay") { var icon = 'SolidTrustPay.png'; }
	else if(value == "2checkout") { var icon = '2checkout.png'; }
	else if(value == "Litecoin") { var icon = 'Litecoin.png'; }
	else if(value == "Dash") { var icon = 'Dash.png'; }
	else if(value == "Dogecoin") { var icon = 'Dogecoin.png'; }
	else if(value == "Payza") { var icon = 'Payza.png'; }
	else if(value == "Bitcoin") { var icon = 'Bitcoin.png'; }
	else if(value == "Bank Transfer") { var icon = 'BankTransfer.png'; }
	else if(value == "Western Union") { var icon = 'Westernunion.png'; }
	else if(value == "Moneygram") { var icon = 'Moneygram.png'; }
	else { var icon = 'Missing.png'; }
	var newImage = url + path + icon;
	$("#sellCompanyImage").attr("src",newImage);
	$("#exchange_form #sellCompanyImage").attr("src",newImage);
}

function mainChangeSellCurrency(value) {
	$("#exchange_form #currency_from").val(value);
	$("#exchange_form #formCurrencyFrom").html(value);
	loadBuyCurrencies();
	mainChangeSellValue("0");
	mainChangeBuyValue("0");
}

function mainChangeBuyValue(value) {
	$("#exchange_form #amount_to").val(value);
	calculateSell();
}

function mainChangeBuyCompany(value) {
	var from = $("#mainCompanySend").val();
	$("#exchange_form #company_to").val(value);
	$("#exchange_form #formCompanyTo").html(value);
	mainChangeBuyCompanyImage(value);
	loadSellCurrencies();
	mainChangeSellValue("0");
	mainChangeBuyValue("0");
	if(value == "Bitcoin") {
		$("#exchange_form #formAccount").show();
		$("#exchange_form #accAddress").show();
		$("#exchange_form #accAccount").hide();
	} else if(value == "Litecoin") {
		$("#exchange_form #formAccount").show();
		$("#exchange_form #accAddress").show();
		$("#exchange_form #accAccount").hide();
	} else if(value == "Dogecoin") {
		$("#exchange_form #formAccount").show();
		$("#exchange_form #accAddress").show();
		$("#exchange_form #accAccount").hide();
	} else if(value == "Bank Transfer") {
		$("#exchange_form #formAccount").hide();
		$("#exchange_form #formBank").show();
		$("#exchange_form #formWM").hide();
	} else if(value == "Western Union") {
		$("#exchange_form #formAccount").hide();
		$("#exchange_form #formBank").hide();
		$("#exchange_form #formWM").show();
	} else if(value == "Moneygram") {
		$("#exchange_form #formAccount").hide();
		$("#exchange_form #formBank").hide();
		$("#exchange_form #formWM").show();
	} else {
		$("#exchange_form #accAddress").hide();
		$("#exchange_form #accAccount").show();
	}
} 

function mainChangeBuyCompanyImage(value) {
	var url = $("#url").val();
	var path = "assets/icons/";
	if(value == "PayPal") { var icon = 'PayPal.png'; }
        else if(value == "MercadoPago") { var icon = 'logo-mp.png'; }
	else if(value == "Skrill") { var icon = 'Skrill.png'; }
	else if(value == "WebMoney") { var icon = 'WebMoney.png'; }
	else if(value == "Payeer") { var icon = 'Payeer.png'; }
	else if(value == "Perfect Money") { var icon = 'PerfectMoney.png'; }
	else if(value == "AdvCash") { var icon = 'AdvCash.png'; }
	else if(value == "OKPay") { var icon = 'OKPay.png'; }
	else if(value == "Entromoney") { var icon = 'Entromoney.png'; }
	else if(value == "SolidTrust Pay") { var icon = 'SolidTrustPay.png'; }
	else if(value == "2checkout") { var icon = '2checkout.png'; }
	else if(value == "Litecoin") { var icon = 'Litecoin.png'; }
	else if(value == "Dash") { var icon = 'Dash.png'; }
	else if(value == "Dogecoin") { var icon = 'Dogecoin.png'; }
	else if(value == "Payza") { var icon = 'Payza.png'; }
	else if(value == "Bitcoin") { var icon = 'Bitcoin.png'; }
	else if(value == "Bank Transfer") { var icon = 'BankTransfer.png'; }
	else if(value == "Western Union") { var icon = 'Westernunion.png'; }
	else if(value == "Moneygram") { var icon = 'Moneygram.png'; }
	else { var icon = 'Missing.png'; }
	var newImage = url + path + icon;
	$("#buyCompanyImage").attr("src",newImage);
	$("#exchange_form #buyCompanyImage").attr("src",newImage);
}

function mainChangeBuyCurrency(value) {
	$("#exchange_form #currency_to").val(value);
	$("#exchange_form #formCurrencyTo").html(value);
	mainChangeSellValue("0");
	mainChangeBuyValue("0");
	loadRate();
	loadReserve();
}

function loadReceiveList(from) {
	var url = $("#url").val();
	var data_url = url + "requests/loadReceiveList.php?from="+from;
	$.ajax({
		type: "GET",
		url: data_url,
		dataType: "html",
		success: function (data) {
			$("#mainReceiveList").html(data);
			var companyReceive = $("#mainReceiveList").val();
			$("#exchange_form #formCompanyTo").html(companyReceive);
			$("#exchange_form #company_to").val(companyReceive);
			var to = $("#mainReceiveList").val();
			mainChangeBuyCompanyImage(to);
			loadSellCurrencies();
		}
	});
}

function loadSellCurrencies() {
	var url = $("#url").val();
	var from = $("#mainCompanySend").val();
	var to = $("#mainReceiveList").val();
	var data_url = url + "requests/loadSellCurrencies.php?from="+from+"&to="+to;
	$.ajax({
		type: "GET",
		url: data_url,
		dataType: "html",
		success: function (data) {
			$("#mainCurrencySend").html(data);
			var sendCurrency = $("#mainCurrencySend").val();
			$("#exchange_form #formCurrencyFrom").html(sendCurrency);
			$("#exchange_form #currency_from").val(sendCurrency);
			loadBuyCurrencies();
		}
	});
}

function loadBuyCurrencies() {
	var url = $("#url").val();
	var from = $("#mainCompanySend").val();
	var to = $("#mainReceiveList").val();
	var currency_from = $("#mainCurrencySend").val();
	var data_url = url + "requests/loadBuyCurrencies.php?from="+from+"&to="+to+"&currency_from="+currency_from;
	$.ajax({
		type: "GET",
		url: data_url,
		dataType: "html",
		success: function (data) {
			$("#mainCurrencyReceive").html(data);
			var receiveCurrency = $("#mainCurrencyReceive").val();
			$("#exchange_form #formCurrencyTo").html(receiveCurrency);
			$("#exchange_form #currency_to").val(receiveCurrency);
			loadRate();
			loadReserve();
		}
	});
}

function loadRate() {
	var url = $("#url").val();
	var from = $("#mainCompanySend").val();
	var to = $("#mainReceiveList").val();
	var currency_from = $("#mainCurrencySend").val();
	var currency_to = $("#mainCurrencyReceive").val();
	var data_url = url + "requests/loadRate.php?from="+from+"&to="+to+"&currency_from="+currency_from+"&currency_to="+currency_to;
	$.ajax({
		type: "GET",
		url: data_url,
		dataType: "html",
		success: function (data) {
			$("#mainRate").html(data);
		}
	});
}

function loadReserve() {
	var url = $("#url").val();
	var from = $("#mainCompanySend").val();
	var to = $("#mainReceiveList").val();
	var currency_from = $("#mainCurrencySend").val();
	var currency_to = $("#mainCurrencyReceive").val();
	var data_url = url + "requests/loadReserve.php?from="+from+"&to="+to+"&currency_from="+currency_from+"&currency_to="+currency_to;
	$.ajax({
		type: "GET",
		url: data_url,
		dataType: "html",
		success: function (data) {
			$("#mainReserve").html(data);
		}
	});
}

function calculateBuy() {
	var url = $("#url").val();
	var from = $("#mainCompanySend").val();
	var to = $("#mainReceiveList").val();
	var currency_from = $("#mainCurrencySend").val();
	var currency_to = $("#mainCurrencyReceive").val();
	var amount = $("#mainAmountSend").val();
	var data_url = url + "requests/calculateBuy.php?from="+from+"&to="+to+"&currency_from="+currency_from+"&currency_to="+currency_to+"&amount="+amount;
	
        $.ajax({
		type: "GET",
		url: data_url,
		dataType: "json",
		success: function (data) {
                    $("#mainAmountReceive").val(data);
                    $("#exchange_form #amount_to").val(data);
			
			
		}
	});
}

function calculateSell() {
	var url = $("#url").val();
	var from = $("#mainCompanySend").val();
	var to = $("#mainReceiveList").val();
	var currency_from = $("#mainCurrencySend").val();
	var currency_to = $("#mainCurrencyReceive").val();
	var amount = $("#mainAmountReceive").val();
	var data_url = url + "requests/calculateSell.php?from="+from+"&to="+to+"&currency_from="+currency_from+"&currency_to="+currency_to+"&amount="+amount;
	$.ajax({
		type: "GET",
		url: data_url,
		dataType: "json",
                success: function (data) {
                    $("#mainAmountSend").val(data);
                    $("#exchange_form #amount_from").val(data);
			
			
		}
	});
}

function validateForm() {
	var url = $("#url").val();	
	var data_url = url + "requests/validateForm.php";
	$.ajax({
		type: "POST",
		url: data_url,
		data: $("#exchange_data_form").serialize(),
		dataType: "json",
		success: function (data) {
			if(data.status == "success") {
				confirmExchange(data.msg);
			} else {
				$("#validate_results").html(data.msg);
			}
		}
	});
}

function confirmExchange(id) {
	var url = $("#url").val();
	var data_url = url + "requests/confirmExchange.php?id="+id;
	$.ajax({
		type: "GET",
		url: data_url,
		dataType: "html",
		success: function (data) {
			$("#exchange_form #exchange_results").hide();
			$("#exchange_form #exchange_confirm").show();
			$("#exchange_form #exchange_confirm").html(data);
		}
	});
}

function makeExchange(id,amount,currency) {
	var url = $("#url").val();
	var data_url = url + "requests/makeExchange.php?id="+id+"&amount="+amount+"&currency="+currency;
	$.ajax({
		type: "GET",
		url: data_url,
		dataType: "html",
		success: function (data) {
			$("#exchange_form #exchange_results").hide();
			$("#exchange_form #exchange_confirm").hide();
			$("#exchange_form #exchange_payment").show();
			$("#exchange_form #exchange_payment").html(data);
		}
	});
}

function cancelExchange(id) {
	var url = $("#url").val();
	var data_url = url + "requests/cancelExchange.php?id="+id;
	$.ajax({
		type: "GET",
		url: data_url,
		dataType: "html",
		success: function (data) {
			$("#exchange_form #exchange_results").hide();
			$("#exchange_form #exchange_confirm").show();
			$("#exchange_form #exchange_confirm").html(data);
		}
	});
}

function trackExchange() {
	var url = $("#url").val();
	var track_id = $("#track_id").val();
	var track_location = url+"exchange/"+track_id;
	window.location.href=track_location;
}
function selecciona_value(objInput) { 

    var valor_input = objInput.value; 
    var longitud = valor_input.length; 

    if (objInput.setSelectionRange) { 
        objInput.focus(); 
        objInput.setSelectionRange (0, longitud); 
    } 
    else if (objInput.createTextRange) { 
        var range = objInput.createTextRange() ; 
        range.collapse(true); 
        range.moveEnd('character', longitud); 
        range.moveStart('character', 0); 
        range.select(); 
    } 
} 