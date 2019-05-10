<?php

foreach ($_REQUEST as $key => $value) {
		if ($key !== "file") {
			// Then sanitize them
			$data[$key] = addslashes($value);
		}
		
	}