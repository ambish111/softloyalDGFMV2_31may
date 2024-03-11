<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1"> 
<link rel="icon" href="<?=$this->site_data->newlogo?>" type="image/x-icon">
<title>Inventory</title>
<?php $this->load->view('include/file'); ?>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

<link rel="stylesheet" href="assets/css/login.css">
</head>

<body> <!--login-cover style="background-color:#0F3BA7;"-->

<!-- Page container -->

  
  <!-- Page content -->
  
    
    <!-- Main content -->
    
      
      <!-- Content area -->
      
        
        <!-- Form with validation -->
       
        

        <section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<!-- <div class="col-md-6 text-center mb-5">
					<h2 class="heading-section">Login #07</h2>
				</div> -->
			</div>
			<div class="row justify-content-center">
				<div class="col-md-12 col-lg-10">
					<div class="wrap d-md-flex">
						<div class="text-wrap p-4 p-lg-5 text-center d-flex align-items-center order-md-last shadow1">
							<!-- <div class="text w-100">
								<h2>DIGGIPACKS</h2>
								
								
							</div> -->
			      </div>
						<div class="login-wrap p-4 p-lg-5 shadow2" ng-init='getLogo();'>
			      	<div class="d-flex">
			      		
								
			      	</div>
              <form action="Login/auth_user" class="form-validate" method="post" >
                            <div class="text-center">
                            <img src="<?=$this->site_data->newlogo?>" width="150">
                    <h3 style="color:black;font-weight: 600;">Login </h3>
                        </div>
			      		<div class="form-group mb-3">
			      			<label class="label" for="name">Username</label>
			      			<input type="text" class="form-control" placeholder="Username" name="username" required>
			      		</div>
		            <div class="form-group mb-3">
		            	<label class="label" for="password" >Password</label>
		              <input type="password" class="form-control" placeholder="Password" name="password" required>
		            </div>
		            <div class="form-group">
                    <?php 
								if($this->session->flashdata('Error'))
								echo '<div class="alert alert-success">'.$this->session->flashdata('Error').' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'?> 
		            	<button type="submit" class="form-control btn  submit px-3" style="background-color:<?=$this->site_data->theme_color_fm?>;">Login</button>
		            </div>
                    <div style="color:black; text-align: center;">
                                    
                                    <p>Power By<a href="#"> <img src="<?=$this->site_data->newlogo?>" class="img-fluid text-dark" style="width: 60px;height: 10px;vertical-align: middle;" width="40px;" ></a> © 2020</p> </div>  
		          </form>
		        </div>
		      </div>
				</div>
			</div>
		</div>
	</section>

	<!-- <script src="asset/js/jquery.min.js"></script>
  <script src="asset/js/popper.js"></script>
  <script src="asset/js/bootstrap.min.js"></script>
  <script src="asset/js/main.js"></script> -->

	</body>



</html>
