
<?php

function view( ) {
	global $msg ;
	include "../Views/reg_view.php" ;
	exit ;
}

session_start( ) ;
if( ! empty( $_POST ) ) {	
	$msg = "" ;
	if( empty( $_POST[ 'login' ] ) ) {
		$msg = "Логин не может быть пустым" ;
		view( ) ;
	} else $_SESSION[ 'login' ] = $_POST[ 'login' ] ;
			
	if( empty( $_POST[ 'name' ] ) ) {
		$msg = "Имя не может быть пустым" ;
		view( ) ;
	} else $_SESSION[ 'name' ]  = $_POST[ 'name' ]  ;

	if( empty( $_POST[ 'balance' ] ) ) {
		$msg = "Баланс денежных средств не может быть пустым" ;
		view( ) ;
	} else $_SESSION[ 'balance' ]  = $_POST[ 'balance' ]  ;
	
	
	if( empty( $_POST[ 'pass' ] ) ) {
		$msg = "Пароль не может быть пустым" ;
		view( ) ;
	} else if( strlen( $_POST[ 'pass' ] ) < 5 ) {
		$msg = "Пароль слишком короткий (5 символов как минимум)" ;
		view( ) ;
	} else if( ! preg_match( "~\d~", $_POST[ 'pass' ] ) ) {
		$msg = "Пароль должен содержать цифру" ;
		view( ) ;
	} else if( ! preg_match( "~\D~", $_POST[ 'pass' ] ) ) {
		$msg = "Пароль не должен состоять только из цифр" ;
		view( ) ;
	} else if( ! preg_match( "~^.*\W.*$~", $_POST[ 'pass' ] ) ) {
		$msg = "Пароль должен содержать спецсимвол (!\"№;%:)" ;
		view( ) ;
	}
	
	if( $_POST[ 'pass' ] !== $_POST[ 'pass2' ] ) {
		$msg = "Пароли не совпадают" ;
		view( ) ;
	}
	
	if( empty( $_POST[ 'email' ] ) ) {
		$msg = "Укажите эл. почту" ;
		view( ) ;
	} else {
		$_SESSION[ 'email' ] = $_POST[ 'email' ] ;
		if( ! preg_match( "~^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$~", $_POST[ 'email' ] ) ) {
			$msg = "Укажите валидную эл. почту" ;
			view( ) ;
		}
	}
	
	@include "../Models/user.php" ;

	if( ! class_exists( "User" ) ) {
		$msg = "User.php load error" ;
		view( ) ;
	} 

	try {
		$user = new User( ) ;
		$login_free = $user->isLoginFree( $_POST[ 'login' ] ) ;
	} catch( Exception $ex ) {
		$msg = $ex->getMessage( ) ;
		view( ) ;
	}
	
	if( ! $login_free ) {
		$msg = "Логин уже используется другим пользователем" ;
		view( ) ;
	}
	
// echo "<pre>";print_r($_FILES); exit;	

	
	
	if( empty( $msg ) ) {
		// echo "Данные приняты, хеш пароля " . hash( 'SHA256', $_POST[ 'pass' ] ) ;
		$salt = md5( rand( ) ) ;
		$pass = hash( 
			'SHA256', 
			$_POST[ 'pass' ] . $salt 
		) ;
	
		$user_data = [
			
			'name'       => $_POST[ 'name' ],
			'email'      => $_POST[ 'email' ],
			'login'      => $_POST[ 'login' ],
			'pass_hash'  => $pass,
			'pass_salt'  => $salt,
			'balance'    => $_POST['balance']
			
			
		] ;
		
		try {
			$user->register_user( $user_data ) ;
		} catch( Exception $ex ) {
			$msg = $ex->getMessage( ) ;
			view( ) ;
		}
		
		echo "<script>setInterval(
							()=>{
						var v=countdown.innerText-1;
						if(v<0)window.location='../index.php';
						else countdown.innerText=v
					},
					1000
				)</script>
				<div class='container' style='text-align:center;'><h1>Данные приняты, через несколько секунд вы окажетесь на главной странице</h1>
				<p id='countdown'>3</p></div>" ;
		
		
		session_unset( ) ;
		exit ;
	} 
}else {
	session_unset( ) ;
	view( ) ;
}