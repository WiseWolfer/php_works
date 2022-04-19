<?php
$FIO_Client = filter_var(trim($_POST['FIO_Client']),
FILTER_SANITIZE_STRING);
$Choice = filter_var(trim($_POST['Choice']),
FILTER_SANITIZE_STRING);
var_dump($_POST);

$mysqli = new mysqli('localhost','root','','upgrade_pc');
if($mysqli->connect_error){
    die("Connection error");
}
else{
    echo "\n Ошибок нет";
}

   //поиск клиента по таблице
	$empty_val = "";
	if ($FIO_Client == $empty_val){
		die("Вы ничего не ввели!");
	}
	$result_client_FIO_check = $mysqli->query("SELECT `FIO_Client` FROM `clients` WHERE `FIO_Client` = '$FIO_Client' ");
	$client_FIO_check = current($result_client_FIO_check -> fetch_array());
		if ($client_FIO_check == $empty_val){
		die("Такого ФИО нет в таблице!!!");
	}
	$result_client_Number_of_client = $mysqli->query("SELECT `Number_of_client` FROM `clients` WHERE `FIO_Client` = '$FIO_Client' ");
	$Number_of_client = current($result_client_Number_of_client -> fetch_array());
	$result_client_Phone_number = $mysqli->query("SELECT `Phone_number` FROM `clients` WHERE `FIO_Client` = '$FIO_Client' ");
	$Phone_number = current($result_client_Phone_number -> fetch_array());
	$result_client_Address = $mysqli->query("SELECT `Address` FROM `clients` WHERE `FIO_Client` = '$FIO_Client' ");
	$Address = current($result_client_Address  -> fetch_array());
	
	$result_Number_of_order = $mysqli->query("SELECT `Number_of_order` FROM `upgrade_order` WHERE `Number_of_client` = '$Number_of_client'");
	$Number_of_order = current($result_Number_of_order->fetch_array());
	$result_Cost_of_order = $mysqli->query("SELECT `Cost_of_order` FROM `upgrade_order` WHERE `Number_of_client` = '$Number_of_client'");
	$Cost_of_order = current($result_Cost_of_order->fetch_array());
	$result_Urgent_repairs = $mysqli->query("SELECT `Urgent_repairs` FROM `upgrade_order` WHERE `Number_of_client` = '$Number_of_client'");
	$Urgent_repairs = current($result_Urgent_repairs->fetch_array());
	$result_Number_busses = $mysqli->query("SELECT `Number_busses` FROM `upgrade_order` WHERE `Number_of_client` = '$Number_of_client'");
	$Number_busses = current($result_Number_busses->fetch_array());
	$result_Number_of_point = $mysqli->query("SELECT `Number_of_point` FROM `upgrade_order` WHERE `Number_of_client` = '$Number_of_client'");
	$Number_of_point = current($result_Number_of_point->fetch_array());
	$result_Id_of_pieces = $mysqli->query("SELECT `Id_of_pieces` FROM `upgrade_order` WHERE `Number_of_client` = '$Number_of_client'");
	$Id_of_pieces = current($result_Id_of_pieces->fetch_array());
	$result_name_of_pieces= $mysqli->query("SELECT `Name_of_pieces` From `warehouse` where `Id_of_pieces` ='$Id_of_pieces '");
	$name_of_pieces = current($result_name_of_pieces->fetch_array());
	$result_Number_of_problem = $mysqli->query("SELECT `Number_of_problem` FROM `upgrade_order` WHERE `Number_of_client` = '$Number_of_client'");
	$Number_of_problem = current($result_Number_of_problem ->fetch_array());
	$result_Name_of_problem = $mysqli->query("SELECT `Problem_name` FROM `problems` where `Number_of_problem` = '$Number_of_problem'");
	$Name_of_problem = current($result_Name_of_problem->fetch_array());
	if($Choice == 1)
	{
		require_once("result_client.html");
	}
	else{
		require_once("result_order.html");
	}
echo "<br/>";
echo "\n Запрос отправлен";
echo "<br/>";
$mysqli->close();
?>