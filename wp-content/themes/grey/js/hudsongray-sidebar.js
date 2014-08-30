!function(global) {
  'use strict';

  function Sidebar(options) {
    this.options = options || {};

    this.$body = $('body');
    this.$toggleElements = $('.nav__menu, .toggle__bar');

    this.colours = [
      'piglet', 'passion', 'ohra', 'honey', 'jeezz',
      'pot', 'mare', 'blu', 'navy', 'rain'
    ];

    this.bindEvents();
  }

  Sidebar.prototype.selectRandomColour = function selectRandomColour() {
    var randomNumber = Math.floor(Math.random() * ((this.colours.length) - 0) + 0)
    return this.colours[randomNumber];
  }

  Sidebar.prototype.toggleSidebar = function(delay) {
    var _this = this;

    if (this.$body.hasClass('is-open')) {
      this.$body.removeClass('is-open').addClass('is-closed');

      setTimeout(function() {
        _this.$toggleElements.removeClass(_this.colourClass);
      }, delay)
    }
    else {
      this.$body.removeClass('is-closed').addClass('is-open');
      this.colourClass = "u-bg-" + this.selectRandomColour();
      this.$toggleElements.addClass(this.colourClass);
    }

    return this;
  }

  Sidebar.prototype.bindEvents = function bindEvents() {
    var _this = this;

    $('.nav__toggle').bind('mousedown touchstart', function(event) {
      event.preventDefault();
      _this.toggleSidebar(300);
    });

    $('.wrapper').bind('mousedown touchstart', function(event) {
      if (_this.$body.hasClass('is-open')) {
        event.preventDefault();
        _this.toggleSidebar();
      };
    });

    $(window).bind('resize', function(event) {
      if (_this.$body.hasClass('is-open')) {
        _this.toggleSidebar();
      };
    });
  }

  global.Sidebar = Sidebar;
}(Ustwo);

var Sidebar = new Ustwo.Sidebar();