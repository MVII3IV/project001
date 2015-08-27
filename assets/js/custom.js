jQuery(document).ready(function($){
	"use strict";
	
	/* ============== slider1 ============== */
	
	$('#slider1').tinycarousel()
	
	
	/* ============== owl.carousel ============== */
	
	$("#owl-demo").owlCarousel({
      navigation : true, // Show next and prev buttons
      slideSpeed : 300,
      singleItem:true,
	  autoPlay : 4000,
      stopOnHover : true,
	  navigationText : false,
      navigation:true,
      paginationSpeed : 1000,
      goToFirstSpeed : 2000,
      singleItem : true,
      autoHeight : true
  	});
  
  
	/* ============== TWITTER ============== */
	
	$(".twitter-widget").each(function () {
		var $this = $(this)
		var count = $this.data('count') || ''
		var username = $this.data('username') || ''
		var content = ''

		$.ajax({
			url: 'php/tweets-json.php',
			type: 'GET',
			dataType: 'json',
			data: { count: count, username: username }
		}).done(function (json) {

			for (var i in json) {
				content += "<li>"
				content +=   json[i].tweet
				content +=   "<div>"+ json[i].date +"</div>"
				content += "</li>\n"
			}
			
			$this.html(content)
		})
	});
	

	/* ============== DOWNMENU ============== */
	
	$(".nav>li").hover(function(){
		$(this).find("ul>li").stop().slideToggle(300)
		$(this).find("ul>li ul>li").stop().slideToggle(300)
	});
	
	
	/* ============== WOW ============== */ 
	
	new WOW().init();
	

	/* ============== Google Maps ============== */
	
	var $map = $('#map')
	if( $map.length ) {
		$map.gMap({
			address: 'No: 58 A, East Madison St, Baltimore, MD, USA',
			zoom: 16,
			markers: [
				{ 'address' : 'No: 58 A, East Madison St, Baltimore, MD, USA' }
			]
		});
	}
		
	
	/* ============== color-box option ============== */
	
	$(".icon-option").click(function(){
		$(".color-option").slideToggle(600)
	});
		
	var colorLi = $(".color-option ul li")
	
	colorLi
		.eq(0).css("backgroundColor","#ec407a").end()
		.eq(1).css("backgroundColor","#6b798f").end()
		.eq(2).css("backgroundColor","#88c425").end()
		.eq(3).css("backgroundColor","#3daa62").end()
		.eq(4).css("backgroundColor","#ff9b00").end()
		.eq(5).css("backgroundColor","#894997").end()
		.eq(6).css("backgroundColor","#e03e25").end()
		.eq(7).css("backgroundColor","#2baab1").end()
		.eq(8).css("backgroundColor","#ffb546").end()
		.eq(9).css("backgroundColor","#29b6f6");
	
	colorLi.click(function(){
		$("link[href*='theme']").attr("href",$(this).attr("data-value"));
	});	
	
	
	/* ============== scroll-top ============== */
	
	var scrollButton = $(".scroll-top");
	
	$(window).scroll(function(){
		$(this).scrollTop() >= 700 ? scrollButton.slideDown(500) : scrollButton.slideUp(500);
	});
	
	scrollButton.click(function(){
			$("html,body").animate({scrollTop:0}, 800)
	});
	
});//jQuery
