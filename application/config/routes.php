<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
| example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
| https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
| $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
| $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
| $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples: my-controller/index -> my_controller/index
|  my-controller/my-method -> my_controller/my_method
 */
$route['default_controller'] = 'Login';

$route['users']                 = 'Users';
$route['add-new-user']          = 'Users/add_view';
$route['update-user/(:any)']    = 'Users/edit_view/$1';
$route['user-details/(:num)']   = 'Users/report_view/$1';
$route['delete-user/(:num)']    = 'Users/delete_update/$1';
$route['user-privilege/(:num)'] = 'Users/getusersprivilegeview/$1';

$route['dispatched']          = 'Shipment/dispatched';
$route['InboundRecord']       = 'ItemInventory/InboundRecord';
$route['backorder']           = 'Shipment/getallbackordersview';
$route['ordergenerated']      = 'Shipment/ordergeneratedView';
$route['packed']              = 'Shipment/packed';
$route['outbound']            = 'Shipment/outbound';
$route['manifestView/(:any)'] = 'Shipment/manifestView/$1';
$route['delivery_manifest']   = 'Shipment/delivery_manifest';
$route['manifestListFilter']  = 'Shipment/manifestListFilter';
$route['manifestListFilter']  = 'Shipment/manifestListFilter';


$route['smsconfigration'] = 'Generalsetting/smsconfigration';
$route['manifestPrint_d/(:any)'] = 'Shipment/manifestPrint/$1';


$route['damage_list']       = 'ItemInventory/damage_list_view';

//===========last mile invoices=========//
$route['createInvoice']       = 'LastmileInvoice/createInvoice'; 
$route['createInvoiceAuto']       = 'LastmileInvoice/createInvoiceAuto'; 
$route['viewLmInvoice']       = 'LastmileInvoice/viewLmInvoice'; 
$route['codreceivablePrint/(:any)'] = 'LastmileInvoice/codreceivablePrint/$1'; 
$route['codreceivableArray/(:any)'] = 'LastmileInvoice/codreceivableArray/$1'; 





//==============vehicle ==================//
$route['vehicle_list'] = 'Vehicle';
$route['add_vehicle'] = 'Vehicle/add_view';
$route['edit_vehicle/(:any)'] = 'Vehicle/add_view/$1';
$route['deleteVehicle/(:any)'] = 'Vehicle/deleteVehicle/$1';
//========================================//
$route['forward_report'] = 'Shipment/forward_report';
$route['delivered'] = 'Shipment/delivered_view';
$route['returned']  = 'Shipment/returned_view';

$route['bulk_print_barcode'] = 'Item/bulk_print_barcode';

$route['topdispatchproduct'] = 'ItemInventory/topdispatchproduct';

$route['inventory_check'] = 'ItemInventory/inventory_check';
$route['inventory_check_new'] = 'Stocks/inventory_check';

//============picking url=====================//
$route['pickedSingle/(:any)'] = 'PickUp/pickedViewSingle/$1';
$route['pickedSingleView']    = 'PickUp/pickedViewSingleList';
$route['pickedcompletedView'] = 'PickUp/pickedcompletedView';
$route['pickedBatch/(:any)']  = 'PickUp/pickedViewbatch/$1';
$route['pickedBatchView']     = 'PickUp/pickedBatchView';
$route['PrintPacking/(:any)'] = 'PickUp/printawbTrack/$1';
$route['dispatch3pl'] = 'PickUp/dispacth3pl';

//============================================//
$route['orderCreated']             = 'Shipment/orderCreated';
$route['bulkprint']                = 'Shipment/BulkPrintPage';
$route['print/invoice']                = 'Print_I/BulkPrintinvoice_view';
$route['Forward_Delivery_Station'] = 'Shipment/ForwardtoDeliveryStation';
$route['Forward_Delivery_Station_New'] = 'Shipment/ForwardtoDeliveryStationNew';
$route['Reverse_Delivery_Station'] = 'Shipment/ReversetoDeliveryStation';
$route['Reverse_Shipment'] = 'Shipment/ViewReverseShipment';
$route['shipment_mapping'] = 'Shipment/ViewShipmentMapping';
$route['add_new_mapping'] = 'Shipment/addNewMapping';
$route['update_mapping'] = 'Shipment/updateMapping';
$route['bulk-audit'] = 'Shipment/bulkaudit';

