$(document).ready(function(){
	$(".hideLogin").click(function(){
		$(".registerFrame.login").hide();
		$(".registerFrame.register").show();
	});
	$(".hideRegister").click(function(){
		$(".registerFrame.login").show();
		$(".registerFrame.register").hide();
	});
});