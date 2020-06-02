<?php

class User{
    public $id;
    public $name;
    public $email;
    public $login;
    public $pass_hash;
    public $pass_salt;
    public $balance;
    

    function __construct($dbLink = null){
        if($dbLink == null){
            unset($db_type);
             if(is_readable("db_ini.php")) @include "db_ini.php";
                else if( is_readable("../db_ini.php")) @include "../db_ini.php";
            if(empty($db_type)){
                echo"Ошибка подключения";
                exit;
            }
            
            $conStr = "$db_type:host=$db_host;dbname=$db_name;charset=$db_enc;";
            
            try{
                $this->DB = new PDO($conStr, $db_user, $db_pass);
                $this->DB ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch(PDOException $ex){
                throw $ex;
            }

        }
        else{
            $this->DB = $dbLink;
        }
    }
    
    function load_from_array( $data ) {
        if( ! is_array( $data ) ) return false ;

        if( isset( $data['name']       ) ) $this->name       = $data['name'];
        if( isset( $data['email']      ) ) $this->email      = $data['email'];  
        if( isset( $data['login']      ) ) $this->login      = $data['login'];
        if( isset( $data['pass_hash']  ) ) $this->pass_hash  = $data['pass_hash'];
        if( isset( $data['pass_salt']  ) ) $this->pass_salt  = $data['pass_salt'];
        if( isset( $data['balance']    ) ) $this->balance    = $data['balance'];
        
    }

   

    function register_user( $data = null ) {
        
        if( empty( $this->DB ) ) return false ;
        
        if( is_array( $data ) ) {
            $this->load_from_array( $data ) ;
        }
        $sql = "INSERT INTO users( 
                    name, email, login, pass_hash, pass_salt, balance        )
        VALUES(       ?,      ?,      ?,         ?,      ?,     ?            )" ;
            
        $prepared = $this->DB->prepare( $sql ) ;
        
        // Вносим данные из полей объекта
        $prepared->execute( [
            
            $this->name      ,
            $this->email     ,
            $this->login     ,
            $this->pass_hash ,
            $this->pass_salt ,
            $this->balance
        ] ) ;
    }

    function isLoginFree($login){
        if(empty($login)) return false;

        $query = "SELECT COUNT(ID) FROM users
                  WHERE login = '$login'";
        $answer = $this->DB->query($query);
        $n = ($answer->fetch(PDO::FETCH_NUM))[0];
        return $n == 0;
    }

    function isAuthorized($login, $pass){
        if(empty($this->DB)) return false;
        
        $query = "SELECT * FROM users WHERE login = '$login'";
        $answer = $this->DB->query($query);

        $userdata = $answer->fetch(PDO::FETCH_ASSOC);
        if(empty($userdata)){
            return false;
        }

        if( hash( 'SHA256', $pass . $userdata['pass_salt'] ) 
            != $userdata['pass_hash']
        ) {
            return false;
        }
        
        $this->id = $userdata['id'];
        
        return true ;
    }

    function load_userById( $ID) {
        if( empty( $this->DB ) )
            return false ;
        if( empty( $ID ) ) return false ;
        
        $users = $this->DB->query( "SELECT * FROM users WHERE id = ". $ID ) ;
        
        return  $users->fetch( PDO::FETCH_ASSOC );
      
    }


function loadUserDataById( $id ) {
        if( empty( $this->DB ) ) return false ;
        if( empty( $id ) ) return false ;
        $res = $this->DB->query( 
            "SELECT * FROM users WHERE id = " . $id
        ) ;
        $row = $res->fetch( PDO::FETCH_ASSOC ) ;
        if( empty( $row ) ) return false ;
        $this->id = $id ;
       
        $this->name             = $row['name'];
        $this->email            = $row['email'];
        $this->login            = $row['login'];
        $this->balance          = $row['balance'];
        
        return true ;
    }

    function update_user_balance(  $balance, $Id) {
       
        $prepared_sql = "UPDATE users SET
            balance         = ? 
            WHERE id = ?
        " ;
        $prepared_que = $this->DB->prepare( $prepared_sql ) ;
        $prepared_que->execute( [ 
            $balance   ,
            $Id
        ] ) ;
        return true ;
    }

};