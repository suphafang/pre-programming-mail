<?php
	session_start();
	if(!isset($_SESSION['authorization']) || !$_SESSION['authorization']) {
		include('login.php');
		die();
	}
?>
<?php
	require('source/config.php');
	require('source/encrypt_function.php');

	if(!isset($_GET['hash']) || decrypt($_GET['hash']) === '') {
		http_response_code(400);
		include('400.html');
		die();
	}

	$conn = conn::mysqli();

	$stmt = $conn->prepare("SELECT * FROM list LEFT JOIN email_log ON list.list_id = email_log.email_log_recipient_id WHERE list.list_id = ? LIMIT 1");
	$stmt->bind_param("s", $id);

	$id = decrypt($_GET['hash']);

	$stmt->execute();
	$result = $stmt->get_result();

	$data = $result->fetch_object();

	// if ($result->num_rows < 1 || $data->email_log_id !== null) {
	// 	http_response_code(400);
	// 	include('400.html');
	// 	die();
	// }

	if ($result->num_rows < 1) {
		http_response_code(400);
		include('400.html');
		die();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<link rel="icon" href="icon.png">
	<title>Detail of <?php echo $data->list_firstname.' '.$data->list_lastname; ?> | Pre-programming Mail</title>
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
			<div class="col-xl-8 offset-xl-2">
				<form id="form">
					<div class="card px-4 py-5 mb-5 shadow-sm rounded-0">
						<div class="card-body">
								<h3 class="mb-3">Recipients</h3>

								<div class="row g-3 mb-4">
									<div class="col-12">
										<label class="form-label">Full name</label>
										<input class="form-control form-control-sm rounded-0" type="text" placeholder="Full name" required readonly value="<?php echo $data->list_firstname.' '.$data->list_lastname; ?>" id="name"></input>
									</div>
									<div class="col-12">
										<label class="form-label">E-mail address</label>
										<input class="form-control form-control-sm rounded-0" type="email" placeholder="E-mail address" required readonly value="<?php echo $data->list_email; ?>" id="email"></input>
									</div>
								</div>

								<h3 class="mb-3">Body</h3>

								<div class="row g-3">
									<div class="col-12">
										<label class="form-label">E-Judge Username</label>
										<input class="form-control form-control-sm rounded-0" type="text" placeholder="E-Judge Username" required readonly value="<?php echo $data->list_ejudge_username; ?>" id="username"></input>
									</div>
									<div class="col-12">
										<label class="form-label">E-Judge Password</label>
										<input class="form-control form-control-sm rounded-0" type="text" placeholder="E-Judge Password" required readonly value="<?php echo $data->list_ejudge_password; ?>" id="password"></input>
									</div>
									<!-- <div class="col-12">
										<label class="form-label">Question & Comment</label>
										<textarea class="form-control form-control-sm rounded-0" placeholder="Question" rows="4" required readonly id="question"><?php 
											if ($data->list_comment === '') {
												echo '-';
											} else {
												echo $data->list_comment;
											}
										?></textarea>
									</div> -->
									<!-- <div class="col-12">
										<label class="form-label">Reply</label>
										<textarea class="form-control form-control-sm rounded-0" placeholder="Reply" rows="4" required autofocus id="answer"></textarea>
									</div>
									<div class="col-12">
										<label class="form-label">Sender name</label>
										<input class="form-control form-control-sm rounded-0" type="text" placeholder="Sender name" required id="senior"></input>
									</div> -->
								</div>
							
						</div>
					</div>

					<p class="text-end">
						<button class="btn btn-outline-secondary rounded-0 me-md-2" onclick="window.history.back();" type="button">Back</button>
						<!-- <button class="btn btn-primary rounded-0" type="submit">Send email</button> -->
					</p>

				</form>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		$(document).ready( function () {

			$('#form').submit(function(e){

				e.preventDefault();

				var data = {
					name: $('#name').val(),
					email: $('#email').val(),
					username: $('#username').val(),
					password: $('#password').val(),
					question: $('#question').val(),
					answer: $('#answer').val(),
					senior: $('#senior').val(),
					hash: '<?php echo $_GET['hash']; ?>'
				}

			    $.ajax({
			    	url: 'source/sendmail.api.php',
			    	dataType: 'json',
			    	type: 'post',
			    	data: JSON.stringify(data),
			    	beforeSend: function(){
			    		console.log("Sending...")
			    		$('#loading').fadeIn();
			    	},
			    	success: function(res){
			    		if(res) {
							window.location.replace("/");
			    		}
			    	},
			    	error: function(res){
			    		if (res.status === 400) {
							Swal.fire({
								title: 'Bed request',
								text: res.responseJSON.msg,
								icon: 'error',
								confirmButtonColor: '#a83232'
							})
			    		}
			    		if (res.status === 401) {
							Swal.fire({
								title: 'Unauthorized',
								text: 'Please login.',
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