$route['bulk_print_stock'] = 'shelve/bulk_print_barcode';
$route['generatePickup']   = 'Shipment/generatePickup';
$route['bulk_update_view'] = 'Shipment/bulk_update_view';
$route['validateUpdate']   = 'Shipment/validateUpdate';
$route['updateData']       = 'Shipment/updateData';

$route['pickupList']             = 'PickUp/pickupList';
$route['pickListView/(:any)']    = 'PickUp/pickListView/$1';
$route['OfferOrders']            = 'Offers/GetofferOrderslist';
$route['import_promo']            = 'Offers/import_promo';

$route['OfferOrders_gift']       = 'Offers/GetofferOrderslist_gift';
$route['pickListFilter']         = 'PickUp/pickListFilter';
$route['packing']                = 'PickUp/packing';
$route['packing_new']                = 'PickUp/packing_new';
$route['packing_tod']            = 'PickUp/packing_tod';
$route['packing_b2b']                = 'Business/packing_b2b';
$route['dispatch']               = 'PickUp/dispatch';
$route['dispatch_b2b']               = 'PickUp/dispatch_b2b';
$route['TrackingResult']         = 'Shipment/getshipmenttrackingresult';
$route['TrackingDetails/(:any)'] = 'Shipment/getshipmentdetailshow/$1';
$route['Staff_report']           = 'PickUp/Staff_report';

$route['dispatching_report'] = 'Reports/client_report';

$route['shelve_report'] = 'ItemInventory/shelve_report';
//===============sms=============================//
$route['add_template']         = 'Templates/Addtemplate';
$route['show_template']        = 'Templates/smsList';
$route['edit_template/(:any)'] = 'Templates/editTemplate/$1';
//================================================//

//===========access template========================//
$route['add_access_template']  = 'Users/add_access_template';
$route['edit_access_template/(:any)']  = 'Users/add_access_template/$1';
$route['show_access_template'] = 'Users/show_access_template';
//==================================================//

$route['awbPrint1/(:any)'] = 'PickUp/GetlabelPrint4_6/$1';
$route['import_from_master'] = 'Country/import_from_master';

$route['returnLM'] = 'PickUp/returnLM';
$route['cancelOrder'] = 'PickUp/CancelOrderView';

$route['skuTransfer']       = 'ItemInventory/SkutranferaddView';
$route['skuTransferedList'] = 'ItemInventory/StockTranferedlistview';

$route['expiry_alert'] = 'ItemInventory/view_expirealert';

$route['lessqty_alert'] = 'ItemInventory/view_lessqty';

$route['packing_report'] = 'PickUp/packing_report';

$route['pickListPrint/(:any)']           = 'PickUp/pickListPrint/$1';
$route['pickListPrintA4/(:any)']           = 'PickUp/pickListPrintA4/$1';
$route['Printpicklist3PL/(:any)/(:num)'] = 'PickUp/Printpicklist3PL/$1/$2';
$route['awbPickupPrint/(:any)/(:any)']   = 'PickUp/awbPickupPrint/$1/$2';
$route['awbPickupPrint/(:any)']          = 'PickUp/awbPickupPrint/$1';
//$route['awbPickupPrint/(:any)'] = 'PickUp/awbPickupPrint/$1';
$route['awbPickupPrint/(:any)/(:any)'] = 'PickUp/awbPickupPrint/$1/$2';

$route['Printpicklist3PL_bulk/(:any)/(:any)'] = 'PickUp/Printpicklist3PL_bulk/$1/$2';

$route['add_shelve']                       = 'Shelve/add_bulk_shelve';
$route['view_shelve']                      = 'Shelve/view_shelve';
$route['picker_setting']                   = 'Users/picker_settings';
$route['edit_picker_setting/(:any)']       = 'Users/edit_picker_setting/$1';
$route['auto_assign_active/(:any)/(:num)'] = 'Users/auto_assign_active/$1/$2';

$route['shelve_sku'] = 'Shelve/shelve_sku';

$route['shelvefilter']          = 'Shelve/shelvefilter';
$route['checkShelve']           = 'Shelve/checkShelve';
$route['checkStockLocation']    = 'Shelve/checkStockLocation';
$route['generateStockLocation'] = 'Shelve/generateStockLocation';
$route['generateStock']         = 'Shelve/generateStock';


$route['showStock']             = 'Shelve/showStock';
$route['showStocklocation']     = 'Stocks/showStocklocation';
$route['showStocklocation/(:any)']     = 'Stocks/showStocklocation/$1';
$route['showStock/(:any)']      = 'Shelve/showStock/$1';
$route['stockLocationFilter']   = 'Shelve/stockLocationFilter';

