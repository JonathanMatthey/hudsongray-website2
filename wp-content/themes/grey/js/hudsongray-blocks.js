!function(global) {
  'use strict';

  function Blocks(options) {
    this.options = options || {};
    this.$blocks = $('.block--hero');
    this.$blockTitles = $('.block__title');

    this.initialize().bindEvents();
  }

  Blocks.prototype.initialize = function initialize() {
    this.$blockTitles.fitText(1.1);
    return this;
  }

  Blocks.prototype.setBlockSize = function setBlockSize(block) {
    var $element = $(block),
        $elementChild = $element.find('.block__inner'),
        elementOffset;

    if (global.dimensions.heightWithOffset < $elementChild.outerHeight() + 100) {
      $elementChild
        .css('height', '')
        .css('top', '')
        .css('margin', '50px auto');
    }
    else {
      elementOffset = Math.abs((global.dimensions.heightWithOffset - $elementChild.outerHeight()) / 2 + 5);

      $element.css('height', global.dimensions.heightWithOffset);

      $elementChild.css({
        margin: '',
        top: elementOffset
      })
    }
  }

  Blocks.prototype.bindEvents = function bindEvents() {
    var _this = this;

    this.$blocks.each(function(id, block) {
      _this.setBlockSize(block);

      $(window).bind('resize', function() {
        _this.setBlockSize(block);
      });
    });

    $(window).bind('enterBreakpoint320', function() {
      _this.$blockTitles.fitText(0.65);
    });

    $(window).bind('exitBreakpoint320', function() {
      _this.$blockTitles.fitText(0.9);
    });

    $('.block--touch').bind('click', function(event) {
      event.preventDefault();
      var _this = $(this);

      $('html').animate({
        scrollTop: _this.position().top + _this.height()
      }, 400);
    });

    return this;
  }

  global.Blocks = Blocks;
}(Ustwo);

var Blocks = new Ustwo.Blocks();
