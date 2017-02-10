var _is_add_mode_cart, _is_add_mode_sale;
var _products, _editModeProducts;
var _cartItems, _editingCartItem, _editingCartItemIndex, _editingCartItemID;
var _sales, _sales_display, _editingSale, _editingSaleIndex;
var _dt_previous_row_no = 0;

var $txtAmt, $txtPrice, $txtTotal, $ddlProduct;
var $txtCash, $txtCredit;
var $tableSale, $tableSaleBody, _dtTableSale;
var $tableCart, $tableCartBody, _dtTableCart;
var $btnAddCart, $btnUpdateCart, $btnCancelEditCart;
var $btnAdd, $btnUpdate, $btnDelete, $btnCancelEdit;

var Sale = { sale_id: 0, sale_uid: '', sale_date: '', sale_time: ''
	, sale_total: 0, sale_cash: 0, sale_credit: 0
	, sale_create_user: 0, sale_create_datetime: ''
	, sale_update_user: 0, sael_update_datetime: ''
	, sale_void_user: 0, sale_void_datetime: ''
	, sale_items: []};

var Sale_Item = { sale_item_id: 0
	, product_id: 0, product_name: '', product_active: 1, product_price_changeable: 0
	, sale_item_amount: 0, sale_item_price: 0, sale_item_total: 0
	, sale_item_create_user: 0, sale_item_create_datetime: ''
	, sale_item_update_user: 0, sael_item_update_datetime: '', sale_item_new_update: 0
	, sale_item_void_user: 0, sale_item_void_datetime: ''};

function initPage()
{		
	main_ajax_success_hide_loading();
	
	_is_add_mode_sale = true;
	_is_add_mode_cart = true;
	_cartItems = [];
	
	$ddlProduct = $("#ddlProduct");
	$txtAmt = $("#txtAmt");
	$txtPrice = $("#txtPrice");
	$txtTotal = $("#txtTotal");
	$txtCash = $("#txtCash");
	$txtCredit = $("#txtCredit");
	$btnAddCart = $("#btnAddCart");
	$btnUpdateCart = $("#btnUpdateCart");
	$btnCancelEditCart = $("#btnCancelEditCart");
	$btnAdd = $("#btnAdd");
	$btnUpdate = $("#btnUpdate");
	$btnDelete = $("#btnDelete");
	$btnCancelEdit = $("#btnCancelEdit");
	
	initMoneyInput($txtPrice, 0, 9999.99);
	initMoneyInput($txtTotal, 0, 99999999.99);
	initMoneyInput($txtCash, 0, 99999999.99);
	initMoneyInput($txtCredit, 0, 99999999.99);
	
	setTextAllSelection($txtPrice);
	setTextAllSelection($txtTotal);
	setTextAllSelection($txtCash);
	setTextAllSelection($txtCredit);
	
	setSubmitTabIndex($txtPrice, $txtAmt);
	setSubmitTabIndex($txtAmt, function(){
		if (_is_add_mode_cart)
			$btnAddCart.click();
		else
			$btnUpdateCart.click();
	});
	setSubmitTabIndex($txtCash, $txtCredit);
	setSubmitTabIndex($txtCredit, function(){
		if (_is_add_mode_sale)
			$btnAdd.click();
		else
			$btnUpdate.click();
	});
	
	$txtPrice.change(function(){
		calTotal();
	});
	
	$txtAmt.TouchSpin({
		verticalbuttons: true,
		initval: 1,
		min: 1,
		max: 9999,
		step: 1}
	);
	$txtAmt.change(function(){
		calTotal();
	});
	
	$ddlProduct.change(function(){
		if ($(this).val() === 'ADD_NEW_PRODUCT') // "ADD NEW PRODUCT" selected 
		{
			main_open_child_window('../product/product.php', initProducts);
			main_set_dropdown_index(this);
		}
		
		setProductPrice();
	});
	
	$btnAddCart.click(function() {
		addItemToCart();
	});
	
	$btnCancelEditCart.click(function (){
		turnOffCartEditMode();
		clearCartInputs();
	});
	
	$btnUpdateCart.click(function (){
		updateItemToCart();
	});
	
	$btnAdd.click(function(){
		addSale();
	});
	
	$btnUpdate.click(function(){
		updateSale();
	});
	
	$btnDelete.click(function(){
		deleteSale();
	});
	
	$btnCancelEdit.click(function(){
		turnOffCartEditMode();
		turnOffSaleEditMode();
		//initDatepicker();
		parent.initDatepicker();
		setTableSaleRowSelection();
		clearCartInputs();
		clearSaleInputs();
		clearTableCart();
		_cartItems = [];
	});
	
	//parent.initDatepicker(); // first initialize only in the paret page
	
	turnOffCartEditMode();
	turnOffSaleEditMode();
	initTableSale();
	initTableCart();
	initProducts();
	
	getSaleRecords();
}

