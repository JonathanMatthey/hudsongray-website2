!function (global) {

  var defaultOptions = {
    onLoad: function() {}
  };

  function BlogLoadNext($el, options) {
    this.$el = $el;
    this.$parent = $el.parent();

    this.$blogArticles = $('.blog__articles');
    this.$label = $('.posts-to-load', this.$el);

    this.options = $.extend(true, {}, defaultOptions, options);

    this.isLoading = false;

    var postsPerPage = 11;

    this.posts = {
      number: options.postNumber,
      remaining: options.postNumber - postsPerPage,
      pages: {
        total: Math.ceil( options.postNumber / postsPerPage),
        current: 1,
        posts: postsPerPage
      }
    };

    this.update();
    this.bindEvents();
  }

  BlogLoadNext.prototype.update = function update() {
    if (this.posts.remaining > 0) {
      // Updates the label text
      this.$label.text(this.posts.remaining);
    } else {
      this.$el.remove();
    }
  };

  BlogLoadNext.prototype.bindEvents = function bindEvents() {
    var _this = this;
    var posts = this.posts;

    this.$el.bind('click touchstart', function (e) {
      e.preventDefault();

      if (this.isLoading) {
        return;
      }

      _this.isLoading = true;

      _this.$parent.addClass('is-loading');


      $.get(_this.options.ajaxUrl + '/blog.php?page='+ (++posts.pages.current), function (data) {
        _this.$blogArticles.append(data);

        posts.remaining -= posts.pages.posts;
        _this.update();

        _this.$parent.removeClass("is-loading");

        _this.isLoading = false;

        // Callback call
        _this.options.onLoad();
      });
    });
  };

  global.BlogLoadNext = BlogLoadNext;


}(Ustwo);