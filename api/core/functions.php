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

function prepareSQL($action, $table, $params = false, $where = false, $order = false, $limit = false) {
	$sql = false;
	if ($action && $table) {
		$table = " `" . $table . "` ";
		$action = strtoupper($action);
		switch($action) {
			case "SELECT":
				$sql = "SELECT * FROM " . $table;
				if (!empty($where)) {
					$sql .= " WHERE ";
					foreach($where as $key => $val) {
						$sql .= "`" . $key . "`='" . sanitize($val) . "' AND ";
					}
					$sql = rtrim($sql, " AND ");
				}
				if (!empty($order)) {
					if ($order["by"]) {
						$sql .= " ORDER BY `" . $order["by"] . "`";
						if ($order["dir"]) {
							$sql .= " " . strtoupper($order["dir"]);
						}
					}
					
				}
				if (!empty($limit)) {
					$sql .= " LIMIT ";
					if ($limit["start"]) {
						$sql .= $limit["start"] . ", ";
					}
					if ($limit["end"]) {
						$sql .= $limit["end"];
					}
					if (!$limit["start"] && !$limit["end"]) {
						$sql .= $limit;
					}
				}
				return $sql;
				break;
			case "INSERT":
				if (!empty($params)) {
					$sql = "INSERT INTO" . $table;
					$fields = "(";
					$values = "(";
					foreach($params as $key => $val) {
						$fields .= $key . ", ";
						if ($key !== "links") {
							$val = sanitize($val);
						}
						$values .= "'" . $val . "', ";
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
					$sql = "UPDATE" . $table . "SET ";
					foreach($params as $key => $val) {
						if ($key !== "links") {
							$val = sanitize($val);
						}
						$sql .= "`" . $key . "`='" . $val . "', ";
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