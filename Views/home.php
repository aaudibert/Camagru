<?php 
	
	session_start();

	if (!$_SESSION['auth'] || !isset($_SESSION['auth']))
		header('Location: ../Views/sign_in.php?redirect=unsigned');



	function getPictures() {
		require_once '../Config/setup.php';

		$req = $db->prepare("SELECT * FROM gallery WHERE user_id = ?");
		$req->execute(array($_SESSION['auth']['user_id']));
		$pics = $req->fetchAll();
		$i = count($pics);
		$tmp = 0;

		if ($i) {
			while (--$i >= 0) {
				echo "<img id='".$pics[$i]['pic_id']."' src='".$pics[$i]['link']."' width=300px onclick='deletePic(this.id)' class='prevs'/>";
			}
		}
	}

	require '../Views/header.php'; 
 ?>

		<table style="margin: auto;">
			<tr>
				<td id="main_fp">
					<div id="rendu_final">
						<table class="filters">
							<tr>
								<td class="flt">
									<img src="../Filters/fokof.png" width=75px>
								</td>
								<td class="flt">
									<img src="../Filters/frog.png" width=75px>
								</td> 
								<td class="flt">
									<img src="../Filters/penguin.png" width=150px>
							</tr>
							<tr>
								<td class="flt">
									<input type="checkbox" name="1" id="cb1" onclick="radioCheck(this.id)">
								</td>
								<td class="flt">
									<input type="checkbox" name="2" id="cb2" onclick="radioCheck(this.id)">
								</td>
								<td class="flt">
									<input type="checkbox" name="3" id="cb3" onclick="radioCheck(this.id)">
								</td>
							</tr>
						</table>
						<video class="video" id="video"></video>
						<div id="filter_prev" class="filter1"></div>
					</div>

					<canvas id="canvas"></canvas>
					<div class="buttons">
						<button id="startbutton">Take a picure!</button>
						<button id="fileToUpload" onclick="document.getElementById('file').click();">Upload your own file</button>
					</div>
						<form  method="post" enctype="multipart/form-data">
						    <input type="file" name="fileToUpload" id="file"/>
						</form>
					</td>
				</tr>
				<tr>
					<td>
						<div id='preview'>
							<?php getPictures(); ?>
						</div>
					</td>
				</tr>
			</table>

		<script src="../Js/webcamHandle.js"></script>
		<script src="../Js/script.js"></script>

<?php require '../Views/footer.php' ?>
