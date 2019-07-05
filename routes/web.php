<?php

Route::get('/', 'HomeController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/user', 'UserController@index');
Route::get('/api/users/all', 'UserController@getAllUsersApi');
Route::get('/api/users', 'UserController@getUsersApi');
Route::post('/api/user/store-update', 'UserController@storeUpdateUserApi');
Route::delete('/api/user/{user_id}/deactivate', 'UserController@deactivateSingleUserApi');
Route::get('/user/{user_id}/account', 'UserController@userAccountIndex');
Route::get('/api/user/{user_id}', 'UserController@getSingleUserApi');

Route::get('/tenancy', 'TenancyController@index');
Route::get('/api/tenancies/all', 'TenancyController@getAllTenanciesApi');
Route::get('/api/tenancies', 'TenancyController@getTenanciesApi');
Route::post('/api/tenancy/store-update', 'TenancyController@storeUpdateTenancyApi');
Route::delete('/api/tenancy/{tenancy_id}/delete', 'TenancyController@destroySingleTenancyApi');
Route::get('/api/tenancy/single/{tenancy_id}', 'TenancyController@getSingleTenancyApi');
Route::post('/api/tenancy/{tenancy_id}/upload/agreement', 'TenancyController@uploadTenancyAgreement');
Route::delete('/api/tenancy/{tenancy_id}/agreement', 'TenancyController@removeTenancyAgreement');

Route::get('/tenant', 'TenantController@index');
Route::get('/api/tenants/all', 'TenantController@getAllTenantsApi');
Route::get( '/api/tenants', 'TenantController@getTenantsApi');
Route::post('/api/tenant/store-update', 'TenantController@storeUpdateTenantApi');
Route::delete('/api/tenant/{tenant_id}/deactivate', 'TenantController@deactivateSingleTenantApi');

Route::get('/rolepermission', 'RolePermissionController@index');

Route::get('/api/permissions/all', 'RolePermissionController@getAllPermissionsApi');
Route::get('/api/permissions', 'RolePermissionController@getPermissionsApi');
Route::post('/api/permission/store-update', 'RolePermissionController@storeUpdatePermissionApi');
Route::delete('/api/permission/{permission_id}/toggle', 'RolePermissionController@toggleSinglePermissionApi');

Route::get('/api/roles/all', 'RolePermissionController@getAllRolesApi');
Route::get('/api/roles', 'RolePermissionController@getRolesApi');
Route::post('/api/role/store-update', 'RolePermissionController@storeUpdateRoleApi');
Route::delete('/api/role/{role_id}/deactivate', 'RolePermissionController@deactivateSingleRoleApi');

Route::get('/profile', 'ProfileController@index');
Route::get('/api/profiles/all', 'ProfileController@getAllProfilesApi');
Route::get('/api/profiles', 'ProfileController@getProfilesApi');
Route::post('/api/profile/store-update', 'ProfileController@storeUpdateProfileApi');
Route::delete('/api/profile/{profile_id}/deactivate', 'ProfileController@deactivateSingleProfileApi');
Route::post('/api/profile/{profile_id}/logo/upload', 'ProfileController@uploadLogoApi');

Route::get('/property', 'PropertyController@index');
Route::get('/api/properties/all', 'PropertyController@getAllPropertiesApi');
Route::get('/api/properties', 'PropertyController@getPropertiesApi');
Route::post('/api/property/store-update', 'PropertyController@storeUpdatePropertyApi');
Route::delete('/api/property/{property_id}/delete', 'PropertyController@destroySinglePropertyApi');

Route::get('/api/units/property/{property_id}', 'UnitController@getAllUnitsByPropertyIdApi');
Route::get('/unit', 'UnitController@index');
Route::get('/api/units/all', 'UnitController@getAllUnitsApi');
Route::get('/api/units', 'UnitController@getUnitsApi');
Route::post('/api/unit/store-update', 'UnitController@storeUpdateUnitApi');
Route::delete('/api/unit/{unit_id}/delete', 'UnitController@destroySingleUnitApi');
Route::get('/api/unit/service-accounts', 'UnitController@services');


