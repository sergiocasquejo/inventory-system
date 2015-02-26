/*
<a href="posts/2" data-method="delete"> <---- We want to send an HTTP DELETE request

- Or, request confirmation in the process -

<a href="posts/2" data-method="delete" data-confirm="Are you sure?">
*/
 
(function() {
 
  var laravel = {
    stockForm:'',
    priceForm:'',
    resetBtn:'',
    notices:'',
    initialize: function() {
      this.methodLinks  = $('a[data-method]');

      this.fetchLinks   = $('a[data-fetch]');

      this.priceForm    = $('#price-form');
      this.stockForm    = $('#stock-form');
      this.notices      = $('#notices');

      this.resetBtn    = $(':button[name=reset]');

      this.registerEvents();
    },
    registerEvents: function() {
      this.methodLinks.on('click', this.handleMethod);
      this.resetBtn.on('click', this.cancelForm);
      this.fetchLinks.on('click', this.handleFetchMethod);
      this.priceForm.on('submit', this.submitStockForm);
      this.stockForm.on('submit', this.submitStockForm);

    },
    cancelForm: function(e) {
      var btn = $(this);
      var form = btn.closest('form');
      console.log(form);

      laravel.resetForm(form);
    },
    submitStockForm: function(e) {

      var form = $(this),
          values = form.serialize()
          success = false;

      $.post(form.attr('action'), values, function(response) {
        var msg = '',
            msgClass = 'warning';

        if (response.errors) {

            msgClass = 'warning';
            msg += '<strong>Warning!</strong><ul>';

            $.each(response.errors, function(index, error) { msg += '<li>'+ error +'</li>'; });

            msg += '</ul>';
            
        } else if (response.success) {
          msgClass = 'success';
          msg = '<strong>Well done!</strong> ' + response.success;
          success = true;
          laravel.resetForm(form);
        }

        // Check if message is not empty
        if (msg != '') {

          var alert = '<div class="alert alert-'+ msgClass +' fade in">'+
                        '<button data-dismiss="alert" class="close close-sm" type="button">'+
                          '<i class="icon-remove"></i>'+
                        '</button>'+ msg + 
                      '</div>';

          laravel.notices.html(alert);

          //Reload if success
          if (success)
            setTimeout(function(){ window.location.reload(true); }, 1000);

        }

      }, 'json')

      e.preventDefault();

    },
    handleFetchMethod: function(e) {
      var link = $(this);
      
      var httpFetch = link.data('fetch').toUpperCase();
  
      // If the data-method attribute is not GET,
      // then we don't know what to do. Just ignore.
      if ( $.inArray(httpFetch, ['STOCK', 'PRICE']) === - 1 ) {
        return;
      }

      var form = $(link.data('form'));

      // Call jqXHR
      var jqxhr = $.ajax(link.attr('href'))
        .done(function(response) {
          laravel.populateForm(response, form, httpFetch);
          

        });

      e.preventDefault();
    },

    // Reset form back to normal state
    resetForm: function(form) {

      form.attr('action', form.data('action'));
      form.find(':input[name=_method]').remove();
      form.find(':input:not(:input[name=_token])').val('');
      form.find(':input[name=action]').text('Add');

    },
    populateForm: function(response, form, httpFetch) {

      // If the data-method attribute is not GET,
      // then we don't know what to do. Just ignore.
      if ( $.inArray(httpFetch, ['STOCK', 'PRICE']) === - 1 ) {
        return;
      }

      if (httpFetch == 'STOCK') {

        form.find(':input[name=total_stocks]').val(response.total_stocks);
        form.find(':input[name=branch_id]').val(response.branch_id);
        form.find(':input[name=uom]').val(response.uom);
        
        form.attr('method', 'POST')
          .attr('action', form.data('action') + '/' + response.stock_on_hand_id);

      } else if (httpFetch == 'PRICE') {
        console.log(form);
        form.find(':input[name=price]').val(response.price);
        form.find(':input[name=branch_id]').val(response.branch_id);
        form.find(':input[name=per_unit]').val(response.per_unit);
        
        form.attr('method', 'POST')
          .attr('action', form.data('action') + '/' + response.price_id);  
      }
      
      form.find(':input[name=action]').text('Update');

      if (!form.find(':input[name=_method]').length)
        form.append('<input type="hidden" name="_method" value="PUT" />');
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