function initProducts()
{
	main_request_ajax('../product/product-boundary.php', 'GET_PRODUCT', {}, onInitProductsDone);
}

function onInitProductsDone(response)
{
	if (response.success) {
		_products = response.result;
		
		if (_is_add_mode_cart) {
			bindProductOption(_products);
		}
		else {
			setEditModeProduct();
		}
		
		setProductPrice();
	}
}

function bindProductOption(products)
{
	$ddlProduct.empty();
	$.each(products, function (i, product){
		option = "<option value='" + product['product_id'] + "'>" + product['product_name'] + "</option>";
		
		$ddlProduct.append(option);
	});
	
	$ddlProduct.append("<optgroup label='--------------------------------------------'></optgroup>");
	$ddlProduct.append("<option value='ADD_NEW_PRODUCT'>&gt;&gt; ADD/EDIT PRODUCT &lt;&lt;</option>");
}

function setEditModeProduct()
{
	_editModeProducts = _products.slice(0);
	if (_editingRecord['product_active'] == 0) {
		_editModeProducts.push(getDeletedProduct());
				
		bindProductOption(_editModeProducts);
	}
}

function getDeletedProduct()
{
	deletedItem = { 
		product_id: _editingRecord['product_id']
		, product_name: _editingRecord['product_name'] + " (Deleted)"
		, product_active: _editingRecord['product_active']
		, product_commission: _editingRecord['product_price']};
		
	return deletedItem;
}

function getSelectedProduct() {
	selectedIndex = $ddlProduct.prop('selectedIndex');
	
	if (_is_add_mode_cart)
		return _products[selectedIndex];
	else
		return _editModeProducts[selectedIndex];
}

function setProductPrice()
{
	selectedProduct = getSelectedProduct();
	
	setMoneyInputValue($txtPrice, parseFloat(selectedProduct["product_price"]));
	
	if (selectedProduct['product_price_changeable'] == 1)
		main_enable_control($txtPrice);
	else
		main_disable_control($txtPrice);
	
	calTotal();
}

function calTotal()
{
	total = 0;
	
	if (getMoneyInputValue($txtPrice).length
			&& $txtAmt.val().length) {
		price = getMoneyInputValue($txtPrice);
		amt = $txtAmt.val();
		
		total = price * amt
	}
	
	$txtTotal.autoNumeric('set', total);
}

function initTableSale()
{
	$tableSale = $('#tableSale');
	
	_dtTableSale = $tableSale.DataTable({
		scrollY: _main_datatable_scroll_y,
		paging: false,
		info: false,
		searching: false,
		ordering: false,
		order: [[0, 'desc']], // default ordering - row_no:desc
		rowId: 'sale_uid',
		columns: [
		          { data: "row_no"
		        	  , render: function(data, type, row) {
		        		  if (data != _dt_previous_row_no) {
		        			  _dt_previous_row_no = data
		        			  return data;
		        		  } else {
		        			  // Do not display a duplicate info
		        			  row['sale_id'] = '';
		        			  row['sale_total'] = '';
		        			  row['sale_date'] = '';
		        			  row['sale_time'] = '';
		        			  row['sale_cash'] = '';
		        			  row['sale_credit'] = '';
		        			  return '';
		        		  }
		        	  }
		          },
		          { data: "sale_id", orderable: false
		        	  , render: function(data, type, row) {
		        		  if (data != "") // if it is not duplicate info
		        			  return data + " (" + row['sale_date'] + ' ' + row['sale_time'] + ")";
		        		  else
		        			  return "";
		        	  }
		          },
		          { data: "product_name", orderable: false, className: 'text-nowrap'
		        	  , render: function(data, type, row) {
		        		  if (row['sale_item_amount'] > 1) {
		        			  return data + ' (' + row['sale_item_amount'] + ' @ $' + row['sale_item_price'] + ' each)';
		        		  }
		        		  else {
		        			  return data;
		        		  }
		        	  }
		          },
		          { data: "sale_item_total", orderable: false, className: "text-right", render: function(data, type, row) { return "$" + data; }},
		          { data: "sale_total", orderable: false, className: "text-right"
		        	  , render: function(data, type, row) { return data != '' ? '$' + data : ''; }
		          },
		          { data: "sale_cash", orderable: false, className: "text-right"
		        	  , render: function(data, type, row) { return data != '' ? '$' + data : ''; }
		          },
		          { data: "sale_credit", orderable: false, className: "text-right"
		        	  , render: function(data, type, row) { return data != '' ? '$' + data : ''; }
		          },
		          { data: "sale_date", orderable: false
		        	  , render: function(data, type, row) {
		        		  if (data != '') {
		        			  return '<a href="../report/report.php?report_type=SALE_RECEIPT&uid=' + row['sale_uid'] + '" target="_blank" class="btn btn-success btn-xs">Receipt</a>';
		        		  } else {
		        			  return '';
		        		  }
		        	  }
		          },
		]
	});
}

