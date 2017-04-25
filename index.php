<?php 
    require("config.php"); 
    $submitted_username = ''; 
    if(!empty($_POST)){ 
        $query = " 
            SELECT 
                id, 
                username, 
                password, 
                salt, 
                email 
            FROM users 
            WHERE 
                username = :username 
        "; 
        $query_params = array( 
            ':username' => $_POST['username'] 
        ); 
         
        try{ 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
        $login_ok = false; 
        $row = $stmt->fetch(); 
        if($row){ 
            $check_password = hash('sha256', $_POST['password'] . $row['salt']); 
            for($round = 0; $round < 65536; $round++){
                $check_password = hash('sha256', $check_password . $row['salt']);
            } 
            if($check_password === $row['password']){
                $login_ok = true;
            } 
        } 

        if($login_ok){ 
            unset($row['salt']); 
            unset($row['password']); 
            $_SESSION['user'] = $row;  
            header("Location: home.html"); 
            die("Redirecting to: home.html"); 
        }else {
			$message = "Username / Password Incorrect. Please Try Again.";
			echo "<script type='text/javascript'>alert('$message');</script>";
    } 
}
?> 
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>GIC Integrated Systems</title>
    <meta name="description" content="Bootstrap Tab + Fixed Sidebar Tutorial with HTML5 / CSS3 / JavaScript">
    <meta name="author" content="Untame.net">

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <script src="assets/bootstrap.min.js"></script>
    <link href="assets/bootstrap.min.css" rel="stylesheet" media="screen">
	<link rel="stylesheet" href="bootstrap.min.css">
	<link rel="stylesheet" href="login.css">
    
</head>
<body>
<div class="wrap">
	<div class="avatar">
      <img src="gic.jpg">
	</div>
				<form action="index.php" method="post">
                    <input type="text" name="username" placeholder="username" value="<?php echo $submitted_username; ?>" /> 
						<div class="bar">
					<i></i>
                    <input type="password" name="password" placeholder="password" value="" />
					<br>
					<table align="center">
					<tbody>
					<tr>
					<td>
					<input class="sign" style="background: linear-gradient(#fff214, #ffdd76);width: 100px" type="submit" value="Login" /> 
					</td>
					<td class="trans">..</td>
					<td style="padding-top: 5px">
					<button class="sign" style="width: 100px"><a href = "register.php" style="color: #277811;text-decoration: none">Register</button>
					</td>
					</tr>
					</tbody>
					</table>
				</form> 
					</div>
	
</div>
</body>
</html>