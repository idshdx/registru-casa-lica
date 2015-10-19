<!DOCTYPE html>
<html>
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Login Form</title>
 <!--link the bootstrap css file-->
 <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
 
 <style type="text/css">
 .colbox {
      margin-left: 0px;
      margin-right: 0px;
 }
 </style>
</head>

<body>
<hr/>
<br><br>

<div class="container" style="padding-top: 60px;">
  <h1 class="page-header">Login</h1>
  <div class="row">
      <?php 
      $attributes = array("class" => "form-horizontal", "id" => "loginform", "name" => "loginform");
      echo form_open("login/login_check", $attributes);?>
          
      <div class="form-group">
        <label class="col-lg-3 control-label">Username:</label>
        <div class="col-lg-4">
          <input class="form-control" id="input_username" name="input_username" placeholder="Username" type="text" value="<?php echo set_value('input_username'); ?>" />
          <span class="text-danger"><?php echo form_error('input_username'); ?></span>
        </div>
        
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label">Password:</label>
        <div class="col-lg-4">
          <input class="form-control" id="input_password" name="input_password" placeholder="Password" type="password" value="<?php echo set_value('input_password'); ?>" />
          <span class="text-danger"><?php echo form_error('input_password'); ?></span>
        </div>
      </div>  

      <div class="form-group">
        <label class="col-md-3 control-label"></label>
        <div class="col-md-8">
            <input id="btn_login" name="btn_login" type="submit" class="btn btn-primary" value="Login" />
        </div>
      </div>

      <?php echo form_close(); ?>
    </div>
  </div>
</div>
     
<!--load assets-->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

</body>
</html>

sume(tabelul, data) 
return [ [],[] ]