function initTableCart()
{
	$tableCart = $('#tableCart');
	
	_dtTableCart = $tableCart.DataTable({
		scrollY: 100,
		language: {
		      emptyTable: "No products in the cart"
		},
		paging: false,
		info: false,
		searching: false,
		ordering: false,
		rowId: 'sale_item_id',
		columns: [
		          { data: "product_name", width: "50%"
		        	  , render: function( data, type, row) { 
		        		  if (row["sale_item_amount"] > 1) {
		        			  return data + " ($" + row["sale_item_price"] + " each)"
		        		  }
		        		  else {
		        			  return data;
		        		  }
		        	  }},
		          { data: "sale_item_amount", width: "20%"},
		          { data: "sale_item_total", width: "20%", className: "text-right"
		        	  , render: function(data, type, row) { 
		        		  return "$" + data;
		        	  }},
		          { data: "sale_item_id", width: "10%"
		        	  , render: function(data, type, row) {
		        		  return '<button type="button" id="btnDeleteCart' + data + '" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>';
		        	  }}
		],
		footerCallback: function(row, start, data, end, display){
			var api = this.api(), data;
			
			var numVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1.0 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            
            total = api.column(2).data().reduce( function (a, b) {
                    return numVal(a) + numVal(b);
                }, 0 );
            
			$(api.column(2).footer()).html('$' + total);
		}
	});
	
}

function getSaleRecords()
{
	date = parent.getSelectedDailyRecordDate();
	main_request_ajax('sale-boundary.php', 'GET_SALES', date, getSaleRecordsDone);
}
function getSaleRecordsDone(response)
{
	clearTableSale();
	
	if (response.success) {
		_dt_previous_row_no = 0;	
		_sales_display = response.result['sales_display'];
		_sales = response.result['sales'];
		
		bindTableSale(_sales_display);
	}
	else {
		main_alert_message(response.msg);
	}
}

function bindTableSale(salesDisplay) {
	clearTableSale();
	addSaleRows(salesDisplay);
	setTableSaleRowSelection();
}

function clearTableSale() {	
	_dtTableSale.rows().remove().draw();
	unbindSaleRowSelection(); // unbind events to prevent duplicate events
}

function addSaleRows(salesDisplay)
{
	for (var i = 0; i < salesDisplay.length; i++) {
		_dtTableSale.row.add({
			row_no: salesDisplay[i]['row_no'],
			sale_uid: salesDisplay[i]['sale_uid'],
			sale_id: salesDisplay[i]['sale_id'],
			sale_date: salesDisplay[i]['sale_date'],
			sale_time: salesDisplay[i]['sale_time'],
			sale_total: salesDisplay[i]['sale_total'],
			sale_item_amount: salesDisplay[i]['sale_item_amount'],
			sale_item_price: salesDisplay[i]['sale_item_price'],
			sale_item_total: salesDisplay[i]['sale_item_total'],
			sale_cash: salesDisplay[i]['sale_cash'],
			sale_credit: salesDisplay[i]['sale_credit'],
			product_name: salesDisplay[i]['product_name']
		}).draw();
	}
}

