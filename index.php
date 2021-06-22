<?php
	session_start();
	if(!isset($_SESSION['authorization']) || !$_SESSION['authorization']) {
		include('login.php');
		die();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css"> -->
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css">

	<script src="https://code.jquery.com/jquery-3.6.0.js"></script>

	<script type="text/javascript" src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="/js/dataTables.bootstrap5.min.js"></script>

	<link rel="icon" href="icon.png">

	<title>Pre-programming Mail</title>

	<style type="text/css">
		@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Sarabun:wght@300;400;500;600;700&display=swap');
		* {
			font-family: 'Roboto', 'Sarabun', sans-serif;
		}
		body {
			background: #eeeeee;
		}
	</style>
</head>
<body>

	<?php include('templates/navbar.template.php') ?>

	<div class="bg-dark text-light rounded-0 p-5 mb-5 shadow-sm">
		<div class="container">
			<h1>Pre-programming Mail</h1>
			<p>Pre-programming's 2021 event.</p>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="card shadow-sm rounded-0 mb-5">
					<div class="card-body">
						<table id="myTable" class="table table-bordered w-100">
							<thead>
								<tr>
									<th>Firstname</th>
									<th>Lastname</th>
									<th>E-mail</th>
									<th>Status</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		$(document).ready( function () {
		    var listTable = $('#myTable').DataTable({
		    	responsive: true,
		    	ajax: {
			        url: '/source/list.api.php',
			        dataSrc: ''
			    },
			    columns: [
			        { data: 'FIRSTNAME' },
			        { data: 'LASTNAME' },
			        { data: 'EMAIL' },
			        {
			        	data: null,
			        	render: function (data, type, row) {
			        		// if (row.SEND) {
			        		// 	return '<span class="badge bg-success rounded-0" style="font-weight: 500;">Sent</span>';
			        		// } else {
			        			return '<a class="btn btn-outline-primary btn-sm rounded-0" href="send.php?hash='+row.HASH+'">View</a>';
			        		// }
        				}
			        }
			    ],
			    columnDefs: [
	                { className: 'text-center', targets: [3] },
	                { className: 'align-middle', targets: '_all' }
	            ]
		    });

		    setInterval( function () {
			    listTable.ajax.reload( null, false ); // user paging is not reset on reload
			}, 2*1000 );
		} );
	</script>

	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
</body>
</html>
