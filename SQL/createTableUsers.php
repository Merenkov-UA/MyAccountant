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
echo "Успешно вошли <br/><br/>"; //Вывод строки в случае успешного подключения к базе.

//Блок создание таблицы пользователей при условии её отсутствия
//Строка запроса.
$query=<<<SQL
CREATE TABLE  IF NOT EXISTS Users(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
name VARCHAR(32),
email VARCHAR(32),
login VARCHAR(32),
pass_hash CHAR(64),
pass_salt CHAR(32),
balance FLOAT(32)
) engine=InnoDB default charset = utf8 collate=utf8_general_ci

SQL;


//Выполнение запроса.
try{ $DB->query($query);
}catch(Exception $ex)
{
	echo  $ex->getMessage(),"<br/>",$query;
	exit;
}
echo "<br>Запрос выполнен";  //Выводит строку в случае успешного добавления таблицы.