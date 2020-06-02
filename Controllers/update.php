<?php

$data = array('success' => false, 'messages' => array());


	@include_once "../Models/record.php" ;
	if( ! class_exists( "Record" ) ) echo "Ошибка подключения класса Record" ;
		$records = new Record( ) ;
		$query = $records->update_record($_POST['editDescription'], $_POST['editOperation'], $_POST['editAmount'], $_POST['editId']) ;
		


	if($query === true){
			$data['success'] = true;
			$data['messages'] = "Запись успешно обновлена ";
		}else{
			$data['success'] = false;
			$data['messages'] = "Ошибка обновления записи";
		}
		
 
print json_encode($data); 



