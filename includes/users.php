<?php
Flight::route( 'POST /users/add', function(){
	$db = Flight::db();

	$username = $_POST['username'];
	$password = $_POST['password'];

	$data = array(
		'username' => $username,
		'password' => md5($password)
	 );

	$id = $db->insert('users', $data);
 	if ($id)
 		Flight::redirect( 'users');
 	 else
 	     echo 'insert failed: ' . $db->getLastError();
 });

Flight::route( 'POST /users/edit', function($username){
	
	$db = Flight::db();

	$username = $_POST['username'];
	$password = $_POST['password'];


	$data = array(
		'username' => $username,
		'password' => md5($password)
	);
	$db->where('username', $username);
	$id = $db->insert('users', $data);
	if($id)
		Flight::redirect( 'users');
	else
		echo 'insert failed: ' . $db->getLastError();
});

flight::route('/users/delete/@username', function($username){
	$db = Flight::db();

	$db->where('username', $username);
	if($db->delete('users')) Flight::redirect ('users');

});

Flight::route( 'POST/users/edit/@username', function($username){
	Flight::view()->set('title', 'Edit User');
	Flight::render('edit-user', array(
		'username' => $username
		));
});
Flight::route( '/users/edit', function()
	Flight::view()->set('title', 'Add Users');
	Flight::render( 'users-app');
});

Flight::route('GET /users(/page/@page:[0-9]+)', function($page){
	Flight::view()->set('title', 'Users');

	if ( empty($page) ){
		$page = 1;
	}

	$db = Flight::db();
	$db->pageLimit = 10; // set limit per page

	$users = $db->arraybuilder()->paginate('users', $page);
    Flight::render( 'users', array(
    	'users' => $users,
    	'page' => $page,
    	'total_pages' => $db->totalPages
    ) );
});