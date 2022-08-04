planet = {

  init: function(){
    this.getFiles()
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

    let arr = [];

    $.each(data, function(k,v){
      arr.push({'date': k,'loc': v})
    });

    this.heatMapTimer(arr)
  },

  heatMapTimer: function(arr) {

    let num = arr.length;
    console.log(num)
    let loops = 0;
    let _this = this;

    let t = setInterval(function(){

      console.log(arr[loops])

      _this.updateTitle(arr[loops].date);
      _this.updateHeatMap(arr[loops].loc)

      loops++;
      console.log(loops)

      if(loops >= 78) {
        clearTimeout(t);
        console.log('end')
        $('#title').html('<h1>The End</h1><img src="https://media2.giphy.com/media/Kz420G0aGw5mU/giphy.gif?cid=ecf05e472r161n9u24q25sw6p70jn3o2nzlurwlbrsourff9&rid=giphy.gif&ct=g"/>')
      }
    },2000);

  },

  updateTitle: function(d) {
    $('#title').html('<h1>Date: ' + d + '</h1>')
  },

  updateHeatMap: function(loc) { 

    var heatmapData = [];

    $.each(loc, function(k, v){

      let geo = v.geo.split(',');
      let lat = parseFloat(geo[0]);
      let lng = parseFloat(geo[1])

      heatmapData.push(new google.maps.LatLng(lat, lng))
    });

    var heatmap = new google.maps.visualization.HeatmapLayer({
      data: heatmapData
    });

    heatmap.setMap(map);

  }

};