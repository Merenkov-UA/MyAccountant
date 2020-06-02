<?php
session_start();

if(!empty($_SESSION[ 'userid' ]))
{
		@include_once "../Models/user.php";
	if(!class_exists("User"))
		echo "Ошибка подключения класса User";
	
	else{
		$users= new User();
		$user = $users->load_userById($_SESSION[ 'userid' ]);
		if($user!==true)
		{
			$users=null;
		}
		echo json_encode($user);
	}
	
}