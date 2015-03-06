(function() {
 
  var agrivate = {
    initialize: function() {
      this.methodLinks = $('a[data-fetch]');
 
      this.registerEvents();
    },
 
    registerEvents: function() {
      this.methodLinks.on('click', this.handleMethod);
    },

    handleMethod: function(e) {
      var link = $(this);
      
      var jqxhr = $.ajax(link.attr('href'))
        .done(function(response) {
          console.log(response);
        });

 
      e.preventDefault();
    },

  };
 
  agrivate.initialize();
 
})();