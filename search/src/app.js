var miniSearch;
let planet = {

  getFiles: function(){
    let _this = this;
    return fetch('csv/planet.json')
        .then((response) => { 
            return response.json().then((data) => {
              _this.initMiniSearch(data)
            }).catch((err) => {
                console.log(err);
            }) 
        });
  },

  initMiniSearch: function(data){
    miniSearch = new MiniSearch({
      fields: ['title', 'author','location','keyword','date'], // fields to index for full-text search
      storeFields: ['title', 'author','location','keyword','date','issue'] // fields to return with search results
    })

    // Index all documents
    miniSearch.addAll(data);
  },

  search: function(query){
    let results = miniSearch.search(query)
    this.renderOutput(results)
  },

  renderOutput: function(res){

    $('#results').html('');

    let count = res.length;

    if(count == 1) var resultsTerm = 'result';
    else var resultsTerm = 'results';

    $('#results').append('<p>' + count + ' ' + resultsTerm + '</p>');

    $.each(res, function(k,v){
      // console.log(v)

      

      let template = '<div class="item">';
        template += '<h2>' + v.title + '</h2>';
        template += '<p>Author: ' + v.author + '</p>';
        template += '<p>Date: ' + v.date + ' - Issue: ' + v.issue + '</p>';
        template += '<p>Location: ' + v.location + '</p>';
        template += '<p>Terms: ' + v.terms + '</p>';
      
      template += '</div>';

      $('#results').append(template);

    })
  }
}

$(document).ready(function(){

  planet.getFiles();

  $('#submit').click(function(){
    let q = $('#query').val();
    planet.search(q);
    return false;
  });

});