<?php
class Record{
	public $id            ;
	public $description   ;
	public $amount      ;
	public $operation	  ;
	public $dt_create     ;
	public $dt_edit       ;
	public $id_author     ;
	private $DB ;


	function __construct( $data = null ) {		
		if(is_readable("db_ini.php")) @include "db_ini.php";
                else if( is_readable("../db_ini.php")) @include "../db_ini.php";
		if( empty( $db_type ) ) {
			throw new Exception( "Ошибка подключения к базе" ) ;
		}
		
		$conStr = "$db_type:host=$db_host;dbname=$db_name;charset=$db_enc;";
		
		try{
			$this->DB = new PDO( $conStr, $db_user, $db_pass ) ;
			$this->DB ->setAttribute(
				PDO::ATTR_ERRMODE, 
				PDO::ERRMODE_EXCEPTION
			) ;
		}
		catch( PDOException $ex ) {
			throw $ex;
		}
		
		if( is_array( $data ) ) {
			$this->load_from_array( $data ) ;
		}
	}

	function deleteRecordById($ID){
		if( empty( $this->DB ) ) return false ;
		$sql = "DELETE FROM records WHERE id=$ID";
		$query = $this->DB->prepare($sql);

		if($query->execute()){
                return true;
            }
            return false;
	}

	function update_record( $description, $operation, $amount, $Id) {
		if( empty( $this->DB ) ) return false ;
		
			$sql = "UPDATE records SET description ='$description', operation = '$operation', amount ='$amount', dt_edit = CURRENT_TIMESTAMP
			WHERE id =".$Id;
		$query = $this->DB->prepare($sql);

		return $query->execute();
	}

	function load_by_id( $ID ) {
		if( empty( $this->DB ) )
			return false ;
		$data = $this
			->DB
			->query( "SELECT * FROM records WHERE id = $ID" )
			->fetch( PDO::FETCH_ASSOC ) ;
		if( empty( $data ) )
			return false ;

		return $data ;
	}

	function get_all_records( ) {
		if( empty( $this->DB ) )
			return false ;
		
		$all_records = $this->DB->query( "SELECT * FROM records" ) ;
		$ret = [] ;
		while( $records = $all_records->fetch( PDO::FETCH_ASSOC ) ) {
			$ret[] = $records ;
		}
		return $ret ;
	}

	function get_all_recordsById( $authorID) {
		if( empty( $this->DB ) )
			return false ;
		
		$all_records = $this->DB->query( "SELECT * FROM records WHERE id_author = $authorID" ) ;
		$ret = [] ;
		while( $records = $all_records->fetch( PDO::FETCH_ASSOC ) ) {
			$ret[] = $records ;
		}
		return $ret ;
	}


	function getAllRecordsById( $authorID) {
		if( empty( $this->DB ) )
			return false ;
		$ret = array('data' => array());

		$sql = "SELECT * FROM records WHERE id_author =" . $authorID  ;
		$query = $this->DB->query($sql);

		$x = 1;
		while( $records = $query-> fetch(PDO::FETCH_ASSOC) ) {
			$operation = '';
			$dateEdit = '';
			if($records['operation'] == 'profit'){
				$operation = 'Доход';
			}else{
				$operation = 'Затраты';
			}

			if($records['dt_edit'] == null){
				$dateEdit = 'Не редактировалась';
			}else{
				$dateEdit = $records['dt_edit'];
			}


			$actionButton = '<div class="btn-group">
								<button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown"
									aria-haspopup="true" aria-expanded="false">
									Действие
									 <span class="caret"></span>
									 </button>
								<ul class="dropdown-menu">
								<li><a type="button" data-toggle="modal" data-target="#editRecordModal" onclick="editRecord('.$records['id'].')"><span class="fa fa-pencil" aria-hidden="true">Изменить</span></a></li>
								<li><a type="button" data-toggle="modal" data-target="#deleteRecordModal" onclick="deleteRecord('.$records['id'].')"><span class="fa fa-trash" aria-hidden="true">Удалить</span></a></li>
								</ul>
								</div>';
			$ret['data'][] = array(
				$x,
				$records['description'],
				$operation,
				$records['amount'],
				$records['dt_create'],
				$dateEdit,
				$actionButton

			);
			$x++;
		}
		return $ret ;
	}

	
	function load_all_records( ) {
		if( empty( $this->DB ) )
			return false ;
		
		$all_records = $this->DB->query( "SELECT * FROM records" ) ;
		$ret = [] ;
		while( $records = $all_records->fetch( PDO::FETCH_ASSOC ) ) {
			$ret[] = $records ;
		}
		return $ret ;
	}

	function __dump() {
		return 
			'id'            . ' : ' . ($this->id            ?? '--' ) . '<br>' .
			'description'   . ' : ' . ($this->description   ?? '--' ) . '<br>' .
			'amount'        . ' : ' . ($this->amount        ?? '--' ) . '<br>' .
			'operation'     . ' : ' . ($this->operation	    ?? '--' ) . '<br>' .
			'dt_create'     . ' : ' . ($this->dt_create     ?? '--' ) . '<br>' .
			'dt_edit'       . ' : ' . ($this->dt_edit       ?? '--' ) . '<br>' .
			'id_author'     . ' : ' . ($this->id_author     ?? '--' ) . '<br>' 
		;
	}
	function add_to_db( $data = null ) {
		if( empty( $this->DB ) )
			return false ;
		
		if( is_array( $data ) ) 
			$this->load_from_array( $data ) ;
			
		$prepared_sql = "INSERT INTO records
				( description, amount, operation, dt_create,     id_author)
		VALUES(		?,          ?,          ?,     CURRENT_TIMESTAMP ,  ? )
		" ;
		$prepared_que = $this->DB->prepare( $prepared_sql ) ;
		$prepared_que->execute( [ 
			$this->description   ,
			$this->amount    	 ,
			$this->operation 	 ,
			$this->id_author
			
		] ) ;
		return true ;
	}

	function load_from_array( $data ) {
		if( isset( $data[ 'id'             ] ) ) $this->id             = $data[ 'id'           ];
		if( isset( $data[ 'amount'         ] ) ) $this->amount         = $data[ 'amount'     ];
		if( isset( $data[ 'description'    ] ) ) $this->description    = $data[ 'description'  ];
		if( isset( $data[ 'operation'      ] ) ) $this->operation      = $data[ 'operation'    ];
		if( isset( $data[ 'dt_create'      ] ) ) $this->dt_create      = $data[ 'dt_create'    ];
		if( isset( $data[ 'dt_edit'        ] ) ) $this->dt_edit        = $data[ 'dt_edit'      ];
		if( isset( $data[ 'id_author'      ] ) ) $this->id_author      = $data[ 'id_author'    ];
		
	}
}