//=================tods=================================//
$route['GenerateTods'] = 'Shelve/GenerateTods';
$route['generatetodsfrm']         = 'Shelve/generatetodsfrm';
$route['showtods']             = 'Shelve/showtods';
$route['showtods/(:any)']      = 'Shelve/showtods/$1'; 
//========================================================//

$route['pickup_order/(:any)']      = 'Shipment_og/pickup_order/$1';
$route['shelveSelected']            = 'Shelve/shelveSelected';
$route['add_faq']                   = 'Faq/add_faq';
$route['show_faq']                  = 'Faq/show_faq';
$route['faqupactive/(:num)/(:any)'] = 'Faq/staffactiveview/$1/$2';
$route['editfaq/(:num)']            = 'Faq/add_view/$1';
//==========================manifest===================//

$route['shownewmanifestRequest'] = 'Manifest/Getnewrequestmanifest';
$route['showpickuplist']         = 'Manifest/getpickuplistmanifest';
$route['showmenifest']           = 'Manifest/getmenifestlist';
$route['show_assignedlist']           = 'Manifest/show_assignedlist';
$route['updateManifest/(:any)']           = 'Manifest/updateManifest/$1';
$route['updateManifest_new/(:any)']           = 'Manifest/updateManifest_new/$1';
$route['manifestview/(:any)/(:any)']    = 'Manifest/getmanifestdetailsview/$1/$2';
$route['manifestdetails']        = 'Manifest/manifestListFilter';
$route['manifestListFilterSplit']        = 'Manifest/manifestListFilterSplit';

$route['split/(:any)']        = 'Manifest/split/$1';

//===================route========================//


$route['return_manifest']       = 'Manifest/return_manifest_view';


$route['show_route']        = 'RoutsManagement/show_route';
$route['add_route']        = 'RoutsManagement/add_route_view';
$route['edit_route/(:any)']        = 'RoutsManagement/add_route_view/$1';
$route['delete_route/(:any)']        = 'RoutsManagement/delete_route/$1';
//================================================//

//====================Warehouse management==================//

$route['addWarehouse']         = 'Warehouse/add_view';
$route['viewWarehouse']        = 'Warehouse/list_view';
$route['editWarehouse/(:any)'] = 'Warehouse/edit_msg_template/$1';

$route['showTicket']                = 'Ticket/showTicket';
$route['ticketdetails_view/(:any)'] = 'Ticket/ticketdetails_view/$1';
$route['showTicketview']            = 'Ticket/showTicketview';
$route['tickethistory/(:any)']      = 'Ticket/tickethistory/$1';

$route['add_storage']     = 'Storage/add_storage';
$route['view_storage']    = 'Storage/storageview';
$route['editview/(:any)'] = 'Storage/add_storage/$1';

 $route['view_damage_inventory'] = 'Reports/view_damage_inventory';
$route['editviewstorage/(:any)'] = 'Seller/add_storagecharges/$1';
$route['add_courier_company/(:any)'] = 'Seller/add_courier_company/$1';
$route['active_seller/(:num)/(:any)'] = 'Seller/active_seller/$1/$2';
$route['active_wallet/(:num)/(:any)'] = 'Seller/active_wallet/$1/$2';
$route['setStorageRate']  = 'Storage/setStorageRate';
$route['CompanyDetails'] = 'Generalsetting/CompanyDetails';
$route['defaultlist_view'] = 'Generalsetting/defaultlist_view';
$route['ShipmentLogview'] = 'CourierCompany/ShipmentLogview';
$route['ReverseShipmentLog'] = 'Generalsetting/ReverseShipmentLog';

$route['update_password'] = 'Generalsetting/update_password';
 
$route['reverse_order'] = 'ShipmentR/reverse_view';
$route['performance']                                     = 'CourierCompany/performance';
$route['performance_details/(:any)/(:any)/(:any)/(:any)'] = 'Reports/performance_details_3pl/$1/$2/$3/$4';
//$route['performance_details/(:any)/(:any)/(:any)/(:any)'] = 'CourierCompany/performance_details/$1/$2/$3/$4';

$route['report_3pl'] = 'Reports/performance_3pl';
$route['performance_details_3pl/(:any)/(:any)/(:any)/(:any)'] = 'Reports/performance_details_3pl/$1/$2/$3/$4';

