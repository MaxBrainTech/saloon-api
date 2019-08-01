$(document).ready(function(e) {
    
	//animações home
	var animateHome = function(){
		
		//logo
		$("#home .homeLogoEasyMop").fadeIn(800, function(){
			
			//balde
			$("#home .homeIMGBalde").fadeIn(800);
			
			//vassoura
			$("#home .homeIMGEasyMop").stop(true, false).animate({ "left": "111px"}, {
				duration: 500, 
				easing: "easeInOutCubic"
			});
			
			
			
			
			
			
			
			
			
			
			
			setTimeout(function(){
				//circulo texto
				$("#home .siteCircle01").fadeIn(600, function(){});
				
				setTimeout(function(){
					
					//img circulo 01
					$("#home .siteCircleIMG01 .imgHolder").animate({ 
						width:"165px", 
						height:"167px", 
						"margin-left": "-82.5px", 
						"margin-top": "-83.5px" 
					}, 
					{ 
						easing: "easeOutBounce", 
						duration: 500
					});
					
					setTimeout(function(){
						//img circulo 03
						$("#home .siteCircleIMG03 .imgHolder").animate({ 
							width:"114px", 
							height:"114px", 
							"margin-left": "-57px", 
							"margin-top": "-57px" 
						}, 
						{ 
							easing: "easeOutBounce", 
							duration: 500
						});
					}, 300);
					
					setTimeout(function(){
						//img circulo 02
						$("#home .siteCircleIMG02 .imgHolder").animate({ 
							width:"133px", 
							height:"133px", 
							"margin-left": "-66.5px", 
							"margin-top": "-66.5px" 
						}, 
						{ 
							easing: "easeOutBounce", 
							duration: 500
						});
					}, 600);
					
					setTimeout(function(){
						
						$("#home .siteNavigation").fadeIn("slow");
						
						//tag
						$("#home .siteNavigation li.current .tag").animate({ "width": "135px"}, {
							duration: 800, 
							easing: "easeOutBounce"
						});
					}, 900); 
					
					setTimeout(function(){
						$("#home .siteScrollBtn").fadeIn(800, function(){});
						$("#home .siteScrollBtn .icon").effect("bounce", {  duration: 1000, times: 5 });
						
						setTimeout(function(){
							$("#home .siteLogoCasdan").fadeIn("slow");
						}, 300);
						
					}, 1200);
				
				}, 400);
				
			}, 600);
			
		});
	
	};
	
	//============ #home =============
	
	$("#home .homeLogoEasyMop").hide();
	$("#home .homeIMGBalde").hide();
	$("#home .homeIMGEasyMop").css({ "left": $(document).width() + "px" });
	
	$("#home .siteNavigation li.current .tag").css({ "width": "0px" });
	$("#home .siteNavigation").hide();
	$("#home .siteCircle01").hide();
	$("#home .siteScrollBtn").hide();
	$("#home .siteLogoCasdan").hide();

	$("#home .siteCircleIMG01 .imgHolder, #home .siteCircleIMG02 .imgHolder, #home .siteCircleIMG03 .imgHolder").css({ 
		"border-radius": "50%", 
		width:"0px", 
		height:"0px", 
		"margin-left":"0px", 
		"margin-top": "0px"
	});
	
	//============ #sobre =============
	
	$("#sobre .siteCircle03").hide();
	$("#sobre .siteCircle04").hide(); 
	
	$("#sobre .siteNavigation li.current .tag").css({ "width": "0px" });
	$("#sobre .siteNavigation").hide();
	
	$("#sobre .siteCircle02 .textWrap").hide();
	$("#sobre .siteCircle02").css({ 
		"border-radius": "50%", 
		width:"0px",
		height:"0px", 
		"margin-left": "139.5px", 
		"margin-top": "139.5px"
	});
	
	$("#sobre .siteCircleIMG01, #sobre .siteCircleIMG03").hide().css({
		"z-index": "1", 
		left: "50px"
	});
	
	//============ #caracteristicas =============
	
	$("#caracteristicas .caracteristicasHaste .textWrap h2").hide();
	$("#caracteristicas .caracteristicasMop .textWrap h2").hide();
	$("#caracteristicas .caracteristicasBalde .textWrap h2").hide();
	
	$("#caracteristicas .caracteristicasHaste .textWrap ul").css({ left: "-160px" });
	$("#caracteristicas .caracteristicasMop .textWrap ul").css({ left: "-160px" });
	$("#caracteristicas .caracteristicasBalde .textWrap ul").css({ left: "-160px" });
	
	$("#caracteristicas .siteNavigation li.current .tag").css({ "width": "0px" });
	$("#caracteristicas .siteNavigation").hide();
	
	$("#caracteristicas .caracteristicasMopExtraIncluso .icon").css({left: "0px"});
	$("#caracteristicas .caracteristicasFooterText .textWrap").hide();
	
	$("#caracteristicas .caracteristicasHasteIMG").hide();
	$("#caracteristicas .caracteristicasMopIMG").hide();
	$("#caracteristicas .caracteristicasBaldeIMG").hide();
	
	//============ #comoUsar =============
	
	$("#comoUsar .siteNavigation li.current .tag").css({ "width": "0px" });
	$("#comoUsar .siteNavigation").hide();
	
	$("#comoUsar .comoUsarVideo").hide();
	$("#comoUsar .comoUsarText").hide();
	
	$("#comoUsar .siteCircleIMG03").hide();
	$("#comoUsar .siteCircleIMG04").css({ top: "384px" }).hide();
	$("#comoUsar .downloadManualInstrucoes").css({ top: "400px" }).hide();
		
	$("#comoUsar .siteCircleIMG01 .imgHolder").css({ 
		"border-radius": "50%", 
		width:"0px", 
		height:"0px", 
		"margin-left":"0px", 
		"margin-top": "0px"
	});
	
	//============ #representantes =============
	
	$("#representantes .siteCircle03").hide();
	$("#representantes .siteCircle04").hide();
	
	$("#representantes .siteCircleIMG05, #representantes .siteCircleIMG06").hide().css({
		"z-index": "1", 
		left: "400px"
	});
	
	$("#representantes .siteNavigation li.current .tag").css({ "width": "0px" });
	$("#representantes .siteNavigation").hide();
	
	//============ #contato =============
	
	$("#contato .siteNavigation li.current .tag").css({ "width": "0px" });
	$("#contato .siteNavigation").hide();
	
	$("#contato .siteCircleIMG07 .imgHolder").css({ 
		"border-radius": "50%", 
		width:"0px", 
		height:"0px", 
		"margin-left":"0px", 
		"margin-top": "0px"
	});
		
	$("#contato .siteCircle02 p").hide();
	$("#contato .siteCircle02").css({ 
		"border-radius": "50%", 
		width:"0px", 
		height:"0px", 
		"margin-left":"139.5px", 
		"margin-top": "139.5px"
	});
	
	$("#contato .siteCircle03").hide();
	$("#contato .siteCircle04").hide();
	
	$("#contato .contatoSubmitButton").css({ top: "-54px" });
	
	animateHome();

	$(window).on("scroll", function(){
		
		//console.log($(window).scrollTop());
		
		//home -------------------------------------------------
		if ($(window).scrollTop() >= 0 && $(window).scrollTop() < 600){
			
			//animateHome();
		
		//sobre -------------------------------------------------
		
		} else if ($(window).scrollTop() >= 600 && $(window).scrollTop() < 1600){
			
			$("#sobre .siteCircle04").fadeIn(800);
			
			setTimeout(function(){
									
				$("#sobre .siteCircle02").animate({ 
					width:"279px", 
					height:"279px", 
					"margin-left": "0px", 
					"margin-top": "0px"
				}, 
				{ 
					easing: "easeOutBounce", 
					duration: 600, 
					complete: function(){
						$("#sobre .siteCircle02 .textWrap").fadeIn(800);
						
					} 
				});
				
			}, 600);
			
			setTimeout(function(){
				$("#sobre .siteCircleIMG01").fadeIn(800);
				$("#sobre .siteCircleIMG01").animate({
					left: "522px"
				}, { 
					duration: 800, 
					easing: "easeOutElastic"
				});
				setTimeout(function(){ $("#sobre .siteCircleIMG01").css({"z-index": "5"});  }, 1000);
			}, 400);
			
			setTimeout(function(){
				$("#sobre .siteCircleIMG03").fadeIn(800);
				$("#sobre .siteCircleIMG03").animate({
					left: "500px"
				}, { 
					duration: 800, 
					easing: "easeOutElastic", 
					complete: function(){
						
					} 
				}); 
				setTimeout(function(){ 
					$("#sobre .siteCircleIMG03").css({"z-index": "6"});  
				}, 1000);
			}, 600);
			
			setTimeout(function(){
				$("#sobre .siteCircle03").fadeIn(800);
			}, 1800);
			
			setTimeout(function(){
					
				$("#sobre .siteNavigation").fadeIn("slow");
				
				//tag
				$("#sobre .siteNavigation li.current .tag").animate({ "width": "135px"}, {
					duration: 800, 
					easing: "easeOutBounce"
				});
				
			}, 2000); 
			
		//características -------------------------------------------------
		
		} else if ($(window).scrollTop() >= 1600 && $(window).scrollTop() < 2600){
			
			//setTimeout(function(){
				$("#caracteristicas .caracteristicasHasteIMG").fadeIn(800);
				$("#caracteristicas .caracteristicasHaste .textWrap h2").fadeIn(800);
				$("#caracteristicas .caracteristicasHaste .textWrap ul").animate({left: "0px"}, {duration: 800, easing: "easeInOutElastic"});
			//}, 200);
			
			setTimeout(function(){
				$("#caracteristicas .caracteristicasMopIMG").fadeIn(800);
				$("#caracteristicas .caracteristicasMop .textWrap h2").fadeIn(800);
				$("#caracteristicas .caracteristicasMop .textWrap ul").animate({left: "0px"}, {duration: 800, easing: "easeInOutElastic"});
			}, 200);
			
			setTimeout(function(){
				$("#caracteristicas .caracteristicasBaldeIMG").fadeIn(800);
				$("#caracteristicas .caracteristicasBalde .textWrap h2").fadeIn(800);
				$("#caracteristicas .caracteristicasBalde .textWrap ul").animate({left: "0px"}, {duration: 800, easing: "easeInOutElastic"});
			}, 400);
			
			if ($("#caracteristicas .caracteristicasMopExtraIncluso .icon").css("left") == "0px"){
				$("#caracteristicas .caracteristicasMopExtraIncluso .icon").animate({left: "537px"}, {duration: 800});
				$("#caracteristicas .caracteristicasMopExtraIncluso .icon").animateRotate(360, 800, "linear", function(){}); 
			}
			
			setTimeout(function(){
				$("#caracteristicas .caracteristicasFooterText .textWrap").fadeIn(800);
			}, 300);
			
			setTimeout(function(){
					
				$("#caracteristicas .siteNavigation").fadeIn("slow");
				
				//tag
				$("#caracteristicas .siteNavigation li.current .tag").animate({ "width": "135px"}, {
					duration: 800, 
					easing: "easeOutBounce"
				});
			}, 1000); 
		
		//como usar -------------------------------------------------
		
		} else if ($(window).scrollTop() >= 2600 && $(window).scrollTop() < 3600){
			
			$("#comoUsar .comoUsarVideo").fadeIn(600);
			
			setTimeout(function(){
				$("#comoUsar .siteCircleIMG04").show();
				$("#comoUsar .siteCircleIMG04").animate({top: "269px"}, { easing: "easeOutBack" });
				
				setTimeout(function(){
					$("#comoUsar .downloadManualInstrucoes").show();
					$("#comoUsar .downloadManualInstrucoes").animate({ top: "210px" }, { easing: "easeOutBack" });
				}, 300);
			}, 600);	
			
			setTimeout(function(){
				
				$("#comoUsar .siteCircleIMG01 .imgHolder").animate({ 
					width:"165px", 
					height:"167px", 
					"margin-left": "-82.5px", 
					"margin-top": "-83.5px" 
				}, 
				{ 
					easing: "easeOutBounce", 
					duration: 600
				});
				
			}, 1200);
			
			setTimeout(function(){
				$("#comoUsar .siteCircleIMG03").fadeIn(600);
			}, 1600);
			
			setTimeout(function(){
				$("#comoUsar .comoUsarText").fadeIn(800);
			}, 1800); 
			
			setTimeout(function(){
					
				$("#comoUsar .siteNavigation").fadeIn("slow");
				
				//tag
				$("#comoUsar .siteNavigation li.current .tag").animate({ "width": "135px"}, {
					duration: 800, 
					easing: "easeOutBounce"
				});
			}, 2000); 
		
		//representantes -------------------------------------------------
		
		} else if ($(window).scrollTop() >= 3600 && $(window).scrollTop() < 4600){
			
			$("#representantes .siteCircle04").fadeIn(600);
			
			setTimeout(function(){
				$("#representantes .siteCircle03").fadeIn(600);
			}, 400);
			
			setTimeout(function(){
				$("#representantes .siteCircleIMG05").fadeIn(800);
				$("#representantes .siteCircleIMG05").animate({
					left: "120px"
				}, { 
					duration: 800, 
					easing: "easeOutElastic", 
					complete: function(){ 
						
					} 
				});
				
				setTimeout(function(){ 
					$("#representantes .siteCircleIMG05").css({"z-index": "5"});  
				}, 1000);
			
			}, 300);
			
			setTimeout(function(){
				$("#representantes .siteCircleIMG06").fadeIn(800);
				$("#representantes .siteCircleIMG06").animate({
					left: "60px"
				}, { 
					duration: 800, 
					easing: "easeOutElastic", 
					complete: function(){
						
					} 
				}); 
				
				setTimeout(function(){ 
					$("#representantes .siteCircleIMG06").css({"z-index": "6"});  
				}, 1000);
				
			}, 500);
			
			setTimeout(function(){
					
				$("#representantes .siteNavigation").fadeIn("slow");
				
				//tag
				$("#representantes .siteNavigation li.current .tag").animate({ "width": "135px"}, {
					duration: 800, 
					easing: "easeOutBounce"
				});
			}, 1600); 
			
		
		//contato -------------------------------------------------
		
		} else if ($(window).scrollTop() >= 4600){
			
			$("#contato .siteCircle04").fadeIn(800, function(){
				$("#contato .contatoSubmitButton").animate({ 
					top: "0px" 
				}, { 
					duration: 600, 
					easing: "easeOutBounce" 
				});
			});
			
			$("#contato .siteCircle02").fadeIn(800);
			
			setTimeout(function(){
				$("#contato .siteCircle03").fadeIn(800);
				if ($("#contato .siteCircle03").is(":animated")){
					setTimeout(function(){
						if (!$("#contato .siteCircle03 .contatoIconPhone").is(":animated")){
							$("#contato .siteCircle03 .contatoIconPhone").effect("shake");
						}
					}, 200);
				}
			}, 1000);
			
			setTimeout(function(){
				
				$("#contato .siteCircle02").animate({ 
					width:"279px", 
					height:"279px", 
					"margin-left": "0px", 
					"margin-top": "0px" 
				}, 
				{ 
					easing: "easeOutBounce", 
					duration: 600
				});
				
				setTimeout(function(){
					$("#contato .siteCircle02 p").fadeIn(600);
				}, 400);
				
			}, 500);
			
			setTimeout(function(){
				
				$("#contato .siteCircleIMG07 .imgHolder").animate({ 
					width:"165px", 
					height:"167px", 
					"margin-left": "-82.5px", 
					"margin-top": "-83.5px" 
				}, 
				{ 
					easing: "easeOutBounce", 
					duration: 600
				});
				
			}, 1200);
			
			setTimeout(function(){
					
				$("#contato .siteNavigation").fadeIn("slow");
				
				//tag
				$("#contato .siteNavigation li.current .tag").animate({ "width": "135px"}, {
					duration: 800, 
					easing: "easeOutBounce"
				});
				
			}, 1600); 
			
		}
				
	});
	
	//menu principal navegação
	$(document).on("click", ".siteNavigation li a, .scrollTo", function(e){
		e.preventDefault();
		var $href = $(this).attr("href"), 
			$target = $($href);
			
		$("html, body").stop(true, false).animate({scrollTop: ($target.offset().top)}, {
			easing: 'easeInOutCirc', 
			duration: 1000, 
			complete: function(){
				window.location.hash = $href;
			}
		});
	});
	
	//scroll ao carregar site com âncora
	if (window.location.hash){
		$(".siteNavigation:eq(0) li a[href='" + window.location.hash + "']").click();
	}
	
	//over botão download manual
	$("#comoUsar .downloadManualInstrucoes").bind("mouseenter mouseleave", function(e){
		
		e.preventDefault();
		var $element = $(this);
		
		if (e.type == "mouseenter"){
			$element.find("p").stop(true, false).fadeOut("slow");
			$element.find("img").stop(true, false).animate({ width: "100px" });
		} else if (e.type == "mouseleave"){
			$element.find("img").animate({ width: "30px" });
			$element.find("p").fadeIn("slow");
		}
		
	});
	
	$("#contato .contatoFormMessage .contatoFormMessageClose").bind("click", function(e){
		e.preventDefault();
		$("#contato .contatoFormMessage").fadeOut(600); 
	});
		
	//contato
	$("#contato .contatoSubmitButton").bind("click", function(e){
		e.preventDefault(); 
		
		var $form = $("#contatoForm"), 
			validationTextContainer;
		
		$form.find(".validationText").animate({"width": "0px"}, {easing: "easeOutBounce", duration: 600}); 
		
		$form.find(".contatoFormMessage").fadeOut(600).removeClass("success");
		$form.find(".contatoFormMessage").addClass("loading").find("span").html("Carregando...");
		$form.find(".contatoFormMessage").fadeIn(600);
		
		$.ajax({
			type: "POST",
			url: $form.attr("action"),
			data: $form.serialize(),
			dataType: "html",
			success: function(response){ 
				
				response = jQuery.parseJSON(response); 
				
				if (response.status == true){
					
					$("#contato .contatoFormMessage").fadeOut(600, function(){
						$(this).
						removeClass("loading").
						addClass("success").
						find("span").html(response.message);
						$("#contato .contatoFormMessage").fadeIn(600);
						
						setTimeout(function(){ 
							$("#contato .contatoFormMessage").fadeOut(600); 
						}, 7000)
					}); 
					
				} else {
					
					$("#contato .contatoFormMessage").fadeOut(600, function(){
						validationTextContainer = $form.find("[name='" + response.field + "']").parent().find(".validationText"); 
						validationTextContainer.find("span").html(response.message);
						validationTextContainer.animate({"width": "200px"}, {easing: "easeOutBounce", duration: 600}); 
					}); 
					
				}
			}
		});
		
	});
	
	$(".facebookLikeContainer").bind("mouseenter mouseleave", function(e){
		var $element = $(this);
		if (e.type == "mouseenter"){ 
			$element.find(".likebutton").fadeIn(600);
			$element.find(".icon").stop(true, false).fadeOut(600);
		} else if (e.type == "mouseleave"){
			$element.find(".likebutton").stop(true, false).fadeOut(600);
			$element.find(".icon").fadeIn(600);
		}
	});
	
	//over menu navegação
	$(".siteNavigation ul li").bind("mouseenter mouseleave", function(e){
		var $element = $(this); 
		if (!$element.is(".current")){
			if (e.type == "mouseenter"){
				$element.find(".tag").animate({ "width": "135px"}, {
					duration: 600, 
					easing: "easeOutBounce"
				});
			} else if (e.type == "mouseleave"){
				$element.find(".tag").stop(true, false).animate({ "width": "0px"}, {
					duration: 200
				});
			}
		}
	});
	
	//over botão scroll home
	$("#home .siteScrollBtn .icon").bind("mouseenter", function(e){
		var $element = $(this); 
		if (!$element.is(":animated")){
			$element.effect("bounce", { duration: 1000, times: 5});
		}
	});
	
	//over logo interativa
	$(".footerLogoInterativacom").bind("mouseenter mouseleave", function(e){
		var $element = $(this); 
		if (!$element.is(".current")){
			if (e.type == "mouseenter"){
				$element.find(".icon").animate({ "width": "0px"}, {
					duration: 400/*, 
					easing: "easeOutBounce"*/
				});
				$element.find(".over").animate({ "width": "23px"}, {
					duration: 400/*, 
					easing: "easeOutBounce"*/
				});
			} else if (e.type == "mouseleave"){
				$element.find(".icon").stop(true, false).animate({ "width": "23px"}, {
					duration: 400
				});
				$element.find(".over").stop(true, false).animate({ "width": "0px"}, {
					duration: 400
				});
			}
		}
	});
	
});