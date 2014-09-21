var Ustwo = {};

// Set Dimensions object
Ustwo.dimensions = {
  height: $(window).height(),
  heightWithOffset: $(window).height() / 2,
  width: $(window).width()
}

Ustwo.breakpoints = {
  'palmPortrait': {
    width: [0, 320],
    extension: 'iphone_portrait'
  },
  'palmLandscape': {
    width: [321, 539],
    extension: 'iphone_landscape'
  },
  'tabletPortrait': {
    width: [540, 767],
    extension: 'tablet_portrait'
  },
  'tabletLandscape': {
    width: [768, 1023],
    extension: 'desktop'
  },
  'desktop': {
    width: [1024, 1000000],
    extension: 'desktop'
  }
}

// Resize
$(window).bind('resize', function(event) {
  Ustwo.dimensions = {
    height: $(window).height(),
    heightWithOffset: $(window).height() / 2,
    width: $(window).width()
  }
});

// Onload
window.onload = function() {
  $('.wrapper').addClass("fadeIn");
  $('.wrapper').css('opacity',1);
};

// Setup jquery.response.js
var responseBreakpoints = [];
for (var breakpointName in Ustwo.breakpoints) {
  if (Ustwo.breakpoints.hasOwnProperty(breakpointName)) {
    breakpoint = Ustwo.breakpoints[breakpointName];
    responseBreakpoints.push(breakpoint.width[0]);
  }
}
Response.create({
    prop: "width",
    breakpoints: responseBreakpoints
});

// Footer scrollTop click event
$(".footer__scrolltop").bind("click", function(event) {
  event.preventDefault();

  $('html, body').animate({
    scrollTop: 0
  }, 400);
});