Route::get('/beneficiary', 'BeneficiaryController@index');
Route::get('/api/beneficiaries/all', 'BeneficiaryController@getAllBeneficiariesApi');
Route::get('/api/beneficiaries', 'BeneficiaryController@getBeneficiariesApi');
Route::post('/api/beneficiary/store-update', 'BeneficiaryController@storeUpdateBeneficiaryApi');
Route::delete('/api/beneficiary/{beneficiary_id}/deactivate', 'BeneficiaryController@deactivateSingleBeneficiaryApi');

Route::get('/insurance', 'InsuranceController@index');
Route::get('/api/insurances/all', 'InsuranceController@getAllInsurancesApi');
Route::get('/api/insurances', 'InsuranceController@getInsurancesApi');
Route::post('/api/insurance/store-update', 'InsuranceController@storeUpdateInsuranceApi');
Route::delete('/api/insurance/{insurance_id}/deactivate', 'InsuranceController@destroySingleInsuranceApi');

Route::get('/api/propertytypes/all', 'PropertytypeController@getAllPropertytypesApi');
Route::get('/api/idtypes/all', 'IdtypeController@getAllIdtypesApi');
Route::get('/api/genders/all', 'GenderController@getAllGendersApi');
Route::get('/api/countries/all', 'CountryController@getAllCountriesApi');
Route::get('/api/races/all', 'RaceController@getAllRacesApi');

Route::get('/checkout', 'CheckoutController@index');
Route::get('/api/getcheckout', 'CheckoutController@getcheckout');
//Route::get('/createbill', 'CheckoutController@createbill');
Route::post('/api/insurance/create-bill', 'CheckoutController@createinsurance');
Route::get('/bilpllz/payinsurance-return/{token}', 'CheckoutController@returnbill');
Route::get('/success', 'CheckoutController@success');

//Arc related routes
Route::group(['prefix' => 'arc'],function(){
	Route::get('form/{token}', 'ArcController@indexArcForm')->name('arcForm');
	Route::post('create-mandate', 'ArcController@createMandate')->name('createMandate');
	Route::get('mandate-return', 'ArcController@mandateReturn');
	Route::post('mandate-callback', 'ArcController@mandateCallback');
	Route::post('collection', 'ArcController@makeCollection');
});
Route::get('/api/arc/create-send/{tenancy_id}/{channel}', 'ArcController@createAndSendArcForm');

Route::get('/api/utilityrecord/whatsapp/{tenancy_id}', 'UtilityrecordController@sendUtilityRecordFormWhatsapp');
Route::get('/utilityrecord/form/{token}', 'UtilityrecordController@getTenantUtilityRecordIndex');
Route::post('/api/utilityrecord/store-update', 'UtilityrecordController@storeUpdateUtilityrecordApi');
Route::get('/api/utilityrecords/{tenancy_id}', 'UtilityrecordController@getUtilityrecordIndexApi');
Route::delete('/api/utilityrecord/requestremove/{utilityrecord_id}', 'UtilityrecordController@requestRemoveSingleUtilityrecordApi');

Route::get('/utilityrecord', 'UtilityrecordController@getUtilityRecordIndex');
Route::get('/api/utilityrecords/all', 'UtilityrecordController@getAllProfilesApi');
Route::get('/api/utilityrecords', 'UtilityrecordController@getProfilesApi');
// Route::post('/api/utilityrecord/store-update', 'UtilityrecordController@storeUpdateProfileApi');
Route::delete('/api/utilityrecord/{utilityrecord_id}/deactivate', 'UtilityrecordController@deactivateSingleProfileApi');
Route::post('/api/utilityrecord/verifyreject/{utilityrecord_id}/{decision}', 'UtilityrecordController@approveRejectUtilityrecord');

Route::get('/invoice', 'InvoiceController@index');
Route::get('/api/invoices/all', 'InvoiceController@getAllInvoicesApi');
Route::get( '/api/invoices', 'InvoiceController@getInvoicesApi');
Route::post('/api/invoice/store-update', 'InvoiceController@storeUpdateInvoiceApi');
Route::delete('/api/invoice/{invoice_id}/deactivate', 'InvoiceController@deactivateSingleInvoiceApi');

Route::get('/collection', 'CollectionController@index');
Route::get('/api/collections', 'CollectionController@getRecurrencesApi');
