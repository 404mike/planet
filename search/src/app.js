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

    let _this = this;

    $('#results').html('');

    let count = res.length;

    if(count == 1) var resultsTerm = 'result';
    else var resultsTerm = 'results';

    $('#results').append('<p>' + count + ' ' + resultsTerm + '</p>');

    $.each(res, function(k,v){
      // console.log(v)

      let template = '<div class="item">';
        template += '<h2>' + v.title + '</h2>';
        template += '<p>Author: ' + _this.formatData(v.author) + '</p>';
        template += '<p>Date: ' + v.date + ' - Issue: ' + v.issue + '</p>';
        template += '<p>Location: ' + _this.formatData(v.location) + '</p>';
        template += '<p>Terms: ' + _this.formatData(v.keyword) + '</p>';
      
      template += '</div>';

      $('#results').append(template);

    })
  },

  formatData: function(data) {

    if(data == '' || data == null) {
      return '';
    }
   
    let parts = data.split(';');

    let str = '';
    parts.forEach(el => {
      str += '<span data-val="'+el+'">' + el + '</span> ';
    })

    return str;
  },

  updateSearch: function(val) {
    $('input#query').val(val);
    $('#submit').trigger('click');
    $("html, body").animate({ scrollTop: 0 }, "slow");
  }
}

$(document).ready(function(){

  planet.getFiles();

  $('#submit').click(function(){
    let q = $('#query').val();
    planet.search(q);
    return false;
  });

  $(document).on('click', 'span', function(){
    let val = $(this).data('val');
    planet.updateSearch(val)
  });

});