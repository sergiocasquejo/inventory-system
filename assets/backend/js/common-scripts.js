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
            branchField = saleForm.find(':input[name=branch_id]')
            totalAmntField = saleForm.find(':input[name=total_amount]');


        qtyField.on('keyup', function() {

            var slctdProduct = saleForm.find(':input[name=product_id]').val();
            var slctdBranch = saleForm.find(':input[name=branch_id]').val();
            var slctdUOM = saleForm.find(':input[name=uom]').val();
            var quantity = $(this).val();


            $.get(AJAX.baseUrl+'/admin/products/'+ slctdProduct +'/get', {branch_id: slctdBranch, uom:slctdUOM }, function(response) {
                $('#total_error').remove();
                if (response.selling_price) {

                    saleForm.find(':input[name=total_amount]').val(response.selling_price * quantity)
                } else {
                    $("<span id='total_error' class='label label-warning label-mini mr-10px'>")
                        .html('<a href="'+ AJAX.baseUrl +'/admin/products/'+ slctdProduct +'/edit">Click here</a> to setup pricing for this branch')
                        .insertAfter(saleForm.find(':input[name=total_amount]'));
                }
            });

        }).trigger('keyup');


        productField.on('change', function() {
            var self = $(this);
            var slctdProduct = self.val();
            var slctdBranch = saleForm.find(':input[name=branch_id]').val();


            saleForm.find(':input[name=total_amount]').val('');
            salesDDUOM(slctdProduct, slctdBranch);



            uomField.trigger('change');

        }).trigger('change');


        // Trigger qty to change price
        uomField.on('change', function () {
            saleForm.find(':input[name=total_amount]').val('');
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
            qtyField = expenseForm.find(':input[name=quantity]'),
            totalAmntField = expenseForm.find(":input[name=total_amount]");

        expenseForm.find('select[name=expense_type]').on('change', function() {
            var val = $(this).val();
            var productSlctdValue = $(':input[name=name]').attr('data-selected');
            var productInput = '';
            if (val == 'PRODUCT EXPENSES') {
                expenseForm.find(':input[name=uom]').removeAttr('disabled');
                productInput = $('<select name="name" class="form-control" data-selected="'+ productSlctdValue +'" required>');
                expenseForm.find(":input[name=total_amount]").attr('readonly', 'readonly');

                var opt = $('<option>').val('').text("Select Product").attr('selected', 'selected');

                productInput.append(opt);

                $.get(AJAX.baseUrl+'/admin/products/dropdown', function(response) {

                    if (response.length) {
                        $.each(response, function(index, product){
                            if (product.name != '') {
                                var opt = $('<option>').val(product.id).text(product.name);
                                if (product.id == productInput.data('selected')) {
                                    opt.attr('selected', 'selected');
                                    ddUOM(product.id);
                                }
                                productInput.append(opt);
                            }
                        });

                    }
                });


            } else if (val == 'STORE EXPENSES') {
                expenseForm.find(':input[name=uom]').attr('disabled','disabled');
                totalAmntField.removeAttr('readonly');

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

            expenseForm.find(':input[name=name]').replaceWith(productInput);
            expenseForm.find(':input[name=name]').trigger('change');

            expenseForm.find(':input[name=uom]').trigger('change');


        }).trigger('change');

        expenseForm.find(':input[name=uom]').on('change', function() {
            expenseForm.find(":input[name=total_amount]").val('');
            expenseForm.find(':input[name=quantity]').trigger('keyup');
        });

        $('body').on('change', ':input[name=name]', function() {

            totalAmntField.val('');

            if (expenseForm.find('select[name=expense_type]').val() == 'STORE EXPENSES') return;


            var self = $(this);
            var slctdProduct = self.val();




            if (!slctdProduct || slctdProduct == '' ||  expenseForm.find('select[name=expense_type]').val() == 'STORE EXPENSES') return;

            ddUOM(slctdProduct);

            expenseForm.find(':input[name=uom]').trigger('change');

        }).trigger('change');






        expenseForm.find(':input[name=quantity]').on('keyup', function() {
            totalAmntField.val('');
            if (expenseForm.find('select[name=expense_type]').val() == 'STORE EXPENSES') return;


            var slctdProduct = expenseForm.find(':input[name=name]').val();
            var slctdBranch = expenseForm.find(':input[name=branch_id]').val();
            var slctdUOM = expenseForm.find(':input[name=uom]').val();
            var quantity = $(this).val();

            if (typeof slctdProduct == 'undefined' || !slctdProduct || slctdProduct == null || slctdUOM == 'Select Measure') return;

            $.get(AJAX.baseUrl+'/admin/products/'+ slctdProduct +'/get', {branch_id: slctdBranch, uom:slctdUOM }, function(response) {
                $('#total_error').remove();
                if (response.selling_price && response.selling_price != 0) {
                    expenseForm.find(":input[name=total_amount]").val(response.selling_price * quantity);
                } else {
                    $("<span id='total_error' class='label label-warning label-mini mr-10px'>")
                        .html('<a href="'+ AJAX.baseUrl +'/admin/products/'+ slctdProduct +'/edit">Click here</a> to setup pricing for this product')
                        .insertAfter(expenseForm.find(":input[name=total_amount]"));
                }
            });

        }).trigger('keyup');



        /**====================================================
         * CREDITS
         *====================================================*/

        var creditForm = $('#creditForm'),
            qtyField = creditForm.find(':input[name=quantity]'),
            productField = creditForm.find(':input[name=product_id]'),
            uomField = creditForm.find(':input[name=uom]'),
            branchField = creditForm.find(':input[name=branch_id]'),
            totalAmntField = creditForm.find(':input[name=total_amount]');


        qtyField.on('keyup', function() {

            var slctdProduct = creditForm.find(':input[name=product_id]').val();
            var slctdBranch = creditForm.find(':input[name=branch_id]').val();
            var slctdUOM = creditForm.find(':input[name=uom]').val();
            var quantity = $(this).val();


            $.get(AJAX.baseUrl+'/admin/products/'+ slctdProduct +'/get', {branch_id: slctdBranch, uom:slctdUOM }, function(response) {
                if (response.selling_price) {
                    totalAmntField.val(response.selling_price * quantity)
                }
            });

        }).trigger('keyup');


        productField.on('change', function() {
            var self = $(this);
            var slctdProduct = self.val();
            var slctdBranch = creditForm.find(':input[name=branch_id]').val();

            var slctUOM = creditForm.find(':input[name=uom]');

            totalAmntField.val('');

            qtyField.trigger('keyup');

            slctUOM.html('');
            slctUOM.html($('<option>').val('').text('Select Measure'));

            $.get(AJAX.baseUrl+'/admin/products/'+ slctdProduct +'/uom', {branch_id: slctdBranch }, function(response) {
                $('#total_error').remove();
                if (response.length) {
                    $.each(response, function(index, uom){
                        var opt = $('<option>').val(uom.name).text(uom.label);
                        if (uom.name == slctUOM.data('selected')) {
                            opt.attr('selected', 'selected');
                        }
                        slctUOM.append(opt);
                    });

                } else {
                    $("<span id='total_error' class='label label-warning label-mini mr-10px'>")
                        .html('<a href="'+ AJAX.baseUrl +'/admin/products/'+ slctdProduct +'/edit">Click here</a> to setup pricing for this branch')
                        .insertAfter(self);
                }
            });



        }).trigger('change');


        // Trigger qty to change price
        uomField.on('change', function () {
            totalAmntField.val('');
            creditForm.find(':input[name=quantity]').trigger('keyup');
        })
        branchField.on('change', function() {
            totalAmntField.val('');
            creditForm.find(':input[name=product_id]').trigger('change');
            creditForm.find(':input[name=quantity]').trigger('keyup');

        });


        $(".edit-sales-review").on('click', function(e) {
            e.preventDefault();
            var _self = $(this),
                trEl = _self.closest('tr'),
                branch = trEl.find('td[data-branch]').data('branch'),
                product = trEl.find('td[data-product]').data('product'),
                uom = trEl.find('td[data-uom]').data('uom')
            quantity = trEl.find('td[data-quantity]').data('quantity')
            total_amount = trEl.find('td[data-total_amount]').data('total_amount')
            comments = trEl.find('td[data-comments]').data('comments')
            date_of_sale = trEl.find('td[data-date_of_sale]').data('date_of_sale'),
                hiddenInput = $('<input type="hidden" name="review_id">');
            $(':input[name=review_id]').remove();
            $(':input[name=branch_id]').val(branch).attr('data-selected', branch);
            $(':input[name=product_id]').val(product).attr('data-selected', product).trigger('change');
            $(':input[name=uom]').val(uom).attr('data-selected', uom);
            $(':input[name=quantity]').val(quantity);
            $(':input[name=total_amount]').val(total_amount);
            $(':input[name=comments]').val(comments);
            $(':input[name=date_of_sale]').val(date_of_sale);
            $(':input[name=action][value=review]').text('Update Review');

            var input = hiddenInput.val(_self.data('review-id'));

            $('#saleForm').append(input);

            return false;
        });


        $(".edit-credits-review").on('click', function(e) {
            e.preventDefault();
            var _self = $(this),
                trEl = _self.closest('tr'),
                branch = trEl.find('td[data-branch]').data('branch'),
                product = trEl.find('td[data-product]').data('product'),
                uom = trEl.find('td[data-uom]').data('uom')
            quantity = trEl.find('td[data-quantity]').data('quantity')
            total_amount = trEl.find('td[data-total_amount]').data('total_amount')
            comments = trEl.find('td[data-comments]').data('comments')
            date_of_sale = trEl.find('td[data-date_of_sale]').data('date_of_sale'),
                address = trEl.find('td[data-address]').data('address'),
                customer_name = trEl.find('td[data-customer_name]').data('customer_name'),
                contact_number = trEl.find('td[data-contact_number]').data('contact_number'),
                hiddenInput = $('<input type="hidden" name="review_id">');
            $(':input[name=review_id]').remove();
            $(':input[name=branch_id]').val(branch).attr('data-selected', branch);
            $(':input[name=customer_name]').val(customer_name);
            $(':input[name=address]').val(address);
            $(':input[name=contact_number]').val(contact_number);
            $(':input[name=product_id]').val(product).attr('data-selected', product).trigger('change');
            $(':input[name=uom]').val(uom).attr('data-selected', uom);
            $(':input[name=quantity]').val(quantity);
            $(':input[name=total_amount]').val(total_amount);
            $(':input[name=comments]').val(comments);
            $(':input[name=date_of_sale]').val(date_of_sale);
            $(':input[name=action][value=review]').text('Update Review');

            var input = hiddenInput.val(_self.data('review-id'));

            $('#creditForm').append(input);

            return false;
        });


        $(".edit-expense-review").on('click', function(e) {
            e.preventDefault();
            var _self = $(this),
                trEl = _self.closest('tr'),
                branch = trEl.find('td[data-branch]').data('branch'),
                name = trEl.find('td[data-name]').data('name'),
                expense_type = trEl.find('td[data-expense_type]').data('expense_type');
            uom = trEl.find('td[data-uom]').data('uom')
            quantity = trEl.find('td[data-quantity]').data('quantity')
            total_amount = trEl.find('td[data-total_amount]').data('total_amount')
            comments = trEl.find('td[data-comments]').data('comments')
            date_of_sale = trEl.find('td[data-date_of_sale]').data('date_of_sale'),
                hiddenInput = $('<input type="hidden" name="review_id">');
            $(':input[name=review_id]').remove();
            $(':input[name=branch_id]').val(branch).attr('data-selected', branch);
            $(':input[name=expense_type]').val(expense_type).attr('data-selected', expense_type).trigger('change');
            $(':input[name=name]').val(name).attr('data-selected', name);//.trigger('change');
            $(':input[name=uom]').val(uom).attr('data-selected', uom);
            $(':input[name=quantity]').val(quantity);
            $(':input[name=total_amount]').val(total_amount);
            $(':input[name=comments]').val(comments);
            $(':input[name=date_of_sale]').val(date_of_sale);
            $(':input[name=action][value=review]').text('Update Review');

            var input = hiddenInput.val(_self.data('review-id'));

            $('#expenseForm').append(input);

            return false;
        });


    });


    function salesDDUOM(slctdProduct, slctdBranch) {

        var slctUOM = $(':input[name=uom]');
        slctUOM.html('');
        slctUOM.html($('<option>').val('').text('Select Measure').attr('selected', 'selected'));

        $.get(AJAX.baseUrl+'/admin/products/'+ slctdProduct +'/uom', {branch_id: slctdBranch }, function(response) {
            $('#total_error').remove();
            if (response.length) {
                $.each(response, function(index, uom){
                    var opt = $('<option>').val(uom.name).text(uom.label);
                    if (uom.name == slctUOM.data('selected')) {
                        opt.attr('selected', 'selected');
                    }
                    slctUOM.append(opt);
                });

            } else {
                $("<span id='total_error' class='label label-warning label-mini mr-10px'>")
                    .html('<a href="'+ AJAX.baseUrl +'/admin/products/'+ slctdProduct +'/edit">Click here</a> to setup pricing for this branch')
                    .insertAfter($(":input[name=total_amount]"));
            }
        });
    }
    function ddUOM(slctdProduct) {
        var slctUOM = $(':input[name=uom]');


        slctUOM.html($('<option>').val('').text('Select Measure'));

        $.get(AJAX.baseUrl+'/admin/products/'+ slctdProduct +'/measures', function(response) {

            if (response.length) {
                slctUOM.html('');
                var opt = $('<option>').val('').text('Select Measure').attr('selected', 'selected');
                slctUOM.append(opt);
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



}();