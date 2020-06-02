<?php  
 session_start( ) ;

 if( isset( $_GET[ 'logout' ] ) ) {
	unset( $_SESSION[ 'userid' ] ) ;
	header( "Location: index.php" ) ;
	exit ;
}

if(!empty($_SESSION[ 'userid' ]))
{
		@include_once "Models/user.php";
	if(!class_exists("User"))
		echo "Ошибка подключения класса User";
	
	else{
		$users= new User();
		$res = $users->loadUserDataById( $_SESSION[ 'userid' ] ) ;
		$user = $users->load_userById($_SESSION[ 'userid' ]);
		if($res!==true)
		{
			$users=null;
		}
	}
}
	@include_once "Models/record.php" ;
	if( ! class_exists( "Record" ) ) 
		echo "Ошибка подключения класса Record" ;
	else {
		$records = new Record( ) ;
		if(!empty($users))
		$all_records = $records->get_all_recordsById( $_SESSION[ 'userid' ] ) ;
	else{
		$all_records['operation'] = "";
		$all_records['description'] = "";
		$all_records['amount'] = "";
	}

	include "Views/index_view.php";
	exit ;
}

