<?php
 session_start( ) ;

@include_once "../Models/record.php" ;
	if( ! class_exists( "Record" ) ) echo "Ошибка подключения класса Record" ;
	else {
		$records = new Record( ) ;
}

$test = $records->getAllRecordsById($_SESSION[ 'userid' ]);

 echo json_encode($test); 










 

?>