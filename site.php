<?php
$FIO_Client = filter_var(trim($_POST['FIO_Client']),
FILTER_SANITIZE_STRING);
$Phone_number = filter_var(trim($_POST['Phone_number']),
FILTER_SANITIZE_STRING);
$Address = filter_var(trim($_POST['Address']),
FILTER_SANITIZE_STRING);

$Urgent_repairs = filter_var(trim($_POST['Urgent_repairs']),
FILTER_SANITIZE_STRING);
$BusSelect = filter_var(trim($_POST['BusSelect']),
FILTER_SANITIZE_STRING);
$Reception_point_Select = filter_var(trim($_POST['Reception_point_Select']),
FILTER_SANITIZE_STRING);
$Pieces_Select = filter_var(trim($_POST['Pieces_Select']),
FILTER_SANITIZE_STRING);
$Problem_Select = filter_var(trim($_POST['Problem_Select']),
FILTER_SANITIZE_STRING);
var_dump($_POST);

$mysqli = new mysqli('localhost','root','','upgrade_pc');
if($mysqli->connect_error){
    die("Connection error");
}
else{
    echo "\n Ошибок нет";
}

	$ResultAmountInStock = $mysqli->query("SELECT `Amount_InStock` FROM `warehouse` where `Id_of_pieces` = '$Pieces_Select'");
	$AmountInStock = current($ResultAmountInStock->fetch_array());
	
	if($AmountInStock < 1)
	{
		echo "<br/>";
		die("На складе  нет деталей для апгрейда/ремонта");
	}
   //заполняем таблицу клиент 
	$result_client_FIO_check = $mysqli->query("SELECT `FIO_Client` FROM `clients` WHERE `FIO_Client` = '$FIO_Client' ");
	$client_FIO_check = current($result_client_FIO_check -> fetch_array());
	if ($client_FIO_check == ""){
		$mysqli->query("INSERT INTO `clients`(`FIO_Client`,`Phone_number`, `Address`) 
		VALUES ('$FIO_Client', '$Phone_number', '$Address')");
	}
	$result_id_client = $mysqli->query("SELECT `Number_of_client` FROM `clients` WHERE `FIO_Client` = '$FIO_Client'");
	$Number_of_client = current($result_id_client -> fetch_array());
	//заполняем таблицу заказ
	$result_Cost_of_order = $mysqli->query("SELECT `Price_of_pieces` FROM `warehouse` WHERE `id_of_pieces` = '$Pieces_Select'");
	$Cost_of_order = current($result_Cost_of_order -> fetch_array()); 
	$DontDemand_in_bus = "Не нужны оба автобуса"; 
	if($DontDemand_in_bus != $BusSelect){
		$Cost_of_order =$Cost_of_order + 500;
	}
	$mysqli->query("INSERT INTO `upgrade_order`(`Number_of_client`,
	`Cost_of_order`,`Urgent_repairs`,`Number_busses`, `Number_of_point`,`Id_of_pieces`,`Number_of_problem`) 
	VALUES('$Number_of_client','$Cost_of_order', '$Urgent_repairs', '$BusSelect','$Reception_point_Select', '$Pieces_Select','$Problem_Select')");
	//заполняем таблицу стационарный цех
	$orderID = $mysqli->insert_id;
	$result_Time_of_repair = $mysqli->query("SELECT `Time_of_Solution` From `problems` where `Number_of_problem` = '$Problem_Select'");
	$Time_of_repair = current($result_Time_of_repair -> fetch_array());
	$result_Name_of_main_master= $mysqli->query("SELECT `Master_who_dec_p` From `problems` Where `Number_of_problem` = '$Problem_Select'");
	$Name_of_main_master = current($result_Name_of_main_master -> fetch_array());
	$mysqli->query("INSERT INTO `stationary_shop`(`Number_of_order`,`Id_of_pieces`,`Time_of_repair`,`Name_of_main_master`)
	VALUES('$orderID','$Pieces_Select','$Time_of_repair','$Name_of_main_master')");
	//подсчитывае срочные ремонты в приёмном пункте
	if($Urgent_repairs == "да" or $Urgent_repairs == "yes" or $Urgent_repairs == "Yes" or $Urgent_repairs == "Да")
	{
		$mysqli->query("Update `reception_point` set `Amount_of_Urgent_Repairs` = `Amount_of_Urgent_Repairs` + 1
		Where `Number_of_point` = $Reception_point_Select");
	}
	$mysqli->query("Update `warehouse` set `Amount_InStock` = `Amount_InStock` - 1 where `Id_of_pieces` = '$Pieces_Select'");
echo "<br/>";
echo "\n Запрос отправлен";
echo "<br/>";
$mysqli->close();
?>