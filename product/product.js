var _is_add_mode;
var _is_child;

var $btnAdd;
var $btnUpdate;
var $btnDelete;
var $btnCancel;
var $txtPrice;
var $txtName;
var $cbChangeable;
var $tableProduct;
var $tableProductBody;
var _dtTableProduct;
var _products;
var _editingProduct;

function initPage()
{
	main_ajax_success_hide_loading();
	
	_is_add_mode = true;
	
	// is the page child window?
	_is_child = main_get_parameter('child');
	if (_is_child != null && _is_child.length > 0)
		_is_child = true;
	else
		_is_child = false;
	
	$btnAdd = $('#btnAdd');
	$btnUpdate = $('#btnUpdate');
	$btnDelete = $('#btnDelete');
	$btnCancel = $('#btnCancel');
	$txtPrice = $('#txtPrice');
	$txtName = $('#txtName');
	$cbChangeable = $('#cbChangeable');
	
	$txtPrice.autoNumeric('init', { vMin: 0, vMax: 1000.99, aSign: '$' });
	$txtPrice.focus(function(){ $(this).select(); });
	
	$tableProduct = $('#tableProduct');
	// keep instance of DataTable so that it will be used for row.add(), rows().remove() and others
	_dtTableProduct = $tableProduct.DataTable({
		paging: false,
		info: false,
		searching: false,
		ordering: false,
		rowId: 'product_id',
		columns: [
		    { data: "product_id", title: "ID", visible: false },
		    { data: "product_name", title: "Product Name" },
		    { data: "product_price", title: "Product Price"
		    	, render: function ( data, type, row ) { return (row['product_price_changeable'] == 1) ? '$' + data + ' <img src="../image/changeable.png" title="Changeable price">'  : '$' + data; } }
        ]
	});
	
	$tableProductBody = $('#tableProduct tbody');
	
	$btnAdd.click(function(){
		if (validateInputs()) {
			main_confirm_message('Do you want to add a new product?', addProduct, function(){ $btnAdd.focus(); });
		}				
	});
	
	$btnUpdate.click(function(){
		if (validateInputs()) {
			updateProduct();
		}
	});
	
	$btnDelete.click(function(){
		main_confirm_message('Do you want to DELETE the product?', deleteProduct, function(){ $btnCancel.focus(); }, 1);
	});

	$btnCancel.click(function(){
		turnOffEditMode();
	});
	
	$txtName.keypress(function(e){
		if (e.which == 13) {
			$txtPrice.focus();
			return false;
		}
	});
	
	$txtPrice.keypress(function(e){
		if (e.which == 13) {
			if (_is_add_mode)
				$btnAdd.click();
			else
				$btnUpdate.click();
			return false;
		}
	});
	
	$cbChangeable.keypress(function(e){
		if (e.which == 13) {
			if (_is_add_mode)
				$btnAdd.click();
			else
				$btnUpdate.click();
			return false;
		}
	});
	
	getProducts();
}

function getProducts()
{
	main_request_ajax('product-boundary.php', 'GET_PRODUCT', {}, onGetProductDone);
}

function onGetProductDone(response)
{
	if (response.success) {
		_products = response.result;
		
		clearTableProduct();
		addProductRows(_products);
		setProductRowSelection();
		
		clearInputs();
	}
}

function clearTableProduct()
{
	_dtTableProduct.rows().remove().draw();
	$tableProductBody.unbind(); // unbind events to prevent duplicate events
}

function addProductRows(result)
{
	for (var i = 0; i < result.length; i++) {
		_dtTableProduct.row.add({
			product_id: result[i]['product_id'],
			product_price: result[i]['product_price'],
			product_price_changeable: result[i]['product_price_changeable'],
			product_name: result[i]['product_name']}).draw();
	}
}

function setProductRowSelection()
{
	$tableProductBody.on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            //$(this).removeClass('selected');
        }
        else {
        	_dtTableProduct.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });
	
	$tableProductBody.on('dblclick', 'tr', function () {
		turnOnEditMode(_dtTableProduct.row('.selected').index());
	});
}

function validateInputs()
{
	if ($txtPrice.val().replace(/\$/i, '').length) {
		if ($txtName.val().trim().length) {
			return true;
		}
		else {
			main_alert_message('Please enter "Product Name"', function(){ $txtName.focus();});
		}
	}
	else {
		main_alert_message('Please enter "Product Price"', function(){ $txtPrice.focus();});
	}
	
	return false;
} // validateInputs

function addProduct()
{
	var productInfo = getProductInfo();
	main_request_ajax('product-boundary.php', 'ADD_PRODUCT', productInfo, onAddProductDone);
}

function onAddProductDone(response)
{
	if (response.success) {
		parentCallback(function() {
			main_info_message(response.msg, getProducts);
		});
	}
	else
		main_alert_message(response.msg);
}

function updateProduct()
{
	var productInfo = getEditedProductInfo();
	main_request_ajax('product-boundary.php', 'UPDATE_PRODUCT', productInfo, onUpdateProductDone);
}

function onUpdateProductDone(response)
{
	if (response.success) {
		parentCallback(function() {
			turnOffEditMode();
			main_info_message(response.msg, getProducts);
		});
	}
	else
		main_alert_message(response.msg);
}

function deleteProduct()
{
	var productInfo = getEditedProductInfo();
	main_request_ajax('product-boundary.php', 'DELETE_PRODUCT', productInfo, onDeleteProductDone);
}

function onDeleteProductDone(response)
{
	if (response.success) {
		parentCallback(function() {
			turnOffEditMode();
			main_info_message(response.msg, getProducts);
		});
	}
	else
		main_alert_message(response.msg);
}

function getProductInfo()
{
	var productInfo = {
			product_id: '',
			product_price: $txtPrice.autoNumeric('get'),
			product_name: $txtName.val(),
			product_price_changeable: $cbChangeable.is(':checked')
	};
	
	return productInfo;
}

function getEditedProductInfo()
{
	var productInfo = {
			product_id: _editingProduct['product_id'],
			product_price: $txtPrice.autoNumeric('get'),
			product_name: $txtName.val(),
			product_price_changeable: $cbChangeable.is(':checked')
	};
	
	return productInfo;
}

function clearInputs()
{
	$txtPrice.autoNumeric('set', 0);
	$txtName.val('');
	$cbChangeable.prop('checked', false);
	
	$txtName.focus();
}

function turnOnEditMode(productIndex)
{
	_is_add_mode = false;
	
	_editingProduct = _products[productIndex];
	
	$btnAdd.addClass('hidden');
	$btnUpdate.removeClass('hidden');
	$btnDelete.removeClass('hidden');
	$btnCancel.removeClass('hidden');
	
	$txtPrice.autoNumeric('set', _editingProduct['product_price']);
	$txtName.val(_editingProduct['product_name']);
	if (_editingProduct['product_price_changeable'] == 1) 
		$cbChangeable.prop('checked', true);
	else
		$cbChangeable.prop('checked', false);
	
	main_move_to_title_text(function(){ $txtName.focus(); });
}

function turnOffEditMode()
{
	_is_add_mode = true;
	
	$btnAdd.removeClass('hidden');
	$btnUpdate.addClass('hidden');
	$btnDelete.addClass('hidden');
	$btnCancel.addClass('hidden');
	
	clearInputs();
}

function parentCallback(selfCallback)
{
	if (_is_child) {
		opener.parentCallback();
		self.close();
	}
	else {
		selfCallback();
	}
}





