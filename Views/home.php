<?php 
	session_start();

	if (!$_SESSION['auth'] || !isset($_SESSION['auth']))
		header('Location: ../Views/sign_in.php?redirect=unsigned');

	require '../Views/header.php';
 ?>

		<div id="content">
		<div id="rendu_final">
			<video class="video"id="video"></video>
			<div id="filter_prev" class = "filter1"></div>
		</div>

		<canvas id="canvas"></canvas>
			<div id= "bouton">
			<button id="startbutton">Take a picure!</button>
				
			<form action="../Controlers/upload.php" method="post" enctype="multipart/form-data">
			    Select image to upload:
			    <input type="file" name="fileToUpload" id="fileToUpload">
			    <input type="submit" value="Upload Image" name="submit">
			</form>

			<form method="post" action="final.php">
				<select name="filter" id="choice">
					<option name = "1" value ="filter1">Filter 1</option>
					<option name = "2" value ="filter2">Filter 2</option>
					<option name = "3" value ="filter3">Filter 3</option>
				</select>
				<input id = "filter_button" type="submit" VALUE="filter"/>
			</form>
			</div>
		</div>

		<script src="../Js/webcamHandle.js"></script>
		<script type="text/javascript" src= "../Js/filter_preview.js"></script>


<?php require '../Views/footer.php' ?>
