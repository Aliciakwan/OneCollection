/**
 * @author Christopher Wallace
 */

jQuery(window).load(function(){

  // Lazy Load images below the fold
  jQuery(".content img.thumbnail").lazyload();

  // The magic sliding panels
	jQuery('.entry-content a span.slide-title, span.slide-excerpt').css({
		opacity : '0.0'
	}).parent('a').append('<span class="cover-up"></span>');
	jQuery('.entry-content a').mouseover(function(e){
      jQuery(this).find('img.thumbnail').stop().animate({
	  	marginTop : '-200px'
	  }, 100).parent('a').find('span.slide-title, span.slide-excerpt').stop().fadeTo("slow",1.0);
	});
	jQuery('.entry-content a').mouseout(function(e){
      jQuery(this).find('img.thumbnail').stop().animate({
	  	marginTop : '0'
	  }, 100).parent('a').find('span.slide-title, span.slide-excerpt').stop().fadeTo("slow",0.0);
	});
  
  // Comment Author URL hover effect
  jQuery('.comment-author a.url').mouseover(function(e){
  	var url = jQuery(this).attr('href');
	jQuery(this).parent('span').append('<span class="hover-url">'+url+'</span>');
  })
  jQuery('.comment-author a.url').mouseout(function(e){
	jQuery(this).parent('span').find('.hover-url').remove();
  })
  
  jQuery('#footer .widgetcontainer:nth-child(3n+1)').addClass('reset');
  jQuery('.ie6 #footer .widgetcontainer:nth-child(3n+1),.ie7 #footer .widgetcontainer:nth-child(3n+1)').css({
    clear : 'left'
  });

});