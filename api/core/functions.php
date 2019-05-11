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

function prepareSQL($action, $table, $params = false, $where = false) {
	$sql = false;
	if ($action && $table) {
		$table = " `" . $table . "` ";
		$action = strtoupper($action);
		switch($action) {
			case "SELECT":
				if ($where) {
					$sql = "SELECT * FROM" . $table . "WHERE ";
					foreach($where as $key => $val) {
						$sql .= "`" . $key . "`='" . sanitize($val) . "' AND ";
					}
					$sql = rtrim($sql, " AND ");
				} else {
					return false;
				}
				break;
			case "INSERT":
				if ($params) {
					$sql = "INSERT INTO" . $table;
					$fields = "(";
					$values = "(";
					foreach($params as $key => $val) {
						$fields .= $key . ", ";
						$values .= "'" . sanitize($val) . "', ";
					}
					$fields = rtrim($fields, ", ") . ")";
					$values = rtrim($values, ", ") . ")";
					$sql .= $fields . " VALUES " . $values;
				} else {
					return false;
				}
				break;
			case "UPDATE":
				if ($params && $where) {
					$sql = "UPDATE" . $table . " SET ";
					foreach($params as $key => $val) {
						$sql .= "`" . $key . "`='" . sanitize($val) . "', ";
					}
					$sql = rtrim($sql, ", ") . " WHERE ";
					foreach($where as $key => $val) {
						$sql .= "`" . $key . "`='" . sanitize($val) . "' AND ";
					}
					$sql = rtrim($sql, " AND ");
				} else {
					return false;
				}
				break;
			default:
				return false;
		}
	}
	return $sql;
}