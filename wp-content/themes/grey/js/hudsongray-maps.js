var studios = ['nyc', 'london', 'malmo'],
    maps = [];

var styles = {
  nyc: [{"stylers": [ {"hue" : "#FF5700" },{"saturation":0 }]},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":100},{"visibility":"simplified"}]}],
  london: [{"stylers": [ {"hue" : "#16D6D9" },{"saturation":0 }]},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":100},{"visibility":"simplified"}]}],
  malmo: [{"stylers": [ {"hue" : "#F62A81" },{"saturation":0 }]},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":100},{"visibility":"simplified"}]}]
};

var icons = {
  nyc: "../images/find-us/icn_pin_newyork.png",
  malmo: "../images/find-us/icn_pin_malmo.png",
  london: "../images/find-us/icn_pin_london.png",
}

var locations = {
  nyc: new google.maps.LatLng(40.747273,-73.995752),
  malmo: new google.maps.LatLng(55.600824, 13.001198),
  london: new google.maps.LatLng(51.523910, -0.076880)
};

var initialize = function() {
  for (var i in studios) {
    var current = studios[i];

    var options = {
      center: locations[current],
      styles: styles[current],
      zoom: 15,
      scrollwheel: false,
      mapTypeControl: false
    };

    maps[current] = new google.maps.Map(document.getElementById(current + "Map"), options);

    var marker = new google.maps.Marker({
      map: maps[current],
      animation: google.maps.Animation.DROP,
      position: locations[current],
      icon: new google.maps.MarkerImage(icons[current], null, null, null, new google.maps.Size(35,30))
    });
  }
}

google.maps.event.addDomListener(window, 'load', initialize);