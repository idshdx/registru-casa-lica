<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/styles.css">
    <script src="<?php echo base_url(); ?>/assets/js/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>/assets/js/bootstrap.min.js"></script>
    <title>Registru de Casa</title>
</head>
<body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="#">LICA SRL</a>
			</div>
		</div>
	</nav>
	<div>
		<div class="panel panel-primary">
			<div class="panel-heading text-center"><h4>Log In</h4></div>
			<div class="panel-body">
				<form class="form-horizontal" role="form">
					<div class="form-group">
						<label class="control-label col-sm-3" for="username">Username:</label>
						<div class="col-sm-9">
							<input type="text" class="form-control login-input" id="username" placeholder="Enter username">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" for="pwd">Password:</label>
						<div class="col-sm-9"> 
							<input type="password" class="form-control login-input" id="pwd" placeholder="Enter password">
						</div>
					</div>
					<div class="form-group"> 
						<div class="col-sm-offset-4 col-sm-4">
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<footer class="page-space">
	  <p>Copyright &copy; 2015 SC LICA SRL. Toate drepturile rezervate. </p>
	</footer>
</body>
</html>
