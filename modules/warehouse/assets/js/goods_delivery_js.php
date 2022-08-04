<script>
  var purchase;
  var warehouses;
  var lastAddedItemKey = null;
(function($) {
"use strict";  
 
  appValidateForm($('#add_goods_delivery'), {
     date_c: 'required',
     date_add: 'required',

     <?php  if($pr_orders_status == true && get_warehouse_option('goods_delivery_required_po') == 1){  ?>
      pr_order_id: 'required',
     <?php } ?>
    
   });

   // Maybe items ajax search
    init_ajax_search('items','#item_select.ajax-search',undefined,admin_url+'warehouse/wh_commodity_code_search/rate');

    wh_calculate_total(); 


 })(jQuery);



    //version2
(function($) {
  "use strict";

// Add item to preview from the dropdown for invoices estimates
$("body").on('change', 'select[name="item_select"]', function () {
  var itemid = $(this).selectpicker('val');
  if (itemid != '') {
    wh_add_item_to_preview(itemid);
  }
});

// Recaulciate total on these changes
$("body").on('change', 'select.taxes', function () {
  wh_calculate_total();
});

$("body").on('click', '.add_goods_delivery', function () {
  submit_form(false);
});

$('.add_goods_delivery_send').on('click', function() {
  submit_form(true);
});


$('select[name="warehouse_id"]').on('change', function() {
  "use strict"; 

  var data = {};
  data.commodity_id = $('.main input[name="commodity_code"]').val();
  data.warehouse_id = $('.main select[name="warehouse_id"]').val();
  var quantities = $('.main input[name="quantities"]').val();

  if(data.commodity_id != '' && data.warehouse_id != ''){
    $.post(admin_url + 'warehouse/get_quantity_inventory', data).done(function(response){
      response = JSON.parse(response);
      $('.main input[name="available_quantity"]').val(response.value);
      if(parseFloat(response.value) < parseFloat(quantities)){
      }
    });
  }else{
    $('.main input[name="available_quantity"]').val(0);
    alert_float('warning', '<?php echo _l('inventory_quantity_is_not_enough') ?>');
  }

});

$('input[name="quantities"]').on('change', function() {
  "use strict"; 

  var available_quantity = $('.main input[name="available_quantity"]').val();
  var quantities = $('.main input[name="quantities"]').val();
  if(parseFloat(available_quantity) < parseFloat(quantities)){
    alert_float('warning', '<?php echo _l('inventory_quantity_is_not_enough') ?>');
    $('.main input[name="quantities"]').val(available_quantity);

  }
});

})(jQuery);

function check_quantity_inventory(commodity_id, quantity, warehouse_id, switch_barcode_scanners = false) {
  // body...
  data.commodity_id = commodity_id;
  data.quantity = quantity;
  data.switch_barcode_scanners = switch_barcode_scanners;
  data.warehouse_id = warehouse_id;

  if(commodity_id != '' && warehouse_id != '' ){
    $.post(admin_url + 'warehouse/check_quantity_inventory', data).done(function(response){
      response = JSON.parse(response);

      purchase.setDataAtCell(row,2,response.value);

    });
  }
}
            
// Add item to preview
function wh_add_item_to_preview(id) {
  "use strict";

  requestGetJSON('warehouse/get_item_by_id/' + id +'/'+true).done(function (response) {
    clear_item_preview_values();

    $('.main input[name="commodity_code"]').val(response.itemid);
    $('.main textarea[name="commodity_name"]').val(response.code_description);
    $('.main input[name="unit_price"]').val(response.rate);
    $('.main input[name="unit_name"]').val(response.unit_name);
    $('.main input[name="unit_id"]').val(response.unit_id);
    $('.main input[name="quantities"]').val(1);
    $('.main select[name="warehouse_id"]').html(response.warehouses_html);
    $('.main input[name="guarantee_period"]').val(response.guarantee_new);
    $('.selectpicker').selectpicker('refresh');
    // if($('select[name="warehouse_id"]').val() != ''){
    //   $('.main select[name="warehouse_id"]').val($('select[name="warehouse_id"]').val());
    //   init_selectpicker();
    //   $('.selectpicker').selectpicker('refresh');
    // }

    var taxSelectedArray = [];
    if (response.taxname && response.taxrate) {
      taxSelectedArray.push(response.taxname + '|' + response.taxrate);
    }
    if (response.taxname_2 && response.taxrate_2) {
      taxSelectedArray.push(response.taxname_2 + '|' + response.taxrate_2);
    }

    $('.main select.taxes').selectpicker('val', taxSelectedArray);
    $('.main input[name="unit"]').val(response.unit_name);

    var $currency = $("body").find('.accounting-template select[name="currency"]');
    var baseCurency = $currency.attr('data-base');
    var selectedCurrency = $currency.find('option:selected').val();
    var $rateInputPreview = $('.main input[name="rate"]');

    if (baseCurency == selectedCurrency) {
      $rateInputPreview.val(response.rate);
    } else {
      var itemCurrencyRate = response['rate_currency_' + selectedCurrency];
      if (!itemCurrencyRate || parseFloat(itemCurrencyRate) === 0) {
        $rateInputPreview.val(response.rate);
      } else {
        $rateInputPreview.val(itemCurrencyRate);
      }
    }

    $(document).trigger({
      type: "item-added-to-preview",
      item: response,
      item_type: 'item',
    });
  });
}

