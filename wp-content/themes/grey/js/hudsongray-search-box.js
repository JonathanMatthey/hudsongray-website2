!function (global) {
  function SearchBox ($el) {
    this.$el = $el;
    this.$input = $('input', this.$el);

    this.isOpenable = true;

    this.bindEvents();
  };

  SearchBox.prototype.setOpenable = function setOpenable(value) {
    if (typeof value === 'undefined') {
      value = false;
    }

    this.isOpenable = value;

    if (!value) {
      this.$el.parent().removeClass('is-open');
    }
  };

  SearchBox.prototype.bindEvents = function bindEvents() {
    var _this = this;

    this.$input.bind('focus', function () {
      if (_this.isOpenable) {
        _this.$el.parent().addClass('is-open');
      }
    });

    this.$input.bind('blur', function () {
      _this.$el.parent().removeClass('is-open');
    });
  };

  global.SearchBox = SearchBox
}(Ustwo);