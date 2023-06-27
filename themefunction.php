<?php
	function getTheme(){
		return trim(file_get_contents("selected_theme.txt"));
	}

	function setTheme($theme){
		file_put_contents("selected_theme.txt", $theme);
	}


	$availableThemes = ["maintheme", "pastel", "tecoturg", "fluorescent"];

	if(isset($_POST["themeSelect"]) && in_array($_POST["themeSelect"], $availableThemes)) {
		setTheme($_POST["themeSelect"]);
	}

?>
