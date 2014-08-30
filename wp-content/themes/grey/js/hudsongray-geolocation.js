!function(global) {
  'use strict';

  function Geolocation(options) {
    this.options = options || {};
    this.getLocation().decision();
  }

  Geolocation.prototype.getLocation = function getLocation() {
    var _this = this;

    $.get("http://ipinfo.io", function(response) {
      _this.latitude = response.loc.split(",")[1];
      _this.country = response.country;
    }, "jsonp");

    return this;
  };

  Geolocation.prototype.decision = function decision() {
    if (this.longitude < -30 && this.longitude > -175) {
      this.moveStudioElements("nyc");
    }
    else if (this.country === "SE") {
      this.moveStudioElements("malmo");
    }
    else {
      this.moveStudioElements("london");
    }

    return this;
  };

  Geolocation.prototype.moveStudioElements = function moveStudioElements(studioLocation) {
    // Arrange Footers
    $('.footer__contact__item[data-studio="' + studioLocation +'"]').insertBefore($('.footer__contact__item[data-studio]:first'));

    // Arange studio page
    $('.studios__item[data-studio="' + studioLocation +'"]').insertBefore($('.studios__item[data-studio]:first'));

    // Show contact in sidebar
    $('.nav__menu__contact__list__item[data-studio="' + studioLocation +'"]').show();

    // Show contact in sidebar
    $('.studio[data-studio="' + studioLocation +'"]').insertBefore($('.studio[data-studio]:first'));

    $('.telephone[data-studio]').hide();
    $('.telephone[data-studio="' + studioLocation +'"]').show();
  };

  global.Geolocation = Geolocation;
}(Ustwo);

var Geolocation = new Ustwo.Geolocation();