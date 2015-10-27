<?php

    //$date(date array) = result of call to date_parse
     function parsed_date_to_string($date) {
        return  join('-', [$date['year'], $date['month'], $date['day'] ] );
     }

	function secure($username, $password) {
		return md5($username.$password.date('Y-m-d') );
	}

	function loggedin() {
      return isset($_SESSION['userdata']);
    }
	
?>

