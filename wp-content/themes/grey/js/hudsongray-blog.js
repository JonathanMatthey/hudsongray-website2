!function(global) {
  'use strict';

  var $body = $('body');
  var defaultOptions = {
    category: 'all',
    siteUrl: 'http://localhost'
  };

  function Blog(options) {
    this.isInitialized = false;

    this.options = $.extend(true, {}, defaultOptions, options);

    this.bindEvents();

    this.isSingleColumn = false;
    this.checkForMobile();

    // Initializes components
    this.searchBarHider = new Ustwo.SearchBarHider($('.blog__searchbox'));

    this.searchBox = new Ustwo.SearchBox($('.searchbox__search'));
    this.updateSearchBox();

    var _this = this;

    this.categorySelectBox = new Ustwo.SelectBox($('.searchbox__categories'), {
      onSelect: function (selected) {
        if (_this.isInitialized) {
          var url = _this.options.siteUrl + '/blog/';
          if (selected !== 'all') {
            url += selected;
          }
          window.location.href = url;
        }
      }
    });

    this.categorySelectBox.select(this.options.category);

    this.isInitialized = true;
  }


  Blog.prototype.resizeThumbs = function resizeThumbs() {
    var $articleThumbs = $('.blog__articles__item:not(.blog__articles__item--hero) .article__thumb:first'),
        $largeArticle = $('.blog__articles__item--hero'),
        height = $largeArticle.find('.article__thumb').height();

    $articleThumbs.outerHeight(height);
  };

  Blog.prototype.checkForMobile = function checkForMobile() {
    if (Response.viewportW() <= 768) {
      if (!this.isSingleColumn) {
        this.setupSingleColumn();
        this.isSingleColumn = true;
      }
    }
    else {
      this.isSingleColumn = false;
      this.resizeThumbs();
    }
  };

  Blog.prototype.setupSingleColumn = function setupSingleColumn() {
      var $articleThumbs = $('.blog__articles__item:not(.blog__articles__item--hero) .article__thumb');
      $articleThumbs.removeAttr("style");
  };

  Blog.prototype.updateSearchBox = function updateSearchBox() {
      var isOpenable = Response.viewportW() <= 540;
      this.searchBox.setOpenable(isOpenable);
  };

  Blog.prototype.bindEvents = function bindEvents() {
    var _this = this;

    $(window).bind("resize", function() {
      _this.checkForMobile();
    });

    Response.crossover(function () {
      _this.updateSearchBox();
    });

    return this;
  };

  global.Blog = Blog;
}(Ustwo);