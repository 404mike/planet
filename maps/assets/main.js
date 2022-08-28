let dates = [];
let arr = []; 
let heatmap;

planet = {

  init: function(){
    this.updateMapButtonEvent();
    this.createHeatMapInstance();
    this.getFiles();
  },

  getFiles: function(){
    let _this = this;
    return fetch('generate_data/planet_locations.json')
      .then((response) => { 
          return response.json().then((data) => {
            _this.parseData(data)
          }).catch((err) => {
              console.log(err);
          }) 
      });
  },

  parseData: function(data) {
    $.each(data, function(k,v){
      arr.push({'date': k,'loc': v})
      dates.push(parseInt(k))
    });

    this.setSliders();
  },

  createHeatMapInstance: function() {
    heatmap = new google.maps.visualization.HeatmapLayer({
      data: []
    });
  },

  setGradient: function() {
    const gradient = [
      "rgba(0, 255, 255, 0)",
      "rgba(0, 255, 255, 1)",
      "rgba(0, 191, 255, 1)",
      "rgba(0, 127, 255, 1)",
      "rgba(0, 63, 255, 1)",
      "rgba(0, 0, 255, 1)",
      "rgba(0, 0, 223, 1)",
      "rgba(0, 0, 191, 1)",
      "rgba(0, 0, 159, 1)",
      "rgba(0, 0, 127, 1)",
      "rgba(63, 0, 91, 1)",
      "rgba(127, 0, 63, 1)",
      "rgba(191, 0, 31, 1)",
      "rgba(255, 0, 0, 1)",
    ];
  
    heatmap.set("gradient", gradient);
  },

  updateHeatMap: function(heatmapData) { 

    heatmap.setMap(null);
  
    heatmap = new google.maps.visualization.HeatmapLayer({
      data: heatmapData
    });

    this.setGradient();
    heatmap.setMap(map);

  },

  setSliders: function() {
    var valuesSlider = document.getElementById('slider');
    var valuesForSlider = dates;
    
    var format = {
      to: function(value) {
          return valuesForSlider[Math.round(value)];
      },
      from: function (value) {
          return valuesForSlider.indexOf(Number(value));
      }
    };
    
    noUiSlider.create(valuesSlider, {
        start: [dates[0],dates[1]],
        // A linear range from 0 to 15 (16 values)
        range: { min: 0, max: valuesForSlider.length - 1 },
        // steps of 1
        step: 1,
        tooltips: true,
        format: format,
        pips: { mode: 'steps', format: format,density: 50 },
    });
  },

  updateMapButtonEvent: function() {
    let _this = this
    $('button#update_map').click(function(){
      let val = slider.noUiSlider.get(true);
      _this.parseDateRangeData(val)
    })
  },

  parseDateRangeData: function(range){
    s = range[0]
    f = range[1]
    let new_range = arr.slice(s, f + 1)
    this.parseNewDateRangeData(new_range);
  },

  getKeyByValue: function(object, value) {
    return Object.keys(object).find(key => object[key] === value);
  },

  parseNewDateRangeData: function(data) {

    let _this = this;
    let heatmapData = [];
    let topPlaces = {};

    data.forEach(function(date){

      date['loc'].forEach(function(k){
                
        heatmapData.push(_this.createGoogleMapsLocation(k.geo))

        if(k.place in topPlaces) {
          topPlaces[k.place] += 1;
        }else{
          topPlaces[k.place] = 1;
        }

      })

    })

    this.analyseDataRange(topPlaces);
    this.updateHeatMap(heatmapData)
  },

  createGoogleMapsLocation: function(geo) {
      let g = geo.split(',');
      let lat = parseFloat(g[0]);
      let lng = parseFloat(g[1])
      // console.log(lat, lng)
      return new google.maps.LatLng(lat, lng)
  },

  analyseDataRange: function(topPlaces) {

    $('#info').html('');

    var r = Object.entries(topPlaces).sort((a,b) => b[1]-a[1]);

    r.forEach(function(k){
      $('#info').append('<p>' + k[0] + ' - ' + k[1] + '</p>');
    })

  }

};