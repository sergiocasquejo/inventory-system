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



    $(function () {



        var payableList = $('form#payableList');

        payableList.on('change', 'select[name=branch].branch-filter', function() {
            var self = $(this);
            var $supField = payableList.find('select[name=supplier].supplier-filter');
            $supField.html('<option>Select Supplier</option>');

            console.log('test');

            loadImgLoader($supField);

            $.post(AJAX.baseUrl + '/admin/suppliers/list-by-branch', { branch: self.val() }).done(function(response) {

                if (response.length) {
                    $.each(response, function(index, data) {
                        var option = $('<option>').text(data.supplier_name).val(data.supplier_id);

                        if ($supField.data('selected') == data.supplier_id) {
                            option.attr('selected', true);
                        }

                        $supField.append(option);
                    });
                }
                always($supField);
            });
        });
        $('select[name=branch].branch-filter').trigger('change');

        /** Get credit information by customer id */
        $('#partialPaymentModal select[name=customer]').on('change', function() {
            var custId = $(this).val();
            if (!custId) return;

            $.get(AJAX.baseUrl + '/admin/credits/info-by-customer/'+ custId, function(response) {
                var total = amount = 0;
                console.log(response.data.total_credits);

               // if (response.data.length) {
                    total = response.data.total_credits;
                    amount = parseFloat(total);

               // }

                $('.total-credits').text(formatNumberStr(amount));
                $('input[name=amount]').val(total);
            });
        }).trigger('change');

        $('#partialPaymentModal select[name=supplier]').on('change', function() {
            var supplierId = $(this).val();
            if (!supplierId) return;

            $.get(AJAX.baseUrl + '/admin/credits/info-by-supplier/'+ supplierId, function(response) {
                var total = amount = 0;

                // if (response.data.length) {
                total = response.data.total_payables;
                amount = parseFloat(total);

                // }

                $('.total-payables').text(formatNumberStr(amount));
                $('input[name=amount]').val(total);
            });
        }).trigger('change');

        $('#payPayables').on('click', function(e) {
            e.preventDefault();
            $('.alert-message').addClass('hide').text('');
            var data = $('form#payablesPayForm').serialize();

            $.post(AJAX.baseUrl + '/admin/credits/payables-partial-payment', data).done(function(response) {

                if (response.error) {

                    $('.alert-message').removeClass('hide').addClass('alert-warning').text(response.error);
                } else if ( response.errors ) {

                    var msg = '';
                    $.each(response.errors, function(index, text) {
                        msg += text + '<br />';
                    });

                    $('.alert-message').removeClass('hide').addClass('alert-warning').html(msg);

                } else if (response.success) {
                    $('.alert-message').removeClass('hide alert-warning').addClass('alert-success').text(response.success);
                    $('form#payablesPayForm')[0].reset();
                }
            },'json').error(function(response) {
                $('.alert-message').removeClass('hide').addClass('alert-warning').text('Error: please check the form or reload the page.');
            });

            return false;
        });


        $('#pay').on('click', function(e) {
            e.preventDefault();
            $('.alert-message').addClass('hide').text('');
            var data = $('form#partialPayForm').serialize();

            $.post(AJAX.baseUrl + '/admin/credits/partial-payment', data).done(function(response) {

                if (response.error) {

                    $('.alert-message').removeClass('hide').addClass('alert-warning').text(response.error);
                } else if ( response.errors ) {

                    var msg = '';
                    $.each(response.errors, function(index, text) {
                        msg += text + '<br />';
                    });

                    $('.alert-message').removeClass('hide').addClass('alert-warning').html(msg);

                } else if (response.success) {
                    $('.alert-message').removeClass('hide alert-warning').addClass('alert-success').text(response.success);
                    $('form#partialPayForm')[0].reset();
                }
            },'json').error(function(response) {
                $('.alert-message').removeClass('hide').addClass('alert-warning').text('Error: please check the form or reload the page.');
            });

            return false;
        });




        var customers = [];


        $.get(AJAX.baseUrl + '/admin/customers/lists', function(response) {
           if (response.customers.length) {
               $.each(response.customers, function(index, data) {

                    var customer = {};

                   customer.id = data.customer_id;
                   customer.name = data.customer_name;
                   customer.address = data.address;
                   customer.contact_no = data.contact_no;

                   customers.push(customer);
               });

            }
        }, 'json');




        var $input = $('input[name=customer_name].typeahead');
        $input.typeahead({source:customers,
            autoSelect: false});
        $input.change(function() {
            var current = $input.typeahead("getActive");
            if (current) {
                // Some item from your model is active!
                if (current.name == $input.val()) {

                    $(':input[name=address]').val(current.address);
                    $(':input[name=contact_number]').val(current.contact_no);
                    $(':input[name=customer_id]').val(current.id);

                    // This means the exact match is found. Use toLowerCase() if you want case insensitive match.
                } else {
                    $(':input[name=customer_id]').val(0);
                    // This means it is only a partial match, you can either add a new item
                    // or take the active if you don't want new items
                }
            } else {
                // Nothing is active so it is a new value (or maybe empty value)
            }
        });



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
            totalAmntField = saleForm.find(':input[name=total_amount]'),
                totalAmountHolder = $('span.total_amount');


        qtyField.on('keyup', function() {

            var slctdProduct = saleForm.find(':input[name=product_id]').val();
            var slctdBranch = saleForm.find(':input[name=branch_id]').val();
            var slctdUOM = saleForm.find(':input[name=uom]').val();
            var quantity = $(this).val();

            loadImgLoader(saleForm.find(':input[name=total_amount]'));

            $.get(AJAX.baseUrl+'/admin/products/'+ slctdProduct +'/get', {branch_id: slctdBranch, uom:slctdUOM }, function(response) {
               // $('#total_error').remove();
                if (response.selling_price) {

                    saleForm.find(':input[name=total_amount]').val(response.selling_price * quantity);
                    totalAmountHolder.text(formatNumberStr(response.selling_price * quantity));

                }
                always(saleForm.find(':input[name=total_amount]'));
            });//.always(always(saleForm.find(':input[name=total_amount]')));

        }).trigger('keyup');


        productField.on('change', function() {
            var self = $(this);
            var slctdProduct = self.val();
            var slctdBranch = saleForm.find(':input[name=branch_id]').val();

            loadImgLoader(saleForm.find(':input[name=uom]'));

            saleForm.find(':input[name=total_amount]').val('');
            totalAmountHolder.text(formatNumberStr(0));
            salesDDUOM(slctdProduct, slctdBranch);



            uomField.trigger('change');

        }).trigger('change');


        // Trigger qty to change price
        uomField.on('change', function () {
            saleForm.find(':input[name=total_amount]').val('');
            totalAmountHolder.text(formatNumberStr(0));
            saleForm.find(':input[name=quantity]').trigger('keyup');
        })
        branchField.on('change', function() {
            saleForm.find(':input[name=product_id]').trigger('change');
            saleForm.find(':input[name=quantity]').trigger('keyup');

        });



        var stockForm = $('#stockForm'),
            productField = stockForm.find(':input[name=product_id]'),
            uomField = stockForm.find(':input[name=uom]'),
            supplierField = stockForm.find('select[name=supplier]'),
            branchField = stockForm.find('select[name=branch_id]');

        branchField.on('change', function() {
            var self = $(this);
            var $supField = stockForm.find('select[name=supplier]');
            $supField.html('<option>Select Supplier</option>');

            loadImgLoader($supField);

            $.post(AJAX.baseUrl + '/admin/suppliers/list-by-branch', { branch: self.val() }).done(function(response) {

                if (response.length) {
                    $.each(response, function(index, data) {
                        var option = $('<option>').text(data.supplier_name).val(data.supplier_id);
                        $supField.append(option);
                    });
                }
                always($supField);
            });
        }).trigger('change');

        supplierField.on('change', function() {
            var self = $(this);
            var productField = stockForm.find('select[name=product_id]');
            loadImgLoader(productField);

            $.post(AJAX.baseUrl+'/admin/products/suppliers-product', { supplier: self.val() }).done(function(response) {


                productField.html('');
                if (response.length) {
                    $.each(response, function(index, data){
                       var option = $('<option>').val(data.id).text(data.name);
                        productField.append(option);
                    });

                    always(productField);
                }
            });
        }).trigger('change');

        productField.on('change', function() {
            var self = $(this);
            var slctdProduct = self.val();

            if (!slctdProduct || slctdProduct == '') return;
            var slctUOM = stockForm.find(':input[name=uom]');

            loadImgLoader(stockForm.find(':input[name=uom]'));


            slctUOM.html('');
            slctUOM.html($('<option>').val('').text('Select Measure'));



            $.get(AJAX.baseUrl+'/admin/products/'+ slctdProduct +'/measures', function(response) {
                //$('span.alert-warning').remove();
                if (response.length) {
                    $.each(response, function(index, uom){
                        var opt = $('<option>').val(uom.name).text(uom.label);
                        if (uom.name == slctUOM.data('selected')) {
                            opt.attr('selected', 'selected');
                        }
                        slctUOM.append(opt);

                    });
                    always(stockForm.find(':input[name=uom]'));
                }
            });//.always(always(stockForm.find(':input[name=uom]')));

        });//.trigger('change');

        /**=========================================================================
         * Expense Form
         * @type {*|jQuery|HTMLElement}
         * =======================================================================*/
        var expenseForm = $('#expenseForm'),
            productField = expenseForm.find(':input[name=name]'),
            uomField = expenseForm.find(':input[name=uom]');
            qtyField = expenseForm.find(':input[name=quantity]'),
            totalAmntField = expenseForm.find(":input[name=total_amount]"),
            branchField = expenseForm.find('select[name=branch_id]'),
            supplierField = expenseForm.find('select[name=supplier]');

        branchField.on('change', function() {
            var self = $(this);
            var $supField = expenseForm.find('select[name=supplier]');
            $supField.html('<option>Select Supplier</option>');

            loadImgLoader($supField);

            $.post(AJAX.baseUrl + '/admin/suppliers/list-by-branch', { branch: self.val() }).done(function(response) {

                if (response.length) {
                    $.each(response, function(index, data) {
                        var option = $('<option>').text(data.supplier_name).val(data.supplier_id);
                        $supField.append(option);
                    });
                }

                removeImageLoader($supField, false);
            });
        }).trigger('change');

        supplierField.on('change', function() {
            var self = $(this);
            var productField = expenseForm.find('select[name=name]');
            loadImgLoader(productField);

            $.post(AJAX.baseUrl+'/admin/products/suppliers-product', { supplier: self.val() }).done(function(response) {


                productField.html('');
                if (response.length) {
                    $.each(response, function(index, data){
                        var option = $('<option>').val(data.id).text(data.name);
                        productField.append(option);
                    });

                    always(productField);

                    /**  Trigger change */
                    productField.trigger('change');
                }
            });
        });



        expenseForm.find('select[name=expense_type]').on('change', function() {
            loadImgLoader(expenseForm.find(':input[name=name]'));

            var val = $(this).val();
            var productSlctdValue = expenseForm.find(':input[name=name]').attr('data-selected');
            var productInput = '';
            if (val == 'PRODUCT EXPENSES') {
                supplierField.removeAttr('disabled');
                expenseForm.find(':input[name=is_payable]').removeAttr('disabled');
                expenseForm.find(':input[name=uom]').removeAttr('disabled');
                productInput = $('<select name="name" disabled class="form-control" data-selected="'+ productSlctdValue +'" required>');
                expenseForm.find(":input[name=total_amount]").attr('readonly', 'readonly').addClass('hidden');
                qtyField = expenseForm.find(':input[name=quantity]');
                qtyField.val(qtyField.data('selected')).removeAttr('readonly');

                totalAmountHolder.removeClass('hidden');
                var opt = $('<option>').val('').text("Select Product").attr('selected', 'selected');

                productInput.append(opt);

                //$.get(AJAX.baseUrl+'/admin/products/dropdown', function(response) {
                //
                //    if (response.length) {
                //        $.each(response, function(index, product){
                //            if (product.name != '') {
                //                var opt = $('<option>').val(product.id).text(product.name);
                //                if (product.id == productInput.data('selected')) {
                //                    opt.attr('selected', 'selected');
                //                    ddUOM(product.id);
                //                }
                //                productInput.append(opt);
                //            }
                //            removeImageLoader(expenseForm.find(':input[name=name]'));
                //        });
                //
                //    }
                //
                //});






            } else if (val == 'STORE EXPENSES') {
                supplierField.attr('disabled', true);
                expenseForm.find(':input[name=is_payable]').attr('checked', false).attr('disabled','disabled');
                expenseForm.find(':input[name=uom]').attr('disabled','disabled');
                expenseForm.find(":input[name=total_amount]").removeClass('hidden').removeAttr('readonly');
                expenseForm.find(':input[name=quantity]').attr('readonly', true);
                totalAmountHolder.addClass('hidden');

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

                        removeImageLoader(expenseForm.find(':input[name=name]'));
                    }

                });
            }


            /** Replace input to select type or vise versa and trigger change*/
            expenseForm.find(':input[name=name]').replaceWith(productInput).trigger('change');



        }).trigger('change');

        expenseForm.find(':input[name=uom]').on('change', function() {
            expenseForm.find(":input[name=total_amount]").val('');
            totalAmountHolder.text(formatNumberStr(parseFloat(totalAmountHolder.data('selected'))));
            expenseForm.find(':input[name=quantity]').trigger('keyup');
        });

        $('body').on('change', ':input[name=name]', function() {



            totalAmntField.val('');
            totalAmountHolder.text(formatNumberStr(parseFloat(totalAmountHolder.data('selected'))));
            if (expenseForm.find('select[name=expense_type]').val() == 'STORE EXPENSES') return;

            loadImgLoader(expenseForm.find(':input[name=uom]'));

            var self = $(this);
            var slctdProduct = self.val();

            if (!slctdProduct || slctdProduct == '' ||  expenseForm.find('select[name=expense_type]').val() == 'STORE EXPENSES') return;

            ddUOM(slctdProduct);

            expenseForm.find(':input[name=uom]').trigger('change');

        }).trigger('change');






        expenseForm.find(':input[name=quantity]').on('keyup', function() {
            var self  = $(this);


            loadImgLoader(expenseForm.find(":input[name=total_amount]"));

            var slctdProduct = expenseForm.find(':input[name=name]').val();
            var slctdBranch = expenseForm.find(':input[name=branch_id]').val();
            var slctdUOM = expenseForm.find(':input[name=uom]').val();
            var quantity = $(this).val();


            if (expenseForm.find('select[name=expense_type]').val() == 'STORE EXPENSES'
                || typeof slctdProduct == 'undefined' || !slctdProduct || slctdProduct == null || slctdUOM == 'Select Measure') {
                removeImageLoader(expenseForm.find(":input[name=total_amount]"));
                return;
            }

            totalAmntField.val('');
            totalAmountHolder.text(formatNumberStr(parseFloat(totalAmountHolder.data('selected'))));


            $.get(AJAX.baseUrl+'/admin/products/'+ slctdProduct +'/get', {branch_id: slctdBranch, uom:slctdUOM }, function(response) {
                if (response.selling_price && response.selling_price != 0) {
                    expenseForm.find(":input[name=total_amount]").val(response.supplier_price * quantity);
                    totalAmountHolder.text(formatNumberStr(response.supplier_price * quantity));
                }
                always(expenseForm.find(":input[name=total_amount]"));

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
            totalAmntField = creditForm.find(':input[name=total_amount]'),
            totalAmountHolder = $('span.total_amount');


        qtyField.on('keyup', function() {

            var slctdProduct = creditForm.find(':input[name=product_id]').val();
            var slctdBranch = creditForm.find(':input[name=branch_id]').val();
            var slctdUOM = creditForm.find(':input[name=uom]').val();
            var quantity = $(this).val();

            loadImgLoader(creditForm.find(':input[name=total_amount]'));
            /** Get selling price by product ID */
            $.get(AJAX.baseUrl+'/admin/products/'+ slctdProduct +'/get', {branch_id: slctdBranch, uom:slctdUOM }, function(response) {
                if (response.selling_price) {
                    totalAmntField.val(response.selling_price * quantity)
                    totalAmountHolder.text(formatNumberStr(response.selling_price * quantity));
                }
                always(creditForm.find(':input[name=total_amount]'));
            });//.always(always(creditForm.find(':input[name=total_amount]')));

        }).trigger('keyup');


        productField.on('change', function() {
            var self = $(this);
            var slctdProduct = self.val();
            var slctdBranch = creditForm.find(':input[name=branch_id]').val();

            var slctUOM = creditForm.find(':input[name=uom]');
            loadImgLoader(creditForm.find(':input[name=uom]'));


            totalAmntField.val('');
            totalAmountHolder.text(formatNumberStr(parseFloat(totalAmountHolder.data('selected'))));
            qtyField.trigger('keyup');

            slctUOM.html('');
            slctUOM.html($('<option>').val('').text('Select Measure'));

            $.get(AJAX.baseUrl+'/admin/products/'+ slctdProduct +'/uom', {branch_id: slctdBranch }, function(response) {
                if (response.length) {
                    $.each(response, function(index, uom){
                        var opt = $('<option>').val(uom.name).text(uom.label);
                        if (uom.name == slctUOM.data('selected')) {
                            opt.attr('selected', 'selected');
                        }
                        slctUOM.append(opt);
                    });

                }
                always(creditForm.find(':input[name=uom]'));
            });//.always(always(creditForm.find(':input[name=uom]')));



        }).trigger('change');


        // Trigger qty to change price
        uomField.on('change', function () {
            totalAmntField.val('');
            totalAmountHolder.text(formatNumberStr(0));
            creditForm.find(':input[name=quantity]').trigger('keyup');
        })
        branchField.on('change', function() {
            totalAmntField.val('');
            totalAmountHolder.text(formatNumberStr(0));
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
            $('.total_amount').text(formatNumberStr(total_amount));

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
                customer_id = trEl.find('td[data-contact_number]').data('customer_id'),
                hiddenInput = $('<input type="hidden" name="review_id">');
            $(':input[name=review_id]').remove();
            $(':input[name=branch_id]').val(branch).attr('data-selected', branch);
            $(':input[name=customer_name]').val(customer_name);
            $(':input[name=customer_id]').val(customer_id);
            $(':input[name=address]').val(address);
            $(':input[name=contact_number]').val(contact_number);
            $(':input[name=product_id]').val(product).attr('data-selected', product).trigger('change');
            $(':input[name=uom]').val(uom).attr('data-selected', uom);
            $(':input[name=quantity]').val(quantity);
            $(':input[name=total_amount]').val(total_amount);
            $(':input[name=comments]').val(comments);
            $(':input[name=date_of_sale]').val(date_of_sale);
            $(':input[name=action][value=review]').text('Update Review');
            $('.total_amount').text(formatNumberStr(total_amount));

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
                hiddenInput = $('<input type="hidden" name="review_id">')
            isPayable = trEl.find('td[data-is_payable]').data('is_payable'),
                supplier = trEl.find('td[data-supplier]').data('supplier');

            $(':input[name=review_id]').remove();
            $(':input[name=branch_id]').val(branch).attr('data-selected', branch);
            $(':input[name=expense_type]').val(expense_type).attr('data-selected', expense_type).trigger('change');
            $(':input[name=supplier] option[value='+ supplier +']').attr('selected', true).attr('data-selected', expense_type).trigger('change');
            $(':input[name=name]').val(name).attr('data-selected', name);//.trigger('change');
            $(':input[name=uom]').val(uom).attr('data-selected', uom);
            $(':input[name=quantity]').val(quantity);
            $(':input[name=total_amount]').val(total_amount);
            $(':input[name=comments]').val(comments);
            $(':input[name=date_of_sale]').val(date_of_sale);
            $(':input[name=action][value=review]').text('Update Review');
            $('.total_amount').text(formatNumberStr(total_amount));

            if (isPayable == 1) {
                $('input[name=is_payable]').attr('checked', true);
            } else {
                $('input[name=is_payable]').attr('checked', false);
            }


            var input = hiddenInput.val(_self.data('review-id'));

            $('#expenseForm').append(input);

            return false;
        });


    });

    /**
     * Add options to sales unit of measures
     * @param slctdProduct
     * @param slctdBranch
     */

    function salesDDUOM(slctdProduct, slctdBranch) {

        var slctUOM = $(':input[name=uom]');
        slctUOM.html('');
        slctUOM.html($('<option>').val('').text('Select Measure').attr('selected', 'selected'));

        $.get(AJAX.baseUrl+'/admin/products/'+ slctdProduct +'/uom', {branch_id: slctdBranch }, function(response) {
            //$('#total_error').remove();
            if (response.length) {
                $.each(response, function(index, uom){
                    var opt = $('<option>').val(uom.name).text(uom.label);
                    if (uom.name == slctUOM.data('selected')) {
                        opt.attr('selected', 'selected');
                    }
                    slctUOM.append(opt);
                });

            }
            always($(':input[name=uom]'));
        });//.always(always($(':input[name=uom]')));
    }


    /**
     * Add option to unit of measure element
     * @param slctdProduct
     */
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

            always($(':input[name=uom]'));

        });//.always(always($(':input[name=uom]')));

    }

    /**
     * Load image loader
     * @param el - Dom element
     */
    function loadImgLoader(el, addAttrDisabled) {

        if ( typeof addAttrDisabled == 'undefined' || addAttrDisabled == true ) {
            el.attr('disabled', true);
        }

        $(el).prevAll('.img-loader').remove();

        $('<img class="img-loader" src="'+ AJAX.baseUrl +'/assets/backend/img/ajax-loader.gif" />').insertBefore(el);
    }

    /**
     * Remove image loader
     * @param el
     * @param removeAttrDisabled
     */
    function removeImageLoader(el, removeAttrDisabled) {

        if ( typeof removeAttrDisabled == 'undefined' || removeAttrDisabled == true ) {
            el.removeAttr('disabled');
        }

        el.prevAll('.img-loader').remove();
    }


    function always(el) {
        removeImageLoader(el);
    }

    /**
     * Add comma to a number
     * @param nStr
     * @returns {*}
     */
    function formatNumberStr(number)
    {
        number = isNaN(number) ? 0 : number;

        nStr = number.toFixed(2).toString();
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }

        return 'Php '+ x1 + x2;
    }



}();