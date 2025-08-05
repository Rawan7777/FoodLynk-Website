<?php 

session_start();

$connection = mysqli_connect("localhost", "root", "", "foodlynk");

$query = "SELECT * FROM brands WHERE status = 'approve'";

$result = mysqli_query($connection, $query);

?>

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
			<a href="" class="brand_anchor" title="Visit McDonald's">
				<img src="images/mc.png" alt="McDonald's logo" />
			</a>
			<p class="brand_name">McDonald's</p>
		</div>

		<div class="brand_item">
			<a href="" class="brand_anchor" title="Visit KFC">
				<img src="images/kfc.png" alt="KFC logo" />
			</a>
			<p class="brand_name">KFC</p>
		</div>

		<div class="brand_item">
			<a href="" class="brand_anchor" title="Visit Burger King">
				<img src="images/burgerking.png" alt="Burger King logo" />
			</a>
			<p class="brand_name">Burger King</p>
		</div>

		<div class="brand_item">
			<a href="" class="brand_anchor" title="Visit Pizza Hut">
				<img src="images/pizzahut.png" alt="Pizza Hut logo" />
			</a>
			<p class="brand_name">Pizza Hut</p>
		</div>

		<?php 
		
		$counter = 1;

		if(mysqli_num_rows($result) > 0 && $counter < 2){

			while($row = mysqli_fetch_assoc($result)){

			echo '	<div class="brand_item">
						<a href="" class="brand_anchor" title="Visit ' . $row['brand_name'] . '">
							<img src="' . $row['brand_image'] . '" alt="' . $row['brand_name'] . ' logo" />
						</a>
						<p class="brand_name">' . $row['brand_name'] . '</p>
					</div>';

			}
			$counter++;
		}
		?>


		<div class="brand_item">
			<a href="" class="brand_anchor" title="Visit Subway">
				<img src="images/subway.png" alt="Subway logo" />
			</a>
			<p class="brand_name">Subway</p>
		</div>

		<div class="brand_item">
			<a href="" class="brand_anchor" title="Visit Starbucks">
				<img src="images/starbucks.png" alt="Starbucks logo" />
			</a>
			<p class="brand_name">Starbucks</p>
		</div>

		<div class="brand_item">
			<a href="" class="brand_anchor" title="Visit Domino's">
				<img src="images/dominos.png" alt="Domino's logo" />
			</a>
			<p class="brand_name">Domino's</p>
		</div>

		<div class="brand_item">
			<a href="" class="brand_anchor" title="Visit Popeyes">
				<img src="images/popeyes.png" alt="Popeyes logo" />
			</a>
			<p class="brand_name">Popeyes</p>
		</div>


	</section>

	<h1 class="section_title">Super Markets</h1>
  
	<section class="brands">

		<div class="brand_item">
			<a href="" class="brand_anchor" title="Visit Marjane">
				<img src="images/marjane.png" alt="Marjane logo" />
			</a>
			<p class="brand_name">Marjane</p>
		</div>

		<div class="brand_item">
			<a href="" class="brand_anchor" title="Visit Carrefour">
				<img src="images/carrefour.png" alt="Carrefour logo" />
			</a>
			<p class="brand_name">Carrefour</p>
		</div>

		<div class="brand_item">
			<a href="" class="brand_anchor" title="Visit Ikea">
				<img src="images/ikea.png" alt="Ikea logo" />
			</a>
			<p class="brand_name">Ikea</p>
		</div>

		<div class="brand_item">
			<a href="" class="brand_anchor" title="Visit Atacadaõ">
				<img src="images/atacadao.png" alt="Atacadaõ logo" />
			</a>
			<p class="brand_name">Atacadaõ</p>

		</div>
	</section>


</body>
</html>
