<?php 


$recordId = $_POST['recordId'];
$query='';
$data = array('success' => false, 'messages' => array());

	@include_once "../Models/record.php" ;
	if( ! class_exists( "Record" ) ) echo "Ошибка подключения класса Record" ;
	
		$records = new Record( ) ;
		
			$query = $records->deleteRecordById($_POST['recordId']) ;
		


	if($query === true){
			$data['success'] = true;
			$data['messages'] = "Успешно удалена запись";
		}else{
			$data['success'] = false;
			$data['messages'] = "Ошибка удаления записи";
		}
		




 
print json_encode($data); 

