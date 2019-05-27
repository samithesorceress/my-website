<?php
$data = [];
$sql = false;
$sql_sel = "SELECT * FROM ";
$sql_ins = "INSERT INTO ";
$sql_upd = "UPDATE ";
$sql_del = "DELETE FROM ";
$sql_vals = " VALUES (";
$sql_set = " SET";
$sql_whr = " WHERE ";
$sql_ord = false;
$sql_lmt = " LIMIT ";
$output = [
	"success" => false,
	"message" => "",
	"data" => []
];
$htp_root = "127.0.0.1/sami-the-sorceress/";