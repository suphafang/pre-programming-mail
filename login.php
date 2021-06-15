<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<link rel="icon" href="icon.png">
	<title>Login | Pre-programming Mail</title>
	<style type="text/css">
		@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Sarabun:wght@300;400;500;600;700&display=swap');
		* {
			font-family: 'Roboto', 'Sarabun', sans-serif;
		}
		body {
			background: #eeeeee;
		}
		.loading {
			width: 100vw;
			height: 100vh;
			background-color: rgba(255, 255, 255, 0.75);
			z-index: 10000;
			position: fixed;
			display: flex;
			justify-content: center;
			align-items: center;
		}
		.loading > div {
			text-align: center;
		}
	</style>
</head>
<body>

	<div class="loading" style="display: none;" id="loading">
		<div>
			<div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
			  	<span class="visually-hidden">Loading...</span>
			</div>
		</div>
	</div>

	<?php include('templates/navbar.template.php') ?>

	<div class="container">
		<div class="row py-5">
			<div class="col-lg-4 offset-lg-4 col-md-6 offset-md-3">
				<form id="form">
					<div class="card px-4 py-4 mb-5 shadow-sm rounded-0">
						<div class="card-body">
								<h3 class="mb-3">Login</h3>

								<div class="row g-3 mb-4">
									<div class="col-12">
										<label class="form-label">Username</label>
										<input class="form-control form-control-sm rounded-0" type="text" placeholder="Username" required id="username"></input>
									</div>
									<div class="col-12">
										<label class="form-label">Password</label>
										<input class="form-control form-control-sm rounded-0" type="password" placeholder="Password" required id="password"></input>
									</div>
								</div>

								<p class="text-end mb-0">
									<button class="btn btn-primary rounded-0" type="submit">Login</button>
								</p>
						</div>
					</div>

				</form>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		$(document).ready( function () {

			$('#form').submit(function(e){

				e.preventDefault();

				var data = {
					username: $('#username').val(),
					password: $('#password').val()
				}

			    $.ajax({
			    	url: 'source/login.api.php',
			    	dataType: 'json',
			    	type: 'post',
			    	data: JSON.stringify(data),
			    	beforeSend: function(){
			    		console.log("Sending...")
			    		// $('#loading').fadeIn();
			    	},
			    	success: function(res){
			    		if(res) {
							window.location.replace("/");
			    		}
			    	},
			    	error: function(res){
			    		console.log(res)
			    		if (res.status === 401) {
							Swal.fire({
								title: 'Username or password is incorrect',
								icon: 'error',
								confirmButtonColor: '#a83232'
							})
			    		}
			    		if (res.status === 400) {
							Swal.fire({
								title: 'Bed request',
								text: res.responseJSON.msg,
								icon: 'error',
								confirmButtonColor: '#a83232'
							})
			    		}
			    		if (res.status === 404) {
							Swal.fire({
								title: 'Something wrong',
								icon: 'error',
								confirmButtonColor: '#a83232'
							})
			    		}
			    		$('#loading').fadeOut();
			    	}
			    })
			})
		} );
	</script>

	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
</body>
</html>