//====================================================//
//====================finance manage==================//
$route['addfinancecategory']    = 'Finance/getaddviewfinancecat';
$route['viewfinancecategory']   = 'Finance/categoryView';
$route['editcatview/(:any)']    = 'Finance/getaddviewfinancecat/$1';
$route['deletecategory/(:any)'] = 'Finance/getremovecategory/$1';
$route['sellerCharges']         = 'Finance/getallsellerchargesset';
$route['viewfixrateCharges']    = 'Finance/getallfixrateCharges';
$route['storageInvoices']       = 'Finance/GetstorageTypesInvoicesView';
$route['ordersinvoiceView']     = 'Finance/GetallfinanceInvocieView';
$route['viewinvoice/(:any)']    = 'Finance/Viewinvoice/$1';
$route['viewinvoice_new/(:any)']    = 'Finance/Viewinvoice_new/$1';
$route['viewinvoice_dynamic/(:any)']    = 'Finance/ViewinvoiceDynamic/$1';
$route['viewinvoice_dynamic_new/(:any)']    = 'Finance/ViewinvoiceDynamic_new/$1';
$route['newinvoicesView']     = 'Finance/GetallNewfinanceInvocieView';
$route['invoices_dynamic']     = 'Finance/GetallNewfinanceInvocieDynamicView';
$route['PickupchargesInvocie']  = 'Finance/GetallpickupChrgesinvoices';
$route['transaction_report']    = 'Finance/transaction_report';

//====================Courier service manage==================//
$route['addCourierCompany']  = 'CourierCompany/add_view';
$route['viewCourierCompany'] = 'CourierCompany/cCompany';
$route['addZone']            = 'Zone/add_view';
$route['editZone/(:num)']    = 'Zone/add_view/$1';
$route['viewZone']           = 'Zone/list_view';
$route['addZoneCustomer']            = 'Zone/add_view_customer';
$route['editZoneCustomer/(:num)']    = 'Zone/add_view_customer/$1';
$route['viewZoneCustomer']           = 'Zone/list_view_customer';


$route['sallacityupload']            = 'country/sallacity';

$route['addZoneclient']            = 'Zones/add_view_customer';
$route['editZoneclient/(:num)']    = 'Zones/add_view_customer/$1';
$route['viewZoneclient']           = 'Zones/list_view_customer';


$route['filter_zone_by_cc']           = 'Zone/filter_zone_by_cc';
//=============================================================




$route['forwardshipments']   = 'CourierCompany/forwardshipments';
$route['forwardedshipments'] = 'CourierCompany/forwardedshipments';


$route['run_shell'] = 'Shipment/runshell';

$route['runshell_tracking'] = 'Shipment/runshell_tracking';

$route['run_shell_fixrate'] = 'Finance/run_shell_fixrate';
$route['run_shell_dynamic'] = 'Finance/run_shell_dynamic';

$route['city_list'] = 'Country/cityList';

//$route['editcatview/(:any)'] = 'Finance/getaddviewfinancecat/$1';
//$route['deletecategory/(:any)'] = 'Finance/getremovecategory/$1';
//$route['sellerCharges'] = 'Finance/getallsellerchargesset';
//$route['storageInvoices'] = 'Finance/GetstorageTypesInvoicesView';
//$route['ordersinvoiceView'] = 'Finance/GetallfinanceInvocieView';
//$route['PickupchargesInvocie'] = 'Finance/GetallpickupChrgesinvoices';

//========================Salla/Zid Order Pulling===============================//
$route['salla'] = 'Salla/add';
$route['zid/(:any)']   = 'Zid/getOrder/$1';
$route['bulk_tracking']= 'Shipment/bulk_tracking';
$route['remove_forward']= 'Shipment/forward_remove';
$route['setup_storage/(:any)']= 'Warehouse/setup_storage/$1';
$route['warehouse_storage_report']= 'Warehouse/warehouse_stprage_report';

$route['bulk_order_create']= 'Shipment_bulk/bulk_create';

 $route['dispatchtodeliver'] = 'Deliver/deliver';
//===================================================//

 $route['print_stlocation'] = 'Stocks/bulk_print_stocklocation';
$route['manifestPrint/(:any)'] = 'Manifest/manifestPrint/$1';
$route['notfound'] = 'Mynotfound';
$route['Reverse_Delivery_Station'] = 'Shipment/ReversetoDeliveryStation';
//$route['(:any)'] = "Login/$1";
//$route['auth_user'] = "Login/auth_user";
//$route['Login/home']="inventory/Login/home";
//$route['(:any)'] = "default_controller/$1";
//$route['home']="home.php";
//$route['auth_user'] = 'Login/auth_user';
// $route["about"] = 'webpages/about';
// $route['about'] = 'theWorksPlumbingController/about';
// $route['services'] = 'theWorksPlumbingController/services';
//$route['Login/auth_user']='Login/auth_user';
$route['404_override'] = '';