function setTableSaleRowSelection() {
	$tableSale.on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            //$(this).removeClass('selected');
        }
        else {
            _dtTableSale.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });
	
	$tableSale.on('dblclick', 'tr', function () {
		turnOnSaleEditMode();
		//setEditingSaleItem(_dtTableSale.row('.selected').index()); // can also use .id()
		setEditingSale(_dtTableSale.row('.selected').id());
	});
}

function unbindSaleRowSelection() 
{
	$tableSale.unbind(); // unbind events to prevent duplicate events
}

function setEditingSale(uid)
{
	// get editing sale
	_editingSale = getSale(uid);
	
	//$dateInput.datepicker('destroy'); // users cannot change the date during editing the item
	parent.destroyDatepicker();
	
	setMoneyInputValue($txtCash, _editingSale['sale_cash']);
	setMoneyInputValue($txtCredit, _editingSale['sale_credit']);
	
	// turn off cart editing mode, and then set editing sale items to the cart
	turnOffCartEditMode();
	_cartItems = JSON.parse(JSON.stringify(_editingSale['sale_items']));
	bindTableCart(_cartItems);
	
	unbindSaleRowSelection(); // users cannot select a row in datatable during editing the item
	main_move_to_title_text(450);
}

function getSale(uid) {
	for(var i = 0; i < _sales.length; i++) {
		if (_sales[i]['sale_uid'] == uid)
			return _sales[i];
	}
}

function getCartItemInfo() {
	selectedProduct = getSelectedProduct();
	
	if (_is_add_mode_cart) {
		item = JSON.parse(JSON.stringify(Sale_Item));
		item.sale_item_id = new Date().valueOf();
	} else {
		item = _editingCartItem;
	}
	
	item.product_id = $ddlProduct.val();
	item.product_name = selectedProduct['product_name'];
	item.product_price_changeable = selectedProduct['product_price_changeable'];
	item.sale_item_price =  getMoneyInputValue($txtPrice);
	item.sale_item_amount = $txtAmt.val();
	item.sale_item_total = getMoneyInputValue($txtTotal);
	
	return item;
}

function validateCartItemInfo() {
	if (getMoneyInputValue($txtPrice).length) {
		if ($txtAmt.val().length) {
			return true;
		}
		else {
			main_alert_message('Please enter "Amount"', function(){ $txtAmt.focus();});
		}
	}
	else {
		main_alert_message('Please enter "Price"', function(){ $txtPrice.focus();});
	}
}

function bindTableCart(items) {
	clearTableCart();
	addCartRows(items);
	setTableCartDeleteOption(items);
	setTableCartRowSelection();
}

function clearTableCart() {
	_dtTableCart.rows().remove().draw();
	$tableCart.unbind(); // unbind events to prevent duplicate events
}

function addCartRows(items) {
	for (var i = 0; i < items.length; i++) {
		if (items[i]['sale_item_void_user'] == 0) {
			_dtTableCart.row.add({
				product_name: items[i]['product_name'],
				sale_item_price: items[i]['sale_item_price'],
				sale_item_amount: items[i]['sale_item_amount'],
				sale_item_total: items[i]['sale_item_total'],
				sale_item_id: items[i]['sale_item_id']
			}).draw();
		}
	}
}

function setTableCartRowSelection() {
	$tableCart.on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            //$(this).removeClass('selected');
        }
        else {
            _dtTableCart.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });
	
	$tableCart.on('dblclick', 'tr', function () {
		//main_alert_message(dtTableRecord.row('.selected').id());
		turnOnCartEditMode();
		//setEditingCartItem(_dtTableCart.row('.selected').index()); // can also use .id()
		setEditingCartItem(_dtTableCart.row('.selected').id());
	});
}

function setTableCartDeleteOption(items) {
	for (var i = 0; i < items.length; i++) {
		if (items[i]['sale_item_void_user'] == 0) {			
			setCartItemDeleteButton(i, items[i]['sale_item_id']);
			
			/*
			 * Passing a variable to a binding event within "for loop" 
			 * 		result in EVERY event will point to the variable that refer to the lastest value!!
			 * SOLUTION : passing a value to a function that binds an event 
			 * 		eg. "setCartItemDeleteButton(itemID)"  
			$('#btnDeleteCart' + itemID).click(function (){
				alert(itemID);
			});
			*/
		}
	}
}

