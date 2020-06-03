<?php

$data = array('success' => false, 'messages' => array());


@include_once "../Models/user.php";
	if( ! class_exists( "User" ) ) echo "Ошибка подключения класса Record" ;
	$users = new User();
	 $users->loadUserDataById($_POST['authorId']);


	@include_once "../Models/record.php" ;
	if( ! class_exists( "Record" ) ) echo "Ошибка подключения класса Record" ;
		$records = new Record( ) ;
		
		
if($_POST['editOperationOld'] === "spending")
	{
		if($_POST['editAmount'] > $_POST['oldAmount'])
		{
			if(($user->balance - ($_POST['editAmount'] - $_POST['oldAmount'])) > 0){
				$users->update_user_balance(($users->balance - ($_POST['editAmount'] - $_POST['oldAmount'])),  $_POST['authorId']);
			}
		}
		else
			$users->update_user_balance(($users->balance + ($_POST['oldAmount'] - $_POST['editAmount'])  ),  $_POST['authorId']);
		
	}
	else{
		if($_POST['editAmount'] > $_POST['oldAmount'])
		{
			$users->update_user_balance(($users->balance + ($_POST['editAmount'] - $_POST['oldAmount'])),  $_POST['authorId']);
		}
		else
			$users->update_user_balance(($users->balance - ($_POST['oldAmount'] - $_POST['editAmount'])  ),  $_POST['authorId']);
	}

	$query = $records->update_record($_POST['editDescription'], $_POST['editAmount'], $_POST['editId']) ;

	if($query === true){
			$data['success'] = true;
			$data['messages'] = "Запись успешно обновлена ";
		}else{
			$data['success'] = false;
			$data['messages'] = "Ошибка обновления записи";
		}
		
 
print json_encode($data); 



