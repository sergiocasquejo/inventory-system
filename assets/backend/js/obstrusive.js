/*
<a href="posts/2" data-method="delete"> <---- We want to send an HTTP DELETE request

- Or, request confirmation in the process -

<a href="posts/2" data-method="delete" data-confirm="Are you sure?">
*/
 
(function() {
 
  var laravel = {
    initialize: function() {
      this.methodLinks  = $('a[data-method]');
      this.fetchLinks   = $('a[data-fetch]');
      this.registerEvents();
    },
    registerEvents: function() {
      this.methodLinks.on('click', this.handleMethod);
      this.fetchLinks.on('click', this.handleFetchMethod);
    },
    handleFetchMethod: function(e) {
      var link = $(this);
      console.log(link);
      var httpFetch = link.data('fetch').toUpperCase();
 
      // If the data-method attribute is not GET,
      // then we don't know what to do. Just ignore.
      if ( $.inArray(httpFetch, ['GET']) === - 1 ) {
        return;
      }

      // Call jqXHR
      var jqxhr = $.ajax(link.attr('href'))
        .done(function(response) {
          var form = $(':input[name=total_stocks]').closest('form');

          $(':input[name=total_stocks]').val(response.total_stocks);
          $(':input[name=branch_id]').val(response.branch_id);
          $(':input[name=uom]').val(response.uom);
          
          form.attr('method', 'POST')
            .attr('action', form.attr('action') + '/' + response.stock_on_hand_id);
          form.append('<input type="hidden" name="_method" value="PUT" />');

        });

      e.preventDefault();
    },
    handleMethod: function(e) {
      var link = $(this);
      var httpMethod = link.data('method').toUpperCase();
      var form;
 
      // If the data-method attribute is not PUT, RESTORE or DELETE,
      // then we don't know what to do. Just ignore.
      if ( $.inArray(httpMethod, ['PUT', 'DELETE', 'RESTORE']) === - 1 ) {
        return;
      }
 
      // Allow user to optionally provide data-confirm="Are you sure?"
      if ( link.data('confirm') ) {
        if ( ! laravel.verifyConfirm(link) ) {
          return false;
        }
      }
 
      form = laravel.createForm(link);
      form.submit();
 
      e.preventDefault();
    },
 
    verifyConfirm: function(link) {
      return confirm(link.data('confirm'));
    },
 
    createForm: function(link) {
      var form = 
      $('<form>', {
        'method': 'POST',
        'action': link.attr('href')
      });
 
      var token = 
      $('<input>', {
        'type': 'hidden',
        'name': 'csrf_token',
          'value': CSRF_TOKEN // hmmmm...
        });
 
      var hiddenInput =
      $('<input>', {
        'name': '_method',
        'type': 'hidden',
        'value': link.data('method') == 'RESTORE' ? 'POST' : link.data('method'),
      });
 
      return form.append(token, hiddenInput)
                 .appendTo('body');
    }
  };

 
  
 
  laravel.initialize();
})();