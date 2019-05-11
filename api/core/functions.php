<?php

function sanitize($data) {
	return addslashes($data);
}

function valExists($key, $arr) {
	if (is_array($arr)) {
		if (array_key_exists($key, $arr) && $arr[$key]) {
			return true;
		}
		return false;
	}
	return false;
}

function checkRequired($required, $input) {
	$res = [
		"success" => false,
		"missing" => []
	];

	if ($required !== false && $input !== false) {
		if (is_array($required) && is_array($input)) {
			foreach ($required as $req) {
				if (!valExists($req, $input)) {
					$res["missing"][] = $req;
				}
			}
			if (count($res["missing"]) < 1) {
				$res["success"] = true;
			}
		}
	}

	return $res;
}

function prepareSQL($action, $table, $params, $where) {
	$sql = false;
	if ($action && $table && ($params || $where)) {
		$sql = " `" . $table . "` ";
		$action = strtoupper($action);
		switch($action) {
			case SELECT:
				$sql = "SELECT * FROM" . $sql . "WHERE ";
				foreach($params as $key => $val) {
					$sql .= "`" . $key . "`='" . sanitize($val) . "' AND ";
				}
				$sql = rtrim($sql, " AND ");
				break;
			case INSERT:
				$sql = "INSERT INTO" . $sql;
				$fields = "(";
				$values = "(";
				foreach($params as $key => $val) {
					$fields .= $key . ", ";
					$values .= "'" . sanitize($val) . "', ";
				}
				$fields = rtrim($fields, ", ") . ")";
				$values = rtrim($values, ", ") . ")";
				$sql .= $fields . " VALUES " . $values;
				break;
			case UPDATE:
				$sql = "UPDATE" . $sql;

				break;
			default:
				return false;
		}
		$sql .= "`" . $table . "` ";
		
	}
	return $sql;
}