!function(global) {
  'use strict';

  var $imgBlocks;

  function ResponsiveImageSwitcher(basePath) {
    $imgBlocks = $("[data-base]");

    this.basePath = basePath

    this.refresh();

    this.bindEvents();
  };

  ResponsiveImageSwitcher.prototype.swapBackground = function swapBackground(breakpoint, extensionName) {
    var _this = this;

    $imgBlocks.each(function(i, el) {
      var element = $(this),
          basePath = element.attr('data-base'),
          fileExt = element.attr('data-ext'),
          bgImage = basePath + extensionName + fileExt,
          bgDefault = element.attr('data-default') + fileExt;

      if (element.attr("data-" + breakpoint) !== "false") {
        element.css({
          backgroundImage: "url(" + _this.basePath + "/" + bgImage + ")"
        });
      }
      else {
        element.css({
          backgroundImage: "url(" + _this.basePath + "/" + bgDefault + ")"
        });
      }
    });
  };

  ResponsiveImageSwitcher.prototype.getCurrentBreakpoint = function getCurrentBreakpoint() {
    var result;

    for (var breakpointName in Ustwo.breakpoints) {
      if (Ustwo.breakpoints.hasOwnProperty(breakpointName)) {
        var breakpoint = Ustwo.breakpoints[breakpointName];
        var width = breakpoint.width;

        if (Response.band(width[0], width[1])) {
          result = {
            name: breakpointName,
            properties: breakpoint
          };
          break;
        } 
      }
    }

    return result;
  }

  ResponsiveImageSwitcher.prototype.refresh = function refresh() {
      var breakpoint = this.getCurrentBreakpoint();
      var breakpointName = breakpoint.name
      var extension = breakpoint.properties.extension;
      this.swapBackground(breakpointName, extension);
  }


  ResponsiveImageSwitcher.prototype.bindEvents = function bindEvents() {
    var _this = this;

    // Triggered at every breakpoint crossing
    Response.crossover(function () {
      _this.refresh();
    });
  };

  global.ResponsiveImageSwitcher = ResponsiveImageSwitcher;
}(Ustwo);