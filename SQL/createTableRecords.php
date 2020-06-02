<?php
//Блок подключение к базе данных
	unset($db_type);
	@include "../db_ini.php";
	if(empty($db_type)){
		echo "Config load error";
		exit;
	}
	$conStr = "$db_type:host=$db_host;dbname=$db_name;charset=$db_enc;";
try{
	$DB=new PDO($conStr, $db_user, $db_pass);
	$DB ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $ex){
	echo"CONNECTION ERROR:",$ex->getMessage();
	exit;
}
echo "Успешно вошли <br/><br/>"; //Вывод строки в слувае успешного подключения к базе.

//Блок создание таблицы записей при условии её отсутствия
//Строка запроса.
$query=<<<SQL
CREATE TABLE  IF NOT EXISTS records(

id            INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
description   VARCHAR(128),
amount        FLOAT(10),
operation 	  VARCHAR(16),
dt_create     DATETIME DEFAULT CURRENT_TIMESTAMP,
dt_edit       DATETIME,
id_author     INT


) engine=InnoDB default charset = utf8 collate=utf8_general_ci
SQL;

//Выполнение запроса.
try{
	$DB->query($query);
}catch(Exception $ex)
{
	echo  $ex->getMessage(),"<BR>",$query;
	exit;
}
echo "<br>Запрос выполнен"; //Выводит строку в случае успешного добавления таблицы.