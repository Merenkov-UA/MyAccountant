<?php
function send_ans( $ans ) {
	$ret[ 'status' ] = $ans[ 0 ] ;
	$ret[ 'descr'  ] = $ans[ 1 ] ;
	echo json_encode( $ret ) ;
	exit ;
}

if( empty( $_GET[ 'login' ] ) ) {
	send_ans( [ -1, "Нет такого логина" ] ) ;
}

// Regular expression
$reg_pattern = "~\W~i" ;  
if( preg_match (          // Поиск совпадений
		$reg_pattern ,    // Шаблон (выражение)
		$_GET[ 'login' ]  // Строка
	)
) {
	send_ans( [ -2, "Логин не может быть пустым" ] ) ; 
}

@include "../Models/user.php" ;
if( ! class_exists( "User" ) ) {
	send_ans( [ -3, "user.php ошибка загрузки" ] ) ;  
} 
try {
	$user = new User( ) ;
	if( $user->isLoginFree( $_GET[ 'login' ] ) ) {
		send_ans( [ 1, "Логин свободен" ] ) ;
	} else {
		send_ans( [ -4, "Логин занят" ] ) ;
	}
} catch( Exception $ex ) {
	send_ans( [ -5, $ex->getMessage( ) ] ) ;
}
