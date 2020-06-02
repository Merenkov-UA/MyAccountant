<?php

$msg = "" ;
	
if( ! empty( $_POST ) ) {
	$login = $_POST[ 'login' ] ;
	$pass  = $_POST[ 'password'  ] ;
	
	if( empty( $login ) ) $msg = "Login is necessary" ;
	if( empty( $pass  ) ) $msg = "Password is necessary" ;
	
	if( empty( $msg ) ) {
		@include "../Models/user.php" ;
		if( ! class_exists( "User" ) ) {
			$msg = "User.php load error" ;
		} else {
			try {
				$user = new User( ) ;
				session_start( ) ;
				if( $user->isAuthorized( $login, $pass ) ) {
					$_SESSION[ 'userid' ] = $user->id ;
					
					
					
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
			exit;
				} else {
					unset( $_SESSION[ 'userid' ] ) ;
					$msg = "Incorrect auth data" ;
				}
			} catch( Exception $ex ) {
				$msg = $ex->getMessage( ) ;
			}
		}
	}
} else {
	
}

include "../Views/authorization_view.php" ;
