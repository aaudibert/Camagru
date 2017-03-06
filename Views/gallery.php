<?php 
	session_start();

	require '../Config/setup.php';

	function display_likes($pic_id, $db) {
		$req = $db->prepare("SELECT * FROM likes WHERE pic_id = ? ORDER BY liked_on DESC");
		$req->execute(array($pic_id));
		$likes = $req->fetchAll();
		if ($likes) {
			$tot = count($likes);
			if ($tot > 1) {
				echo "<p id='cl".$pic_id."'>".$tot." likes</p>";
			}
			else if ($tot == 1) {
				echo "<p id='cl".$pic_id."'>1 like</p>";
			}
		}
		else {
			echo "<p id='cl".$pic_id."'>0 likes</p>";
		}
	}

	function ft_like($pic_id, $db) {
		$req = $db->prepare("SELECT * FROM likes WHERE pic_id = ?");
		$req->execute(array((int)$pic_id));
		$liked = $req->fetch();

		if ($liked) {
			echo "<i id='l".$pic_id."' class='fa fa-heart' aria-hidden='true' onclick='likeImg(this.id)' style='font-size: 28px;color:red;'></i>";
		}
		else {
			echo "<i id='l".$pic_id."' class='fa fa-heart-o' aria-hidden='true' onclick='likeImg(this.id)' style='font-size: 28px;@media screen and (min-width: 200px) and (max-width: 1024px){font-size: 58px;}'></i>";
		}
	}

	function display_comments($pic_id, $db) {
		$req = $db->prepare("SELECT * FROM comments WHERE pic_id = ? ORDER BY com_id DESC");
		$req->execute(array($pic_id));
		$com = $req->fetchAll();

		if ($com) {
			$i = 0;
			$max = count($com);
			while ($i < $max) {
				echo "<div>";
				echo "<p>".$com[$i]['username'].": ".$com[$i]['comment']."</p>";
				$i++;
				echo "</div>";
			}
		}
	}

	$req = $db->prepare("SELECT * FROM gallery");
	$req->execute();
	$pics = $req->fetchAll();

	$max = $i = count($pics);

	$post_per_page = 5;
	$page_max = ceil($max / $post_per_page);

	if (!$max) {
		$_SESSION['flash']['empty'] = "The gallery is currently empty, why don't you add youy own pictures to it ?";
		if (!$_SESSION['auth']) {
			$_SESSION['flash']['empty_out '] = "But first you'll have to login / sign up!";
		}
	}

	if ($_GET && $_GET['p'] && is_numeric($_GET['p']) == true && $_GET['p'] > 0) {
		(int)$page = $_GET['p'];
	}
	else {
		$page = 1;
	}

	if ((int)$page > (int)$page_max && $max) {
		$redir = 'Location: ../Views/gallery.php?p='.(string)$page_max;
		header($redir);
		exit();
	}

	require '../Views/header.php';
	
	while ((--$i - (($page - 1) * $post_per_page)) >= 0 && $tmp < 5) {
		echo "<div class='pictures'>";
		echo "<div class='picture_head'><h4>".$pics[$i - (($page - 1) * $post_per_page)]['username']."</h4>";
		display_likes($pics[$i - (($page - 1) * $post_per_page)]['pic_id'], $db);
		echo "</div>";
		echo "<img src='".$pics[$i - (($page - 1) * $post_per_page)]['link']."'/><br>";
		echo "<div id='".$pics[$i - (($page - 1) * $post_per_page)]['pic_id']."' class='comment'>";
		display_comments($pics[$i - (($page - 1) * $post_per_page)]['pic_id'], $db);
		echo "</div>";
		if (isset($_SESSION['auth'])) {
			echo "<div class='interact'>";
			ft_like($pics[$i - (($page - 1) * $post_per_page)]['pic_id'], $db);
			echo '<textarea name="comment" id="c'.$pics[$i - (($page - 1) * $post_per_page)]['pic_id'].'" class="comment_type" onkeypress="comment('.$pics[$i - (($page - 1) * $post_per_page)]['pic_id'].', '.$page.')" placeholder="Comment on this picture"></textarea>';
			echo "</div>";
		}
		echo "</div>";
		$tmp++;
	}

	for ($i = 1; $i <= $page_max; $i++) { 
			echo "<a href='http://localhost:8080/camagru/Views/gallery.php?p=".$i."'>Page ".$i."</a>";
			if ($i != $page_max) {
				echo " ";
			}
	}
 	require '../Views/footer.php';
 ?>
	<script src="../Js/gallery.js"></script> 
	<script src="https://use.fontawesome.com/223539d286.js"></script>
