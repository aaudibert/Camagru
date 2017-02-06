<?php

	class USER {
		private $db;
		public function is_loggedin() {
			if (isset($_SESSION['user']))
				return true;
			}
		public function redirect($url) {
			header("Location: $url");
		}
	}
?>