function setCartItemDeleteButton(cartItemIndex, cartItemID) {
	$('#btnDeleteCart' + cartItemID).click(function (){
		//alert(cartItemIndex + ":" + cartItemID);
		if (_is_add_mode_sale) {
			deleteItemFromCart(cartItemIndex);
		} else {
			main_confirm_message('Do you want to DELETE the item in the cart?', function() {
				deleteItemFromCart(cartItemIndex);
			}, function(){}, 1);
		}
	});
}

function addItemToCart() {
	if (validateCartItemInfo()) {
		calTotal();
		item = getCartItemInfo();
		
		_cartItems.push(item);
		bindTableCart(_cartItems);
		
		clearCartInputs();
	}
}

function updateItemToCart()
{
	if (validateCartItemInfo()) {
		calTotal();
		//_cartItems[_editingCartItemIndex] = getCartItemInfo();
		for(var i = 0; i < _cartItems.length; i++) {
			if (_cartItems[i]['sale_item_id'] == _editingCartItemID) {
				_cartItems[i] = getCartItemInfo();
				_cartItems[i]['sale_item_new_update'] = 1;
				break;
			}
		}
		
		bindTableCart(_cartItems);
		turnOffCartEditMode();
		clearCartInputs();
	}
}

function deleteItemFromCart(itemIndex)
{
	_cartItems[itemIndex]['sale_item_void_user'] = 999; // "999" used for the flag
	bindTableCart(_cartItems);
	if (!_is_add_mode_cart) { 
		clearCartInputs();
		turnOffCartEditMode();
	}
}

function setEditingCartItem(itemID)
{
	// get editing record 
	//_editingCartItem = _cartItems[itemIndex];
	//_editingCartItemIndex = itemIndex;
	_editingCartItem = getCartItem(itemID);
	_editingCartItemID = _editingCartItem['sale_item_id'];	
	
	setEditModeProductList();
	$ddlProduct.val(_editingCartItem['product_id']);
	$txtAmt.val(_editingCartItem['sale_item_amount']);
	setMoneyInputValue($txtPrice, _editingCartItem['sale_item_price']);
	setMoneyInputValue($txtTotal, _editingCartItem['sale_item_total']);
	
	if (_editingCartItem['product_price_changeable'] == 1)
		main_enable_control($txtPrice)
	else
		main_disable_control($txtPrice)
	
	$ddlProduct.focus();
}

function setEditModeProductList() {
	_editModeProducts = _products.slice(0);
	if (_editingCartItem['product_active'] == 0) {
		_editModeProducts.push(getDeletedProduct());
		
		bindProductOption(_editModeProducts);
	}
}

function getDeletedProduct()
{
	deletedItem = { 
		product_id: _editingCartItem['product_id']
		, product_name: _editingCartItem['product_name'] + " (Deleted)"
		, product_active: _editingCartItem['product_active']
		, product_price: _editingCartItem['product_price']
		, product_changeable: _editingCartItem['product_price_changeable']};
		
	return deletedItem;
}

function turnOnCartEditMode()
{
	_is_add_mode_cart = false;
	
	$btnAddCart.addClass('hidden');
	$btnUpdateCart.removeClass('hidden');
	$btnCancelEditCart.removeClass('hidden');
}

function turnOffCartEditMode()
{
	_is_add_mode_cart = true;
	
	$btnAddCart.removeClass('hidden');
	$btnUpdateCart.addClass('hidden');
	$btnCancelEditCart.addClass('hidden');
}

function clearCartInputs()
{
	//$ddlProduct.focus();
	$txtAmt.val(1);
	setProductPrice();
}

function getCartItem(itemID) {
	for(var i = 0; i < _cartItems.length; i++) {
		//alert(_cartItems[i]['sale_item_id'] + " : " + itemID);
		if (_cartItems[i]['sale_item_id'] == itemID)
			return _cartItems[i];
	}
}

function isTheCartNotEmpty() {
	for(var i = 0; i < _cartItems.length; i++) {
		if (_cartItems[i]['sale_item_void_user'] == 0)
			return true;
	}
	
	return false;
}

function validateSaleInfo() {
	if (isTheCartNotEmpty()) {
		if (getMoneyInputValue($txtCash).length) {
			if (getMoneyInputValue($txtCredit).length) {
				return true;
			}
			else {
				main_alert_message('Please enter "Credit"', function(){ $txtCredit.focus();});
			}
		}
		else {
			main_alert_message('Please enter "Cash"', function(){ $txtCash.focus();});
		}
	}
	else {
		main_alert_message('There is no item in the cart!', function(){ $ddlProduct.focus();});
	}
}

