<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::resource('auth','authController');
Route::resource('logout','authController@logout');

Route::group(['middleware' => 'auth'], function (){

	Route::resource('/','dashboardController');

	//CONCEPTS GROUPS
	Route::resource('concepts_groups','concept_groupController');
	
	Route::get('/concepts_groups/trash/trash', ['uses' =>'concept_groupController@trash']);
	Route::get('/concepts_groups/{id}/inactive', ['uses' =>'concept_groupController@inactive']);
	Route::get('/concepts_groups/{id}/untrashed', ['uses' =>'concept_groupController@untrashed']);

	//CONCEPTS
	Route::resource('concepts','conceptController');
	Route::get('/concepts/trash/trash', ['uses' =>'conceptController@trash']);
	Route::get('/concepts/{id}/inactive', ['uses' =>'conceptController@inactive']);
	Route::get('/concepts/{id}/untrashed', ['uses' =>'conceptController@untrashed']);

	Route::get('/concepts/{id}/add', ['uses' =>'conceptController@list_groups']);
	Route::get('/concepts/{id}/add_elements', ['uses' =>'conceptController@add_elements']);
	Route::post('/concepts/add_store/add_store', ['as'=>'concepts.add_store', 'uses' => 'conceptController@add_store']);
	Route::get('/concepts/{id_concept}/{id_group}/add_inactive', ['uses' =>'conceptController@add_inactive']);

	//DISCOUNTS	
	Route::resource('discounts','discountController');
	Route::get('/discounts/trash/trash', ['uses' =>'discountController@trash']);
	Route::get('/discounts/{id}/inactive', ['uses' =>'discountController@inactive']);
	Route::get('/discounts/{id}/untrashed', ['uses' =>'discountController@untrashed']);

	Route::get('/discounts/{id}/add', ['uses' =>'discountController@list_groups']);
	Route::get('/discounts/{id}/add_elements', ['uses' =>'discountController@add_elements']);
	Route::post('/discounts/add_store/add_store', ['as'=>'discounts.add_store', 'uses' => 'discountController@add_store']);
	Route::get('/discounts/{id_concept}/{id_group}/add_inactive', ['uses' =>'discountController@add_inactive']);
	
	//INTEREST
	Route::resource('interests','interestController');
	Route::get('/interests/trash/trash', ['uses' =>'interestController@trash']);
	Route::get('/interests/{id}/inactive', ['uses' =>'interestController@inactive']);
	Route::get('/interests/{id}/untrashed', ['uses' =>'interestController@untrashed']);

	Route::get('/interests/{id}/add', ['uses' =>'interestController@list_groups']);
	Route::get('/interests/{id}/add_elements', ['uses' =>'interestController@add_elements']);
	Route::post('/interests/add_store/add_store', ['as'=>'interests.add_store', 'uses' => 'interestController@add_store']);
	Route::get('/interests/{id_concept}/{id_group}/add_inactive', ['uses' =>'interestController@add_inactive']);
	
	//PAYMENTS
	
	Route::get('/showListStudents/', ['uses' =>'paymentController@showListStudents']);
	Route::get('/getDebsList/{id_Student}', ['uses' => 'paymentController@showDebts']);
	Route::post('/showReceiptConsole/', ['as'=>'payments.showReceiptConsole', 'uses' => 'paymentController@showReceiptConsole']);
	Route::post('/makePayment/', ['as'=>'payments.makePayment', 'uses' => 'paymentController@makePayment']);
	Route::get('/printPaymentDocument/{id_document_md5}', ['uses' => 'paymentController@printPaymentDocument']);
	Route::get('/printPaymentDocument_test/{id_document_md5}', ['uses' => 'paymentController@printPaymentDocument_test']);	
		//Create Payment Document
	Route::get('/createPaymentDocument/', ['uses' =>'paymentController@createPaymentDocument']);
	Route::get('/getDebsListWithOutDateLimit/{id}', ['uses' =>'paymentController@getDebsListWithOutDateLimit']);
		//update Payments
	Route::get('/updatePayments/showClassrooms', ['uses' =>'paymentController@showClassrooms']);
	Route::post('/updatePayments/ShowStudentsDebts', ['as'=>'updatePayments.ShowStudentsDebts', 'uses' => 'paymentController@ShowStudentsDebts']);
	Route::post('/updatePayments/SaveStudentsDebts', ['as'=>'updatePayments.SaveStudentsDebts', 'uses' => 'paymentController@SaveStudentsDebts']);


	//SCHEDULES
	/*Route::resource('schedules','scheduleController');
	Route::get('/schedules/trash/trash', ['uses' =>'scheduleController@trash']);
	Route::get('/schedules/{id}/inactive', ['uses' =>'scheduleController@inactive']);
	Route::get('/schedules/{id}/untrashed', ['uses' =>'scheduleController@untrashed']);*/

	Route::get('/execute_schedule', ['uses' =>'scheduleController@execute_schedule']);
	Route::get('/recompute', ['uses' =>'scheduleController@recompute']);
	Route::get('/apply_concept_to_new_group_test', ['uses' =>'scheduleController@apply_concept_to_new_group_test']);

	//Infraestructure

	//Students
	Route::resource('students','studentController');
	//Route::get('/students/all/all', ['uses' =>'studentController@all_students']);
	//oute::get('/students/bulcked/bulcked', ['uses' =>'studentController@bulcked_students']);
	Route::get('/students/trash/trash', ['uses' =>'studentController@trash']);
	Route::get('/students/{id}/inactive', ['uses' =>'studentController@inactive']);
	Route::get('/students/{id}/untrashed', ['uses' =>'studentController@untrashed']);
		//add image to student
	Route::post('/students/picture/', ['as'=>'students.picture', 'uses' => 'studentController@picture']);
	Route::post('/students/bulck_search_repeted/', ['as'=>'students.bulck_search_repeted', 'uses' => 'studentController@bulck_search_repeted']);
	Route::post('/students/add_element/add_element', ['as'=>'students.add_element', 'uses' => 'studentController@add_element_store']);
		//Enroller fast
	Route::get('/students/enrolling_fast/enrolling_fast', ['uses' =>'studentController@enrolling_fast']);
	Route::post('/students/enrolling_fast/{id}/add_student/', ['as'=>'students.enrolling_fast', 'uses' => 'studentController@add_student']);
	Route::post('/students/enrolling_fast/store/', ['as'=>'students.enrolling_fast_store', 'uses' => 'studentController@add_student_store']);
		//bulck studen from file
	Route::get('/students/bulck/bulck', ['uses' =>'studentController@bulck_load']);
	Route::post('/students/bulck_store/', ['as'=>'students.bulck_store', 'uses' => 'studentController@bulck_store']);

	//Parents
	Route::resource('family_members','family_memberController');
	Route::get('/family_members/trash/trash', ['uses' =>'family_memberController@trash']);
	Route::get('/family_members/{id}/inactive', ['uses' =>'family_memberController@inactive']);
	Route::get('/family_members/{id}/untrashed', ['uses' =>'family_memberController@untrashed']);
		//Pictures
	Route::post('/family_members/picture/', ['as'=>'families_members.picture', 'uses' => 'family_memberController@picture']);
		//Enroller fast
	Route::get('/family_members/{id}/add_elements/', ['uses' => 'family_memberController@add']);
	Route::post('/family_members/add/store/', ['as'=>'family_members.add_store', 'uses' => 'family_memberController@add_store']);
	Route::get('/family_members/{id}/add_inactive/', ['uses' => 'family_memberController@add_inactive']);
	Route::get('/family_members/{id}/list/', ['uses' => 'family_memberController@add_list']);

	//Groups
	Route::resource('groups','groupController');
	Route::get('/groups/trash/trash', ['uses' =>'groupController@trash']);
	Route::get('/groups/{id}/inactive', ['uses' =>'groupController@inactive']);
	Route::get('/groups/{id}/untrashed', ['uses' =>'groupController@untrashed']);
	
	Route::get('/groups/{id}/add', ['uses' =>'groupController@list_groups']);
	Route::get('/groups/{id}/add_elements', ['uses' =>'groupController@add_elements']);
	Route::get('/groups/{id}/add_elements_students', ['uses' =>'groupController@add_elements_students']);
	Route::get('/groups/{id}/add_elements_groups', ['uses' =>'groupController@add_elements_groups']);
	Route::post('/groups/add_store_students/add_store_students', ['as'=>'groups.add_store_students', 'uses' => 'groupController@add_store_students']);
	Route::get('/groups/{id_group}/{id_student}/add_inactive', ['uses' =>'groupController@add_inactive']);


	Route::resource('classrooms','classroomController');
	Route::get('/classrooms/trash/trash', ['uses' =>'classroomController@trash']);
	Route::get('/classrooms/{id}/inactive', ['uses' =>'classroomController@inactive']);
	Route::get('/classrooms/{id}/untrashed', ['uses' =>'classroomController@untrashed']);
	Route::get('/classrooms/bulck/bulck', ['uses' =>'classroomController@bulck_load']);

	Route::get('/classrooms/{id}/add', ['uses' =>'classroomController@list_student']);

	//REPORTS
	Route::get('/reports/consolidatedDebtReportGet', ['uses' =>'reportController@consolidatedDebtReportGet']);
	Route::post('/groups/consolidatedDebtReport', ['as'=>'reports.consolidatedDebtReport', 'uses' => 'reportController@consolidatedDebtReport']);
	Route::get('/reports/paymentsByDatesReportGet', ['uses' =>'reportController@paymentsByDatesReportGet']);
	Route::post('/groups/paymentsByDatesReport', ['as'=>'reports.paymentsByDatesReport', 'uses' => 'reportController@paymentsByDatesReport']);
	

	//TESTS
	Route::get('/Tests/CreatePaymentDocument/BasicDates_01', ['uses' =>'generatorController@BasicDates_01']);
	Route::post('/Test/createPaymentDocument/GeneratePayments_02', ['as'=>'Test.createPaymentDocument.GeneratePayments_02', 'uses' => 'generatorController@CreateDocs_02']);
});

/*Event::listen('illuminate.query',function($query){
    var_dump($query);
});*/


//TESTS

Route::get('/createStudents/{num_students}', ['uses' =>'testsController@createStudents']);
Route::get('/test_payment/{year}/{amount}/{date_ini}/{date_end}', ['uses' =>'generatorController@create_docs']);

Route::get('/apply_discount_to_group/{id_discount}/{id_group}/{flag}', ['uses' =>'scheduleController@apply_discount_to_group']);

Route::get('/white', ['uses' =>'testsController@white']);

?>