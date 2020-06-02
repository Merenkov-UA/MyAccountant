<?php

session_start( ) ;
$amount = 0;
$description = "";
$operation = "";
$msg = [];
$add_ok = false ;

@include_once "../Models/user.php" ;
if( ! class_exists( "User" ) ) 
	$msg[] = "Ошибка подключения класса Record" ;
else {

	try {
		$user = new User( ) ;
		 $user->loadUserDataById( $_SESSION[ 'userid' ] ) ;
			 
	} catch( Exception $ex ) {echo $ex->getMessage( )  ;}
	



@include_once "../Models/record.php" ;
if( ! class_exists( "Record" ) ) 
	$msg[] = "Ошибка подключения класса Record" ;
else {
$record = new Record( ) ;




if( ! empty( $_POST ) ) {
	// Валидация данных
	if( empty( $_POST[ 'amount' ] ) ) {
		$msg[] = "Необходимо указать сумму" ;
	}
	if( empty( $_POST[ 'description' ] ) ) {
		$msg[] = "Необходимо указать описание" ;
	}
	if( empty( $_POST[ 'operation' ] ) ) {
		$msg[] = "Необходимо указать  операцию" ;
	}
	if( empty($_SESSION[ 'userid' ]) ) {
		$msg[] = "Необходимо указать автора" ;
	}

	if($_POST[ 'operation' ] === "spending")
	{
		if(($user->balance - $_POST['amount']) > 0){
			$user -> update_user_balance(($user->balance - $_POST['amount']),  $_SESSION[ 'userid' ]);
		}
		else $msg[] = "Не хватает суммы для траты.";
	}
	else{
		$user -> update_user_balance(($user->balance + $_POST['amount']),  $_SESSION[ 'userid' ]);
	}
	
	if( ! empty( $msg ) ) {
		$amount       = $_POST[ 'amount' ]      ;
		$description  = $_POST[ 'description' ] ;
		$operation    = $_POST[ 'operation' ]   ;

		
	} else {
		
			$record->load_from_array( [
				'amount'          => $_POST[ 'amount' ]       ,
				'description'    => $_POST[ 'description' ] ,
				'operation'      => $_POST['operation']     ,
				'dt_create'      => date( "Y-m-d H:i:s" )   ,
				'id_author'      =>   $_SESSION[ 'userid' ]                 
				
			] ) ;

			try {
				$record->add_to_db( ) ;
				$add_ok = true ;
			} catch( Exception $ex ) {
				$msg[] = "Ошибка добавления записи: " . $ex->getMessage( ) ;
			}
		
	}
	 
	
	}

}
}
if(empty($msg))
	echo "Добавлено успешно";
else echo "Проверьте правильность заполнения полей";
