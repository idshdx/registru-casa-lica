<?php

	function sold_final($sold_initial, $vanzari, $total_chelt, $total_marfa) {
	    $sold_final = $sold_initial + $vanzari - $total_chelt - $total_marfa;

	    return $sold_final;
	}

	function string_to_integer($string) {
		return intval($string);
	}

	function string_to_float($string) {
		return floatval($string);
	}

	function secure($username, $password) {
		return md5($username.$password.date('Y-m-d') );
	}
	
?>

