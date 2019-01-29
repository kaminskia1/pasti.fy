<?php
require("/var/www/vhosts/testing.cheapboost.me/httpdocs/AppSpot/php/functions.php");
if (loggedIn()) {
	$userInfo = userInfo();
} else {
	denyAccess();
}
$data_dir = "/var/www/vhosts/testing.cheapboost.me/httpdocs/AppSpot/app/pastify/data/";


if ($_POST['function'] == "newPost") { post::newPost($_POST['title'], $_POST['data'], $_POST['key']); }  // Add a post
if ($_POST['function'] == "deletePost") { post::deletePost($_POST['data'], $_POST['id']); }              // Delete a post
if ($_POST['function'] == "editPost") { post::editPost($_POST['id'], $_POST['data'], $_POST['key']); }   // Reload the chat
if ($_POST['function'] == "checkTag") { post::checkTag($_POST['tag']); }                                 // Checks if tag is valid

class post {
	
	public function newPost($title, $data, $key) {
		global $conn, $userInfo, $forwardIP, $data_dir;
		$title = filter_var($title, FILTER_SANITIZE_STRING);
		if (strlen($title) < 255 && $key < 255) {
			if ($title == null || strlen($title) < 1) {
				$title = "New Paste";
			}
			$data = filter_var($data, FILTER_SANITIZE_STRING);
			$key = filter_var($key, FILTER_SANITIZE_STRING);
			$username = $userInfo['username'];
			$ip = 
			$tag = substr(md5(uniqid(mt_rand(), true)) , 0, 30);
			a:
			$file = substr(md5(uniqid(mt_rand(), true)) , 0, 15) . ".txt";
			if (file_exists($data_dir . $file)) {
				goto a;
			}
			$state = 0;
			if ($key != null && $key != '') {
				$data = misc::encrypt($data, $key);
				$state = 1;
			}
			$date = date('Y-m-d H:i:s');
			$conn->query("INSERT INTO `paste_data` (`username`,`ip`,`title`,`tag`,`file`,`encrypt`,`date`) VALUES ('$username','$forwardIP','$title','$tag','$file','$state','$date')");
			$fileW = fopen($data_dir . $file, "w");
			fwrite($fileW, $data);
			fclose($fileW);
			echo $tag;
		} else {
			echo false;
		}
	}

	public function deletePost($id) {
		global $conn, $data_dir;
		if (isRole('administrator')) {
			$result = $conn->query("SELECT * FROM `paste_data` WHERE `id`='$id'");
			if ($result->num_rows > 0) {
				$result = $result->fetch_assoc();
				$file = $result['file'];
				unlink($data_dir . $file);
				echo "Paste Deleted";
			} else {
				echo "Invalid ID";
			}
		}
	}

	public function viewPost($tag, $key) {
		global $conn;
		$tag = filter_var($tag, FILTER_SANITIZE_STRING);
		$key = filter_var($key, FILTER_SANITIZE_STRING);
		$result = $conn->query("SELECT * FROM `paste_data` WHERE `tag`='$tag'");
		if ($result->num_rows > 0) {
			$result = $result->fetch_assoc();
			$data = misc::fileToVar($result['file']);
			if (($key != NULL && $key != '') && ($result['encrypt'] == 1 || $result['encrypt'] == '1')) {
				$data = misc::encrypt($data, $key);
			} elseif (($key == NULL || $key == '') && ($result['encrypt'] == 1 || $result['encrypt'] == '1')) {
				$data = false;
				$result['error'] = 'key';
			}
			$result['data'] = $data;
		} else {
			$result = array('data'=>false,'error'=>'tag');
		}
		return $result;	
	}
	
	public function checkTag($tag) {
		global $conn;
		$tag = filter_var($tag, FILTER_SANITIZE_STRING);
		$result = $conn->query("SELECT * FROM `paste_data` where `tag`='$tag'");
		if ($result->num_rows > 0) {
			echo "true";
		} else {
			echo "false";
		}
	}
}

class module {
	
	public function title($title, $desc) { ?>
        <title>Pasti.fy<?php if ($title != null) { echo " - " . $title;}?> </title>
        <meta name="description" content="<?php if ($desc !== null) { echo $desc; } else {?>Text pasting, with more!<?php }?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="A-K">
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
		<script src="js/jquery-3.3.1.js"></script>
		<script src="js/main.js"></script>
	<?php }
	
	public function head() { 
		global $userInfo; ?>
		<nav class="navbar">
			<div class="title">Pasti.fy</div>
			<ul class="navbar-container"> 
				<li class="navbar-tab" onclick='javascript:location.href = "index.php"'>Home</li>
				<li class="navbar-tab" onclick='javascript:location.href = "new.php"'>New Paste</li>
				<li class="navbar-tab" onclick='javascript:location.href = "viewPost.php"'>View Post</li>
				<li class="navbar-tab" onclick='javascript:location.href = "info.php"'>Info</li>
			</ul>
			<div class='login-tab'>
				<div class='username'><?php echo $userInfo['username'];?></div>
				<div class='dropdown-toggle'>
					<i class="fas fa-caret-down"></i>
				</div>
				<div class='dropdown' style='display:none;opacity:0'>
					<ul>
						<li onclick='javascript:location.href = "userPastes.php"'>My Pastes</li>
						<li onclick='javascript:location.href = "userAccount.php"'>My Account</li>
					</ul>
				</div>
			</div>
		</nav>
	<?php }
	