function wh_add_item_to_table(data, itemid) {
  "use strict";

  data = typeof (data) == 'undefined' || data == 'undefined' ? wh_get_item_preview_values() : data;

  if (data.warehouse_id == "" ||  data.available_quantity == "" || data.quantities == "" || data.commodity_code == "" ) {
    if(data.warehouse_id == ""){
      alert_float('warning', '<?php echo _l('please_select_a_warehouse') ?>');
    }
    if(parseFloat(data.available_quantity) < parseFloat(data.quantities)){
      //check_available_quantity
      alert_float('warning', '<?php echo _l('Inventory quantity is not enough') ?>');
    }

    return;
  }
  var table_row = '';
  var item_key = lastAddedItemKey ? lastAddedItemKey += 1 : $("body").find('.invoice-items-table tbody .item').length + 1;
  lastAddedItemKey = item_key;
  $("body").append('<div class="dt-loader"></div>');
  wh_get_item_row_template('newitems[' + item_key + ']',data.commodity_name,data.warehouse_id, data.available_quantity, data.quantities, data.unit_name,data.unit_price, data.taxname, data.lot_number, data.expiry_date, data.commodity_code, data.unit_id, data.tax_rate, data.discount, data.note, data.guarantee_period, itemid).done(function(output){
    table_row += output;

    $('.invoice-item table.invoice-items-table.items tbody').append(table_row);

    setTimeout(function () {
      wh_calculate_total();
    }, 15);
    init_selectpicker();
    init_datepicker();
    wh_reorder_items('.invoice-item');
    wh_clear_item_preview_values('.invoice-item');
    $('body').find('#items-warning').remove();
    $("body").find('.dt-loader').remove();
        $('#item_select').selectpicker('val', '');

    return true;
  });
  return false;
}

function wh_get_item_preview_values() {
  "use strict";

  var response = {};
  response.commodity_name = $('.invoice-item .main textarea[name="commodity_name"]').val();
  response.warehouse_id = $('.invoice-item .main select[name="warehouse_id"]').val();
  response.available_quantity = $('.invoice-item .main input[name="available_quantity"]').val();
  response.quantities = $('.invoice-item .main input[name="quantities"]').val();
  response.unit_name = $('.invoice-item .main input[name="unit_name"]').val();
  response.unit_price = $('.invoice-item .main input[name="unit_price"]').val();
  response.taxname = $('.main select.taxes').selectpicker('val');
  response.lot_number = '';
  response.expiry_date = '';
  response.commodity_code = $('.invoice-item .main input[name="commodity_code"]').val();
  response.unit_id = $('.invoice-item .main input[name="unit_id"]').val();
  response.tax_rate = $('.invoice-item .main input[name="tax_rate"]').val();
  response.discount = $('.invoice-item .main input[name="discount"]').val();
  response.note = $('.invoice-item .main input[name="note"]').val();
  response.guarantee_period = $('.invoice-item .main input[name="guarantee_period"]').val();

  return response;
}

function wh_clear_item_preview_values(parent) {
  "use strict";

  var previewArea = $(parent + ' .main');
  previewArea.find('input').val('');
  previewArea.find('textarea').val('');
  previewArea.find('select').val('').selectpicker('refresh');
}

