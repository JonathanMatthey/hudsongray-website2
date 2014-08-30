!function(global) {
  'use strict';

  var $body = $('body');

  function SearchBarHider($el, options) {
    this.$el = $el;

    this.scrollValue = this.getSearchBarSize();
    this.isHidden = false;

    this.update();

    this.bindEvents();
  };


  SearchBarHider.prototype.getSearchBarSize = function getSearchBarSize() {
    var searchBoxHeight = this.$el.outerHeight(true);
    var headerPaddingTop = 0;//parseInt($('.blog__header').css('paddingTop'));
    var result = searchBoxHeight + headerPaddingTop;
    return result;
  };

  SearchBarHider.prototype.update = function update() {
    if (Response.viewportW() <= 540) {
      this.hide();
    } else {
      this.show();
    }
  };

  SearchBarHider.prototype.hide = function hide() {
    if (this.isHidden) {
      return;
    }

    var currentScrollTop = $body.scrollTop();
    $body.scrollTop(currentScrollTop + this.scrollValue);

    this.isHidden = true;
  };

  SearchBarHider.prototype.show = function show() {
    if (!this.isHidden) {
      return;
    }

    var currentScrollTop = $body.scrollTop();
    $body.scrollTop(currentScrollTop - this.scrollValue);

    this.isHidden = false;
  };

  SearchBarHider.prototype.bindEvents = function bindEvents() {
    var _this = this;

    // Handles the change of breakpoint
    Response.crossover(function () {
      _this.update();

    });
  };

  global.SearchBarHider = SearchBarHider;
}(Ustwo);