//$route['translate_uri_dashes'] = FALSE;
$route['bulkUploadShipment']           = 'Shipment/bulkUploadShipment';
$route['dr_status/(:num)']           = 'PackStatus/GetUpdatedamageStatus/$1';
$route['cod_update_3pl']           = 'Buk_update/cod_update_3pl';
$route['courierHealthReport']           = 'Reports/courierHealthReport';

$route['damage_inventory_history'] = 'Reports/damage_inventory_history';
$route['shelve_report_new'] = 'ItemInventory/shelve_report_new';
$route['Update_tracking_status'] = 'shipment/Update_tracking_status';
$route['createInvoice3pl']       = 'LastmileInvoice/createInvoice3pl';

$route['bulk_update_weight'] = 'Shipment/bulk_update_weight';
$route['bulk_weight_format'] = 'Excel_export/bulk_weight_format';
$route['BulkWeightUpdate'] = 'Excel_export/BulkWeightUpdate';

$route['reverseconfigration'] = 'Generalsetting/reverseconfigration';
$route['salla_push_status'] = 'Salla/sallaPushStatusManual';
$route['update_expire/(:num)']       = 'ItemInventory/update_expire/$1';
$route['update_expire_new/(:num)']       = 'StockInventory/update_expire/$1';

$route['viewCourierCompanyPartner'] ='CourierCompanyPartner/viewFpCompany'; //'CourierCompanyPartner/cCompany';
$route['viewFpCompany'] = 'CourierCompanyPartner/viewFpCompany';


$route['GenerateTorodWherehouse'] = 'Shipment/GenerateTorodWherehouse';

$route['Torodforwardshipments']   = 'Shipment/Torodforwardshipments';
$route['add_address']   = 'Shipment/add_address';
$route['torod_shipment']   = 'Shipment/torod_shipment_view';
$route['torod_order_create']   = 'Shipment/torod_order_create';
$route['bulk_forward_torod']   = 'Shipment/bulk_forward_torod';
$route['add_address']   = 'Shipment/add_address';
$route['torodLog']   = 'TorodForward/torodLog';
$route['bulk_pickup_update']   = 'Shipment/bulk_pickup_update';
$route['bulkUploadShipment']           = 'Shipment/bulkUploadShipment';

$route['cancelReverseOrder'] = 'OpenShipment/cancelReverseOrder';
$route['packing_ean']                = 'PickUp/packing_ean';
$route['packing_CPS']                = 'PickUp/packing_CPS';

$route['GenerateTorodWherehouse'] = 'Shipment/GenerateTorodWherehouse';

$route['torod_automation']   = 'TorodForward/torod_Automation';
##bof:: salla API config // 

$route['SallaDetails'] = 'Generalsetting/sallaconfigration';
$route['new_request_salla']  = 'SallaApp/new_request';
$route['rejected_request_salla'] = 'SallaApp/rejected_request';
$route['accepted_request_salla'] = 'SallaApp/accepted_request'; 

##eof:: salla API config // 
$route['order/bulk_order_create'] = 'Shipment_bulk_new/bulk_create'; 
$route['connect_seller'] = 'Diggistores/connectSellerToStore'; 
$route['show_dgstores_orders'] = 'Diggistores/showOrders'; 
$route['viewStoreSeller'] = 'Diggistores/viewStoreSeller'; 
$route['edit_connect_seller/(:num)']    = 'Diggistores/connectSellerToStore/$1';

$route['zidpendingOrder'] = 'Seller/zidpendingOrder'; 

$route['packing_serial'] = 'Reports/packing_serial';

$route['remove_reverse_order']= 'Shipment/remove_reverse_order';

// $route['ShipmentLogviewNew'] = 'CourierCompany/ShipmentLogview';

$route['packing_3pl']            = 'PickUp/packing_3pl';
$route['packing_CPS_3pl']        = 'PickUp/packing_CPS_3pl';
$route['Webhook']  = 'CourierCompany/Webhook';

$route['update_3pl_info'] = 'ShipmentUpdate/update_3pl_info';
$route['bulksms']           = 'BulkSms/bulksms';