function wh_get_item_row_template(name, commodity_name, warehouse_id, available_quantity, quantities, unit_name, unit_price, taxname, lot_number, expiry_date, commodity_code, unit_id, tax_rate, discount, note, guarantee_period, item_key)  {
  "use strict";

  jQuery.ajaxSetup({
    async: false
  });

  var d = $.post(admin_url + 'warehouse/get_good_delivery_row_template', {
    name: name,
    commodity_name : commodity_name,
    warehouse_id : warehouse_id,
    available_quantity : available_quantity,
    quantities : quantities,
    unit_name : unit_name,
    unit_price : unit_price,
    taxname : taxname,
    lot_number : lot_number,
    expiry_date : expiry_date,
    commodity_code : commodity_code,
    unit_id : unit_id,
    tax_rate : tax_rate,
    discount : discount,
    note : note,
    guarantee_period : guarantee_period,
    item_key : item_key
  });
  jQuery.ajaxSetup({
    async: true
  });
  return d;
}

function wh_delete_item(row, itemid,parent) {
  "use strict";

  $(row).parents('tr').addClass('animated fadeOut', function () {
    setTimeout(function () {
      $(row).parents('tr').remove();
      wh_calculate_total();
    }, 50);
  });
  if (itemid && $('input[name="isedit"]').length > 0) {
    $(parent+' #removed-items').append(hidden_input('removed_items[]', itemid));
  }
}

function wh_reorder_items(parent) {
  "use strict";

  var rows = $(parent + ' .table.has-calculations tbody tr.item');
  var i = 1;
  $.each(rows, function () {
    $(this).find('input.order').val(i);
    i++;
  });
}

