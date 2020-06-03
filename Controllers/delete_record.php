<?php 
session_start();

$recordId = $_POST['recordId'];
$query='';
$data = array('success' => false, 'messages' => array());

@include_once "../Models/record.php" ;
	if( ! class_exists( "Record" ) ) echo "Ошибка подключения класса Record" ;

		$records = new Record( ) ;
		$record = $records->load_by_id($recordId);

	@include_once "../Models/user.php";
	if( ! class_exists( "User" ) ) echo "Ошибка подключения класса Record" ;
	$users = new User();
	

	 $users->loadUserDataById($record['id_author']);


	if($record['operation'] === "spending")
	{
		
		$users->update_user_balance(($users->balance + $record['amount']),  $record['id_author']);
		$query = $records->deleteRecordById($_POST['recordId']) ;
		
	}
	else if($record['operation'] === "profit"){
		if(($users->balance - $record['amount']) > 0)
		{
			$users -> update_user_balance(($users->balance - $record['amount']),  $_SESSION[ 'userid' ]);
			$query = $records->deleteRecordById($_POST['recordId']) ;
		}
		else $query = false;
		
	}

//$query = $records->deleteRecordById($_POST['recordId']) ;

	if($query === true){
			$data['success'] = true;
			$data['messages'] = "Успешно удалена запись";
		}else{
			$data['success'] = false;
			$data['messages'] = "Ошибка удаления записи";
		}
		

 
print json_encode($data); 

