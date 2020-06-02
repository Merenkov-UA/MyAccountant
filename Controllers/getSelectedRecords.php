<?php

$recordId = $_POST['recordId'];

@include_once "../Models/record.php" ;
	if( ! class_exists( "Record" ) ) echo "Ошибка подключения класса Record" ;
	else {
		$records = new Record( ) ;
		
}
$test = $records->load_by_id($recordId);

 echo json_encode($test); 

?>