function wh_calculate_total(){
  "use strict";
  if ($('body').hasClass('no-calculate-total')) {
    return false;
  }

  var calculated_tax,
    taxrate,
    item_taxes,
    row,
    _amount,
    _tax_name,
    taxes = {},
    taxes_rows = [],
    subtotal = 0,
    total = 0,
    total_money = 0,
    total_tax_money = 0,
    quantity = 1,
    total_discount_calculated = 0,
    item_discount_percent = 0,
    item_discount = 0,
    item_total_payment,
    rows = $('.table.has-calculations tbody tr.item'),
    subtotal_area = $('#subtotal'),
    discount_area = $('#discount_area'),
    adjustment = $('input[name="adjustment"]').val(),
    // discount_percent = $('input[name="discount_percent"]').val(),
    discount_percent = 'before_tax',
    discount_fixed = $('input[name="discount_total"]').val(),
    discount_total_type = $('.discount-total-type.selected'),
    discount_type = $('select[name="discount_type"]').val(),
    additional_discount = $('input[name="additional_discount"]').val();

  $('.wh-tax-area').remove();

    $.each(rows, function () {

    var item_tax = 0,
        item_amount  = 0;

    quantity = $(this).find('[data-quantity]').val();
    if (quantity === '') {
      quantity = 1;
      $(this).find('[data-quantity]').val(1);
    }
    item_discount_percent = $(this).find('td.discount input').val();

    if (isNaN(item_discount_percent) || item_discount_percent == '') {
      item_discount_percent = 0;
    }

    _amount = accounting.toFixed($(this).find('td.rate input').val() * quantity, app.options.decimal_places);
    item_amount = _amount;
    _amount = parseFloat(_amount);

    $(this).find('td.amount').html(format_money(_amount));

    subtotal += _amount;
    row = $(this);
    item_taxes = $(this).find('select.taxes').val();

    if (item_taxes) {
      $.each(item_taxes, function (i, taxname) {
        taxrate = row.find('select.taxes [value="' + taxname + '"]').data('taxrate');
        calculated_tax = (_amount / 100 * taxrate);
        item_tax += calculated_tax;
        if (!taxes.hasOwnProperty(taxname)) {
          if (taxrate != 0) {
            _tax_name = taxname.split('|');
            var tax_row = '<tr class="wh-tax-area"><td>' + _tax_name[0] + '(' + taxrate + '%)</td><td id="tax_id_' + slugify(taxname) + '"></td></tr>';
            $(subtotal_area).after(tax_row);
            taxes[taxname] = calculated_tax;
          }
        } else {
                    // Increment total from this tax
                    taxes[taxname] = taxes[taxname] += calculated_tax;
                }
            });
    }
      //Discount of item
      item_discount = (parseFloat(item_amount) + parseFloat(item_tax) ) * parseFloat(item_discount_percent) / 100;
      item_total_payment = parseFloat(item_amount) + parseFloat(item_tax) - parseFloat(item_discount);

      // Append value to item
      total_discount_calculated += item_discount;
      $(this).find('td.discount_money input').val(item_discount);
      $(this).find('td.total_after_discount input').val(item_total_payment);

      $(this).find('td.label_discount_money').html(format_money(item_discount));
      $(this).find('td.label_total_after_discount').html(format_money(item_total_payment));

  });

  // Discount by percent
  if ((discount_percent !== '' && discount_percent != 0) && discount_type == 'before_tax' && discount_total_type.hasClass('discount-type-percent')) {
    total_discount_calculated = (subtotal * discount_percent) / 100;
  } else if ((discount_fixed !== '' && discount_fixed != 0) && discount_type == 'before_tax' && discount_total_type.hasClass('discount-type-fixed')) {
    total_discount_calculated = discount_fixed;
  }

  $.each(taxes, function (taxname, total_tax) {
    if ((discount_percent !== '' && discount_percent != 0) && discount_type == 'before_tax' && discount_total_type.hasClass('discount-type-percent')) {
      total_tax_calculated = (total_tax * discount_percent) / 100;
      total_tax = (total_tax - total_tax_calculated);
    } else if ((discount_fixed !== '' && discount_fixed != 0) && discount_type == 'before_tax' && discount_total_type.hasClass('discount-type-fixed')) {
      var t = (discount_fixed / subtotal) * 100;
      total_tax = (total_tax - (total_tax * t) / 100);
    }

    total += total_tax;
    total_tax_money += total_tax;
    total_tax = format_money(total_tax);
    $('#tax_id_' + slugify(taxname)).html(total_tax);
  });


  total = (total + subtotal);
  total_money = total;
  // Discount by percent
  if ((discount_percent !== '' && discount_percent != 0) && discount_type == 'after_tax' && discount_total_type.hasClass('discount-type-percent')) {
    total_discount_calculated = (total * discount_percent) / 100;
  } else if ((discount_fixed !== '' && discount_fixed != 0) && discount_type == 'after_tax' && discount_total_type.hasClass('discount-type-fixed')) {
    total_discount_calculated = discount_fixed;
  }

  total = total - total_discount_calculated - parseFloat(additional_discount);
  adjustment = parseFloat(adjustment);

  // Check if adjustment not empty
  if (!isNaN(adjustment)) {
    total = total + adjustment;
  }

  var discount_html = '-' + format_money(parseFloat(total_discount_calculated)+ parseFloat(additional_discount));
    $('input[name="discount_total"]').val(accounting.toFixed(total_discount_calculated, app.options.decimal_places));
    
  // Append, format to html and display
  $('.wh-total_discount').html(discount_html + hidden_input('total_discount', accounting.toFixed(total_discount_calculated, app.options.decimal_places))  );
  $('.adjustment').html(format_money(adjustment));
  $('.wh-subtotal').html(format_money(subtotal) + hidden_input('sub_total', accounting.toFixed(subtotal, app.options.decimal_places)) + hidden_input('total_money', accounting.toFixed(total_money, app.options.decimal_places)));
  $('.wh-total').html(format_money(total) + hidden_input('after_discount', accounting.toFixed(total, app.options.decimal_places)));

  $(document).trigger('wh-receipt-note-total-calculated');

}

function get_available_quantity(commodity_code_name, from_stock_name, available_quantity_name){
  "use strict"; 
    
    var data = {};
        data.commodity_id = $('input[name="'+commodity_code_name+'"]').val();
        data.warehouse_id = $('select[name="'+from_stock_name+'"]').val();
        if(data.commodity_id != '' && data.warehouse_id != ''){
          $.post(admin_url + 'warehouse/get_quantity_inventory', data).done(function(response){
            response = JSON.parse(response);
            $('input[name="'+available_quantity_name+'"]').val(response.value);
          });
        }else{
          $('input[name="'+available_quantity_name+'"]').val(0);
        }

    setTimeout(function () {
        wh_calculate_total();
    }, 15);

}