function addSale() {
	if (validateSaleInfo()) {
		main_confirm_message('Do you want to add a new sale?'
				, function(){
					newSale = getSaleInfo();
					//alert(JSON.stringify(newSale));
					main_request_ajax('sale-boundary.php', 'ADD_SALE', newSale, onAddSaleDone);
				}
				, function(){ $btnAdd.focus(); });
	}
}

function onAddSaleDone(response) {
	if (response.success) {
		clearCartItemsInfo();
		clearCartInputs();
		clearSaleInputs();
		main_info_message(response.msg, getSaleRecords);
		//main_info_message(response.msg, getRecords);
	}
	else {
		main_alert_message(response.msg);
	}
}

function updateSale()
{
	if (validateSaleInfo()) {
		// Need to initialize datepicker first so that can use its method 'getDate' in 'getRecordInfo'
		// otherwise datepicker will work incorrectly after 'getDate' called
		//initDatepicker();
		parent.initDatepicker();
		saleInfo = getSaleInfo();
		
		turnOffCartEditMode();
		turnOffSaleEditMode();
		clearCartInputs();
		clearSaleInputs();
		clearTableCart();
		_cartItems = [];
		
		main_request_ajax('sale-boundary.php', 'UPDATE_SALE', saleInfo, onUpdateSaleDone);
	}
}

function onUpdateSaleDone(response)
{
	if (response.success) {
		main_info_message(response.msg, getSaleRecords);
	}
	else {
		setTableSaleRowSelection();
		main_alert_message(response.msg);
	}
}

function deleteSale()
{
	main_confirm_message('Do you want to DELETE the sale?', function() {
		//initDatepicker();
		parent.initDatepicker();
		uid = _editingSale['sale_uid'];
		
		turnOffCartEditMode();
		turnOffSaleEditMode();
		clearCartInputs();
		clearSaleInputs();
		clearTableCart();
		_cartItems = [];
		
		main_request_ajax('sale-boundary.php', 'DELETE_SALE', uid, onDeleteSaleDone);
	}, function(){
		$btnDelete.focus();
	}, 1);
}

function onDeleteSaleDone(response)
{
	if (response.success) {
		main_info_message(response.msg, getSaleRecords);
	}
	else {
		main_alert_message(response.msg);
	}
}

function getSaleInfo() {
	if (_is_add_mode_sale) {
		sale = JSON.parse(JSON.stringify(Sale));
	} else {
		sale = _editingSale;
	}
	
	sale.sale_date = parent.getSelectedDailyRecordDate();
	sale.sale_items = _cartItems;
	sale.sale_cash = getMoneyInputValue($txtCash);
	sale.sale_credit = getMoneyInputValue($txtCredit);
	// sale_id, sale_uid, sale_time, sale_total will be set in Biz  
	
	return sale;
}

function clearSaleInputs() {
	setMoneyInputValue($txtCash, 0);
	setMoneyInputValue($txtCredit, 0);
}

function clearCartItemsInfo() {
	_cartItems = [];
	clearTableCart();
}

function turnOnSaleEditMode()
{
	_is_add_mode_sale = false;
	
	$btnAdd.addClass('hidden');
	$btnUpdate.removeClass('hidden');
	$btnDelete.removeClass('hidden');
	$btnCancelEdit.removeClass('hidden');
}

function turnOffSaleEditMode()
{
	_is_add_mode_sale = true;
	
	$btnAdd.removeClass('hidden');
	$btnUpdate.addClass('hidden');
	$btnDelete.addClass('hidden');
	$btnCancelEdit.addClass('hidden');
}

function getSelectedDailyRecordDate() {
	return convertDBFormatDate(new Date());
}

//will be called by PARENT
function clearFrameEditMode()
{
	//alert("CLEAR - SALE");
	if (!_is_add_mode_sale) {
		parent.initDatepicker();
		
		turnOffCartEditMode();
		turnOffSaleEditMode();
		setTableSaleRowSelection();
		clearCartInputs();
		clearSaleInputs();
		clearTableCart();
		_cartItems = [];
	}
}

//will be called by PARENT
function updateFrameContent()
{
	//alert("UPDATE - SALE");
	getSaleRecords();
}