	public function footer() { ?>		
		<footer class='footer'>
			<div class='copyright'>&copy; <?php echo date("Y"); ?> - AK AppSpot</div>
		</footer>
	<?php }
	
	public function paste() {?>
		<form class="form-paste">
			<div class="form-header">
				<input class='form-title' maxlength="250" placeholder='New Paste'>
			</div>
			<div class="form-content">
				<textarea name="content" class="form-textarea"></textarea>
				<div class="encrypt-container" style="width: 94px;">
					<div class="encrypt-overflow">
						<label class="material-checkbox">
							<input type="checkbox">
							<span>Encrypt</span>
						</label>
						<input type="text" maxlength="250" placeholder="password" name="password">
					</div>
				</div>
				<input type="button" value="Submit">
			</div>
		</form>
	<?php }
	
	public function recent($count) {
		$count = filter_var($count, FILTER_SANITIZE_NUMBER_INT);
		global $conn;
		$data = [];
		$result = $conn->query("SELECT * FROM `paste_data` ORDER BY `id` DESC LIMIT $count");
		while ($row = $result->fetch_assoc()) {
			$temp = (object) [
				'id'        =>   $row['id'],
				'tag'       =>   $row['tag'],
				'title'     =>   $row['title'],
				'username'  =>   $row['username'],
				'age'       =>   $row['date'],
				'encrypt'   =>   $row['encrypt']
			];
			array_push($data, $temp);
		}
		if (count($data) < $count) {
			$count = count($data);
		}
		?><div class='recent-holder'><?php
		for ($i=0;$i<$count;$i++) { ?>
				<div class='recent-container' onclick="location.href = 'viewPost.php?tag=<?php echo $data[$i]->tag ?>'">
					<div class='recent-title'>
						<?php
							if (strlen($data[$i]->title) < 1) { 
								echo "New Paste";
							} else { 
								echo $data[$i]->title; 
							}
						?>
					</div>
					<div class='recent-author'><?php echo $data[$i]->username ?></div>
					<div class='recent-time'><?php echo $data[$i]->age ?></div>
					<?php if ($data[$i]->encrypt == 1 || $data[$i]->encrypt == '1') { ?> 
						<div class='recent-encrypt'><i class="fas fa-lock"></i></div>
					<?php } ?>
				</div>
		<?php }
		?></div><?php
	}
	
	public function view($tag, $key) {
		global $conn;
		if (!isset($tag) || strlen($tag) < 1 || $tag == null) {?>
			<div class='view-unset'>
				<div class='view-unset-header'>Enter a tag</div>
				<input type='text' class='view-unset-input tag' maxlength="31">
			</div><?php
		} else {
			$pasteData = post::viewPost($tag, $key);
			if ($pasteData['data'] !== false) {?>
				<div class='view-content'>
					<div class='view-holder'>
						
						<div class='view-header'><?php echo $pasteData['title'] ?></div>
						<div class='view-author'><?php echo $pasteData['username']?></div>
						<div class='view-option-container'>
							<?php if (isRole('administrator') || $pasteData['username'] == $userInfo['username']) {?>
								<div class='view-option delete'><i class="far fa-trash-alt"></i></div>
							<?php } ?>
							<div class='view-option raw'><i class="far fa-file"></i></div>
							<div class='view-option download'><i class="fas fa-download"></i></div>
						</div>
						<div class='view-data'><?php echo $pasteData['data'] ?></div>
					</div>
				</div>
				<?php
			} else {
				if ($pasteData['error'] == "tag") { ?>
				<div class='view-unset'>
				<div class='view-unset-header'>Enter a valid tag</div>
				<input type='text' class='view-unset-input tag' maxlength="31">
			</div><?php
				} elseif ($pasteData['error'] == "key") { ?>
				<div class='view-unset'>
					<div class='view-unset-header'>Enter password</div>
					<input type='password' class='view-unset-input password' maxlength="250">
					<button class='view-password-submit'>Submit</button>
				</div><?php
				}
			}
		}
	}
}

class misc {

	public function fileToVar($file) {
		global $data_dir;
		return file_get_contents($data_dir . $file, FILE_USE_INCLUDE_PATH);
	}
	
	public function encrypt($data, $key) {
		$text = $data;
		$output = '';
		for($i=0; $i<strlen($text);) {
			for($x=0; ($x<strlen($key) && $i<strlen($text)); $x++,$i++) {
				$output .= $text{$i} ^ $key{$x};
			}
		}
		return $output;
	}
	
}