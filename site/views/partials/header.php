<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">

	<title>SCM4 Book Shop</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<link href="assets/main.css" rel="stylesheet">

</head>
<body>


<div class="container" id="header">


	<div class="navbar navbar-expand-lg navbar-light bg-light  fixed-top shadow gy-5">
		<div class="container-fluid">

			<a class="navbar-brand" href="/">SCM 4 Bookshop (v2.0)</a>

			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<li class="nav-item"><a class="nav-link  active" href="index.php">Home</a></li>
					<li class="nav-item"><a class="nav-link " href="index.php?view=list">List</a></li>
					<li class="nav-item"><a class="nav-link " href="index.php?view=checkout">Checkout</a></li>
				</ul>


				<form class="d-flex me-auto" action="/index.php" method="get">
					<input type="hidden" name="view" value="search" />
					<input class="form-control form-control-sm me-2" type="search" id="title" name="title" placeholder="Search book by title..." value="" aria-label="Search">
					<button class="btn btn-outline-success btn-sm" type="submit">Search</button>
				</form>
				<ul class="nav navbar-nav navbar-right login">
					<li  class="nav-item">
						<a href="index.php?view=checkout" class="nav-link" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Zum Checkout">
							<span class="badge bg-secondary">0</span>
							<span class="bi bi-cart4" aria-hidden="true"></span>
						</a>
					</li>
					<li class="nav-item dropdown">
						<a href="#" class="nav-link dropdown-toggle"  data-bs-toggle="dropdown" role="button" aria-expanded="false">
							Not logged in!
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu" role="menu">
							<li>
								<a class="dropdown-item" href="index.php?view=login">Login now</a>
							</li>
						</ul>
					</li>
				</ul> <!-- /. login -->
			</div><!--/.nav-collapse -->
		</div>
	</div>
</div>


<div class="container">