<?php
	error_reporting( ~E_NOTICE );
	require_once 'dbcon.php';

	if(isset($_POST['btnsave']))
	{
		$username = $_POST['user_name'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
        $address = $_POST['address'];
        $password_1 = $_POST['password_1'];
        $password_2 =$_POST['password_2'];
		$imgFile = $_FILES['user_image']['name'];
		$tmp_dir = $_FILES['user_image']['tmp_name'];
		$imgSize = $_FILES['user_image']['size'];
		if(empty($username)){
			$errMSG = "Please Enter Username.";
		}
		else if(empty($email)){
			$errMSG = "Please Enter Your email.";
			}
			else if(empty($password_1)){
			$errMSG = "Please Enter Your Password.";
			}
  
			else if($password_1 != $password_2) {
				$errMSG = "The two passwords do not match";
					# code...
				//}	# code...
			}
		
		else if(empty($imgFile)){
			$errMSG = "Please Select Image File.";
		}
		else
		{
			$upload_dir = 'uploads/';
			$imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION));
			$valid_extensions = array('jpeg', 'jpg', 'png', 'gif');
			$userprofile = rand(1000,1000000).".".$imgExt;
			if(in_array($imgExt, $valid_extensions)){
				if($imgSize < 5000000)				{
					move_uploaded_file($tmp_dir,$upload_dir.$userprofile);
				}
				else{
					$errMSG = "Sorry, Your File Is Too Large To Upload. It Should Be Less Than 5MB.";
				}
			}
			else{
				$errMSG = "Sorry, only JPG, JPEG, PNG & GIF Extension Files Are Allowed.";		
			}
		}
		if(!isset($errMSG))
		{
			$stmt = $DB_con->prepare('INSERT INTO users(username,email,phone, address,password,userprofile) VALUES(:uname, :uem,:up,:uaddr,:upas,:upic)');
			$stmt->bindParam(':uname',$username);
			$stmt->bindParam(':uem',$email);
			$stmt->bindParam(':up',$phone);
			$stmt->bindParam(':uaddr',$address);
			$stmt->bindParam(':upas',$password_1);
			$stmt->bindParam(':upic',$userprofile);	
			if($stmt->execute())
			{
				$successMSG = "Successfully Added A New Member.";
				header("refresh:1;home.php");
			}
			else
			{
				$errMSG = "Error While Creating.";
			}
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
<title>PHP/MySQL Add, Edit, Delete, With User Profile.</title>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<script src="bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
<nav class="navbar navbar-default navbar-static-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="http://www.sourcecodester.com">EvecYola</a>
			<ul class="nav navbar-nav">
            <li class="active"><a href="index.php">Home</a></li>
            <li><a href="">About</a></li>
            <li><a href="">Contact Us.</a></li>
            <li><a href="index.php">Log-Out</a></li>
			</ul>
        </div>
    </div>
</nav>
<div class="container">
	<div>
	<h1 class="h2">&nbsp; Add New User<a class="btn btn-success" href="home.php" style="margin-left: 850px"><span class="glyphicon glyphicon-home"></span>&nbsp; Back</a></h1><hr>
    </div>
	<?php
	if(isset($errMSG)){
			?>
            <div class="alert alert-danger">
            	<span class="glyphicon glyphicon-info-sign"></span> <strong><?php echo $errMSG; ?></strong>
            </div>
            <?php
	}
	else if(isset($successMSG)){
		?>
        <div class="alert alert-success">
              <strong><span class="glyphicon glyphicon-info-sign"></span> <?php echo $successMSG; ?></strong>
        </div>
        <?php
	}
	?>   

<form method="post" enctype="multipart/form-data" class="form-horizontal" style="margin: 0 300px 0 300px;border: solid 1px;border-radius:4px">
	<table class="table table-responsive">
    <tr>
    	<td><label class="control-label">Username</label></td>
        <td><input class="form-control" type="text" name="user_name" placeholder="Enter Username" value="<?php echo $username; ?>" /></td>
    </tr>
    <tr>
    	<td><label class="control-label">Email</label></td>
        <td><input class="form-control" type="email" name="email" placeholder="email" value="<?php echo $email; ?>" /></td>
    </tr>
    <tr>
    	<td><label class="control-label">phone</label></td>
        <td><input class="form-control" type="number" name="phone" placeholder="phone" value="<?php echo $phone; ?>" /></td>
    </tr>
     <tr>
    	<td><label class="control-label">address</label></td>
        <td><input class="form-control" type="text" name="address" placeholder="address" value="<?php echo $address; ?>" /></td>
    </tr>
    
     <tr>
    	<td><label class="control-label">password</label></td>
        <td><input class="form-control" type="password" name="password_1" placeholder="password" value="<?php echo $password_1; ?>" /></td>
    </tr>
    <tr>
    	<td><label class="control-label">confirm password</label></td>
        <td><input class="form-control" type="password" name="password_2" placeholder="password" value="<?php echo $password_2; ?>" /></td>
    </tr>
    <tr>
    	<td><label class="control-label">Profile Picture</label></td>
        <td><input class="input-group" type="file" name="user_image" accept="image/*" /></td>
    </tr>
    <tr>
        <td colspan="2" align="center"><button type="submit" name="btnsave" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-save"></span>&nbsp; Save</button>
        </td>
    </tr>
    </table>
</form>
</div>
</body>
</html>