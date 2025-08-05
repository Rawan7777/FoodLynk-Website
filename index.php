<!DOCTYPE html>
<html lang="en">

<head>
	
	<title>FoodLynk</title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="css/style.css" />
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet" />

</head>

<body>

	<div class="nav_bar">

		<h1 class="logo">FoodLynk</h1>
		
		<nav class="nav_links">
			<a href="client_login.php">Login</a>
			
			<?php session_start(); ?>

			<?php if (isset($_SESSION['brand_email'])): ?>
				<a href="brand_dashboard.php">Your Brand</a>

			<?php else: ?>
				<a href="brand_creation.php">Make Your Brand</a>

			<?php endif; ?>
		</nav>

	</div>
	

	<section class="hero">

		<div class="landing_text">
			<h1 class="more">More Than Just A Food</h1>
			<p class="text">
				FoodLynk is your gateway to a world of flavor, where restaurants, brands and even supermarkets build their own pages to 
				showcase unique menus. Whether you crave familiar favorites or want to discover something new, FoodLynk 
				connects you to the meals you love quickly, simply, and deliciously.
			</p>
		</div>

		<div class="img_container">
			<div class="image"></div>
		</div>

	</section>

	<h1 class="section_title">Restaurants</h1>

	<section class="brands">

		<div class="brand_item">
			<div class="brand" id="mc"></div>
			<p class="brand_name">McDonald's</p>
		</div>

		<div class="brand_item">
			<div class="brand" id="kfc"></div>
			<p class="brand_name">KFC</p>
		</div>

		<div class="brand_item">
			<div class="brand" id="bk"></div>
			<p class="brand_name">Burger King</p>
		</div>

		<div class="brand_item">
			<div class="brand" id="pizza"></div>
			<p class="brand_name">Pizza Hut</p>
		</div>

		<div class="brand_item">
			<div class="brand" id="subway"></div>
			<p class="brand_name">Subway</p>
		</div>

		<div class="brand_item">
			<div class="brand" id="starbucks"></div>
			<p class="brand_name">Starbucks</p>
		</div>

		<div class="brand_item">
			<div class="brand" id="dominos"></div>
			<p class="brand_name">Domino's</p>
		</div>

		<div class="brand_item">
			<div class="brand" id="popeyes"></div>
			<p class="brand_name">Popeyes</p>
		</div>

	</section>

	<h1 class="section_title">Super Markets</h1>
  
	<section class="brands">
		<div class="brand_item">
			<div class="brand" id="marjane"></div>
			<p class="brand_name">Marjane</p>
		</div>

		<div class="brand_item">
			<div class="brand" id="carrefour"></div>
			<p class="brand_name">Carrefour</p>
		</div>

		<div class="brand_item">
			<div class="brand" id="ikea"></div>
			<p class="brand_name">Ikea</p>
		</div>

		<div class="brand_item">
			<div class="brand" id="atacadao"></div>
			<p class="brand_name">Atacadaõ</p>
		</div>

	</section>

</body>
</html>
