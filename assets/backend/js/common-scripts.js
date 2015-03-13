var Script = function () {



//    sidebar dropdown menu

    jQuery('#sidebar .sub-menu > a').click(function () {
        var last = jQuery('.sub-menu.open', $('#sidebar'));
        last.removeClass("open");
        jQuery('.arrow', last).removeClass("open");
        jQuery('.sub', last).slideUp(200);
        var sub = jQuery(this).next();
        if (sub.is(":visible")) {
            jQuery('.arrow', jQuery(this)).removeClass("open");
            jQuery(this).parent().removeClass("open");
            sub.slideUp(200);
        } else {
            jQuery('.arrow', jQuery(this)).addClass("open");
            jQuery(this).parent().addClass("open");
            sub.slideDown(200);
        }
        var o = ($(this).offset());
        diff = 200 - o.top;
        if(diff>0)
            $("#sidebar").scrollTo("-="+Math.abs(diff),500);
        else
            $("#sidebar").scrollTo("+="+Math.abs(diff),500);
    });

//    sidebar toggle


    $(function() {
        function responsiveView() {
            var wSize = $(window).width();
            if (wSize <= 768) {
                $('#container').addClass('sidebar-close');
                $('#sidebar > ul').hide();
            }

            if (wSize > 768) {
                $('#container').removeClass('sidebar-close');
                $('#sidebar > ul').show();
            }
        }
        $(window).on('load', responsiveView);
        $(window).on('resize', responsiveView);
    });

    $('.icon-reorder').click(function () {
        if ($('#sidebar > ul').is(":visible") === true) {
            $('#main-content').css({
                'margin-left': '0px'
            });
            $('#sidebar').css({
                'margin-left': '-180px'
            });
            $('#sidebar > ul').hide();
            $("#container").addClass("sidebar-closed");
        } else {
            $('#main-content').css({
                'margin-left': '180px'
            });
            $('#sidebar > ul').show();
            $('#sidebar').css({
                'margin-left': '0'
            });
            $("#container").removeClass("sidebar-closed");
        }
    });

// custom scrollbar
    $("#sidebar").niceScroll({styler:"fb",cursorcolor:"#e8403f", cursorwidth: '3', cursorborderradius: '10px', background: '#404040', cursorborder: ''});

    $("html").niceScroll({styler:"fb",cursorcolor:"#e8403f", cursorwidth: '6', cursorborderradius: '10px', background: '#404040', cursorborder: '', zindex: '1000'});

// widget tools

    jQuery('.widget .tools .icon-chevron-down').click(function () {
        var el = jQuery(this).parents(".widget").children(".widget-body");
        if (jQuery(this).hasClass("icon-chevron-down")) {
            jQuery(this).removeClass("icon-chevron-down").addClass("icon-chevron-up");
            el.slideUp(200);
        } else {
            jQuery(this).removeClass("icon-chevron-up").addClass("icon-chevron-down");
            el.slideDown(200);
        }
    });

    jQuery('.widget .tools .icon-remove').click(function () {
        jQuery(this).parents(".widget").parent().remove();
    });

//    tool tips

    $('.tooltips').tooltip();

//    popovers

    $('.popovers').popover();



// custom bar chart

    if ($(".custom-bar-chart")) {
        $(".bar").each(function () {
            var i = $(this).find(".value").html();
            $(this).find(".value").html("");
            $(this).find(".value").animate({
                height: i
            }, 2000)
        })
    }


//custom select box

//    $(function(){
//
//        $('select.styled').customSelect();
//
//    });
    

    /**=================================================
     * AGRIVATE CUSTOM SCRIPTS
     *==================================================*/

    $(':input[name=brand_id]').on('change', function() {
        var brand = $(this),
            _id = brand.val(),
            data = [];
            data.push($('<option>').text('Select category').val(0));

        $.get(AJAX.baseUrl + '/admin/brands/'+ _id + '/categories', function(response) {
            $.each(response, function(index, value) {
                var option = $('<option>');
                option.text(value).val(index);
                if (index == $(':input[name=category_id]').data('selected')) {
                    option.attr('selected', 'selected');    
                }
                
                data.push(option)
            });

            $(':input[name=category_id]').html(data);

        });
    }).trigger('change');

    $(function () {
      $('[data-toggle="popover"]').popover()

      $('a[rel=popover-image]').popover({
          html: true,
          trigger:'focus',
          content: function (context) {
            return '<img style="width:100%;" src="'+$(this).data('image') + '" />';
          }
        });

        if ($('.datepicker').length) {
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd'
            });
        }

        var saleForm = $('#saleForm'),
            qtyField = saleForm.find(':input[name=quantity]'),
            productField = saleForm.find(':input[name=product_id]'),
            uomField = saleForm.find(':input[name=uom]'),
            branchField = saleForm.find(':input[name=branch_id]');


        qtyField.on('keyup', function() {

            var slctdProduct = saleForm.find(':input[name=product_id]').val();
            var slctdBranch = saleForm.find(':input[name=branch_id]').val();
            var slctdUOM = saleForm.find(':input[name=uom]').val();
            var quantity = $(this).val();


            $.get(AJAX.baseUrl+'/admin/products/'+ slctdProduct +'/get', {branch_id: slctdBranch, uom:slctdUOM }, function(response) {
                if (response.selling_price) {
                    saleForm.find(':input[name=total_amount]').val(response.selling_price * quantity)
                }  
            });
            
        }).trigger('keyup');


        productField.on('change', function() {
            var self = $(this);
            var slctdProduct = self.val();
            var slctdBranch = saleForm.find(':input[name=branch_id]').val();

            var slctUOM = saleForm.find(':input[name=uom]');
            
            slctUOM.html('');
            // slctUOM.html($('<option>').val('').text('Select Measure'));

            $.get(AJAX.baseUrl+'/admin/products/'+ slctdProduct +'/uom', {branch_id: slctdBranch }, function(response) {
                $('span.alert-warning').remove();
                if (response.length) {
                    $.each(response, function(index, uom){
                        var opt = $('<option>').val(uom.name).text(uom.label);
                        if (uom.name == slctUOM.data('selected')) {
                            opt.attr('selected', 'selected');
                        }
                        slctUOM.append(opt);
                    });
                    
                } else {
                    $("<span class='alert alert-warning'>")
                        .html('<a href="'+ AJAX.baseUrl +'/admin/products/'+ slctdProduct +'/edit">Click here</a> to setup pricing for this branch')
                        .insertAfter(self);
                }
            });

        }).trigger('change');


        // Trigger qty to change price
        uomField.on('change', function () {
            saleForm.find(':input[name=quantity]').trigger('keyup');
        })
        branchField.on('change', function() {
            saleForm.find(':input[name=product_id]').trigger('change');
            saleForm.find(':input[name=quantity]').trigger('keyup');

        });

        

        var stockForm = $('#stockForm'),
            productField = stockForm.find(':input[name=product_id]'),
            uomField = stockForm.find(':input[name=uom]');

        productField.on('change', function() {
                var self = $(this);
                var slctdProduct = self.val();

                if (!slctdProduct || slctdProduct == '') return;
                var slctUOM = stockForm.find(':input[name=uom]');
                
                slctUOM.html('');
                // slctUOM.html($('<option>').val('').text('Select Measure'));

                $.get(AJAX.baseUrl+'/admin/products/'+ slctdProduct +'/measures', function(response) {
                    $('span.alert-warning').remove();
                    if (response.length) {
                        $.each(response, function(index, uom){
                            var opt = $('<option>').val(uom.name).text(uom.label);
                            if (uom.name == slctUOM.data('selected')) {
                                opt.attr('selected', 'selected');
                            }
                            slctUOM.append(opt);
                        });
                        
                    } else {
                        $("<span class='alert alert-warning'>")
                            .html('<a href="'+ AJAX.baseUrl +'/admin/products/'+ slctdProduct +'/edit">Click here</a> to setup stocks product')
                            .insertAfter(self);
                    }
                });

            }).trigger('change');


         var expenseForm = $('#expenseForm'),
            productField = expenseForm.find(':input[name=name]'),
            uomField = expenseForm.find(':input[name=uom]');
            qtyField = expenseForm.find(':input[name=quantity]');
        expenseForm.find('select[name=expense_type]').on('change', function() {
            var val = $(this).val();
            var productSlctdValue = $(':input[name=name]').attr('data-selected');
            var productInput = '';
            if (val == 'PRODUCT EXPENSES') {
                expenseForm.find(':input[name=uom]').removeAttr('disabled');
                productInput = $('<select name="name" class="form-control" data-selected="'+ productSlctdValue +'" required>');
                expenseForm.find(":input[name=total_amount]").attr('readonly', 'readonly');

                $.get(AJAX.baseUrl+'/admin/products/dropdown', function(response) {

                    if (response.length) {
                        $.each(response, function(index, product){
                            if (product.name != '') {
                                var opt = $('<option>').val(product.id).text(product.name);
                                if (product.id == productInput.data('selected')) {
                                    opt.attr('selected', 'selected');
                                }
                                productInput.append(opt);
                            }
                        });
                        
                    } 
                });


            } else if (val == 'STORE EXPENSES') {
                expenseForm.find(':input[name=uom]').attr('disabled','disabled');
                expenseForm.find(':input[name=total_amount]').removeAttr('readonly');

                productInput = $('<input type="text" name="name" data-selected="'+ productSlctdValue +'" value="'+ productSlctdValue +'" class="form-control" required>');
                var slctUOM = expenseForm.find(':input[name=uom]');
                 slctUOM.html('<option>Select Measure</option>');
                $.get(AJAX.baseUrl+'/admin/uoms/dropdown', function(response) {

                    if (response.length) {
                        $.each(response, function(index, uom){
                            var opt = $('<option>').val(uom.name).text(uom.label);
                            if (uom.name == slctUOM.data('selected')) {
                                opt.attr('selected', 'selected');
                            }
                            slctUOM.append(opt);
                        });
                        
                    }
                });
            }

            $(':input[name=name]').replaceWith(productInput)
                .trigger('change');
        }).trigger('change');

        $('body').on('change', '#expenseForm :input[name=name]', function() {

            if (expenseForm.find('select[name=expense_type]').val() == 'STORE EXPENSES') return;

            console.log('test');
            var self = $(this);
            var slctdProduct = self.val();
            

            if (!slctdProduct && slctdProduct == '' &&  expenseForm.find('select[name=expense_type]').val() == 'PRODUCT EXPENSES') return;
            var slctUOM = expenseForm.find(':input[name=uom]');
            
          
            // slctUOM.html($('<option>').val('').text('Select Measure'));

            $.get(AJAX.baseUrl+'/admin/products/'+ slctdProduct +'/measures', function(response) {

                if (response.length) {
                      slctUOM.html('');
                    $.each(response, function(index, uom){
                        var opt = $('<option>').val(uom.name).text(uom.label);
                        if (uom.name == slctUOM.data('selected')) {
                            opt.attr('selected', 'selected');
                        }
                        slctUOM.append(opt);
                    });
                    
                }
            });
            qtyField.trigger('keyup');

        }).trigger('change');


        qtyField.on('keyup', function() {
            if (expenseForm.find('select[name=expense_type]').val() == 'STORE EXPENSES') return;
            
            var slctdProduct = expenseForm.find(':input[name=name]').val();
            var slctdBranch = expenseForm.find(':input[name=branch_id]').val();
            var slctdUOM = expenseForm.find(':input[name=uom]').val();
            var quantity = $(this).val();

            if (typeof slctdProduct == 'undefined' || !slctdProduct) return;

            $.get(AJAX.baseUrl+'/admin/products/'+ slctdProduct +'/get', {branch_id: slctdBranch, uom:slctdUOM }, function(response) {
                if (response.selling_price != 0) {
                    expenseForm.find(':input[name=total_amount]').val(response.selling_price * quantity)
                }  
            });
            
        }).trigger('keyup');

    });


    

}();