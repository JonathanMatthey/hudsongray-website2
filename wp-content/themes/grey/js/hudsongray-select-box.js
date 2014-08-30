!function (global) {
  var defaultOptions = {
    onSelect: function (selected) {}
  };

  function SelectBox ($el, options) {
    this.$el = $el;
    this.$label = $('span', this.$el);
    this.$ul = $('ul', this.$el);
    this.$lis = $('li', this.$ul);

    this.options = $.extend(true, {}, defaultOptions, options);

    this.bindEvents();
  }

  SelectBox.prototype.open = function open() {
    this.$el.addClass('open');
  };

  SelectBox.prototype.close = function close() {
    this.$el.removeClass('open');
  };

  SelectBox.prototype.toggle = function toggle() {
    this.$el.toggleClass('open');
  };

  SelectBox.prototype.select = function select(value) {
    $('li', this.$ul).removeClass('selected');

    var $selectedLi = $('li[data-selectbox-value=' + value + ']', this.$ul);
    $selectedLi.addClass('selected');

    this.$label.attr('data-selectbox-value', value);
    this.$label.text($selectedLi.text());

    this.options.onSelect(value);
  };

  SelectBox.prototype.bindEvents = function bindEvents() {
    var _this = this;
    this.$label.bind('mousedown touchstart', function (e) {
      e.preventDefault();
      
      _this.toggle();
    });

    this.$lis.bind('mousedown touchstart', function (e) {
      e.preventDefault();

      var value = $(this).data('selectbox-value');
      console.log(value);
      _this.select(value);
      _this.close();
    });
  };

  global.SelectBox = SelectBox;
}(Ustwo);