function submit_form(save_and_send_request) {
  "use strict";

  wh_calculate_total();

  var $itemsTable = $('.invoice-items-table');
  var $previewItem = $itemsTable.find('.main');
  var check_warehouse_status = true,
      check_quantity_status = true,
      check_available_quantity_status = true;

  if ( $itemsTable.length && $itemsTable.find('.item').length === 0) {
    alert_float('warning', '<?php echo _l('wh_enter_at_least_one_product'); ?>', 3000);
    return false;
  }

  $('input[name="save_and_send_request"]').val(save_and_send_request);

  var rows = $('.table.has-calculations tbody tr.item');
  $.each(rows, function () {

    var warehouse_id = $(this).find('td.warehouse_select select').val();
    var available_quantity_value = $(this).find('td.available_quantity input').val();
    var quantity_value = $(this).find('td.quantities input').val();

    if(warehouse_id == '' || warehouse_id == undefined){
      check_warehouse_status = false;
    }
    if(parseFloat(quantity_value) == 0){
      check_quantity_status = false;
    }
    if(parseFloat(available_quantity_value) < parseFloat(quantity_value) ){
      check_available_quantity_status = false;
    }
  })

  if(check_warehouse_status == true && check_quantity_status == true && check_available_quantity_status == true){
    // Add disabled to submit buttons
    $(this).find('.add_goods_receipt_send').prop('disabled', true);
    $(this).find('.add_goods_receipt').prop('disabled', true);
    $('#add_goods_delivery').submit();
  }else{
    if(check_warehouse_status == false){
      alert_float('warning', '<?php echo _l('please_select_a_warehouse') ?>');
    }else if(check_quantity_status == false){
      alert_float('warning', '<?php echo _l('please_choose_quantity_export') ?>');
    }else{
      //check_available_quantity
      alert_float('warning', '<?php echo _l('inventory_quantity_is_not_enough') ?>');
    }

  }

  return true;
}

function invoice_change(){
  "use strict";

  var invoice_id = $('select[name="invoice_id"]').val();

  $.post(admin_url + 'warehouse/copy_invoices/'+invoice_id).done(function(response){
    response = JSON.parse(response);

    $('input[name="additional_discount"]').val((response.goods_delivery.additional_discount));
    $('.invoice-item table.invoice-items-table.items tbody').html('');
    $('.invoice-item table.invoice-items-table.items tbody').append(response.result);

    setTimeout(function () {
      wh_calculate_total();
    }, 15);

    init_selectpicker();
    init_datepicker();
    wh_reorder_items('.invoice-item');
    wh_clear_item_preview_values('.invoice-item');
    $('body').find('#items-warning').remove();
    $("body").find('.dt-loader').remove();
    $('#item_select').selectpicker('val', '');

    $('select[name="staff_id"]').val((response.goods_delivery.addedfrom)).change();
    $('textarea[name="description"]').val((response.goods_delivery.description)).change();
    $('input[name="address"]').val((response.goods_delivery.address));
    $('select[name="customer_code"]').val((response.goods_delivery.customer_code)).change();
    $('input[name="invoice_no"]').val(response.invoice_no);
  });

}

  function pr_order_change(){
     "use strict";

    var pr_order_id = $('select[name="pr_order_id"]').val();

      alert_float('warning', '<?php echo _l('stock_received_docket_from_purchase_request'); ?>')

      $.post(admin_url + 'warehouse/goods_delivery_copy_pur_order/'+pr_order_id).done(function(response){
        response = JSON.parse(response);

        $('input[name="additional_discount"]').val((response.additional_discount));
        $('.invoice-item table.invoice-items-table.items tbody').html('');
        $('.invoice-item table.invoice-items-table.items tbody').append(response.result);

        setTimeout(function () {
          wh_calculate_total();
        }, 15);

        init_selectpicker();
        init_datepicker();
        wh_reorder_items('.invoice-item');
        wh_clear_item_preview_values('.invoice-item');
        $('body').find('#items-warning').remove();
        $("body").find('.dt-loader').remove();
        $('#item_select').selectpicker('val', '');

      });

      if(pr_order_id != ''){
        $.post(admin_url + 'warehouse/copy_pur_vender/'+pr_order_id).done(function(response){
         var response_vendor = JSON.parse(response);

         $('select[name="buyer_id"]').val(response_vendor.buyer).change();
         $('select[name="project"]').val(response_vendor.project).change();
         $('select[name="type"]').val(response_vendor.type).change();
         $('select[name="department"]').val(response_vendor.department).change();
         $('select[name="requester"]').val(response_vendor.requester).change();

       });
      }else{
        $('select[name="buyer_id"]').val('').change();
        $('select[name="project"]').val('').change();
        $('select[name="type"]').val('').change();
        $('select[name="department"]').val('').change();
        $('select[name="requester"]').val('').change();
      }

  }

  
</script>