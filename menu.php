<?php

session_start();

$connection = mysqli_connect("localhost", "root", "", "foodlynk");

if(isset($_GET['brand_name']) || isset($_GET['refresh'])){

    $brand_name = $_GET['brand_name'];
    $_SESSION['brand_name'] = $brand_name;
    $query_meals = "SELECT * FROM mealsnew WHERE brand_name = '$brand_name'";
    $result_meals = mysqli_query($connection, $query_meals);
}

if(isset($_POST['ajax_add_to_cart'])){

    $brand_name = $_POST['brand_name'];
    $bougth_meal_name = $_POST['meal_name'];
    $meal_price = $_POST['meal_price'];

    if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

    if(isset($_SESSION['cart'][$bougth_meal_name])){

        $_SESSION['cart'][$bougth_meal_name]['qty'] += 1;

    } else {

        $_SESSION['cart'][$bougth_meal_name] = [
            'brand' => $brand_name,
            'qty' => 1,
            'price' => $meal_price
        ];
    }

    $cart_html = '';
    $total = 0;

    if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): 

        foreach($_SESSION['cart'] as $name => $item):

            $total += $item['price'] * $item['qty'];
            $cart_html .= '<li>' . $name . ' x' . $item['qty'] . ' - $' . ($item['price'] * $item['qty']) . '</li>';

        endforeach; 

    endif;

    echo json_encode([
        'success' => true,
        'cart_html' => $cart_html,
        'total' => $total,
        'has_items' => count($_SESSION['cart']) > 0
    ]);

    exit();
}

if(isset($_POST['buy'])){

    $brand_name = $_GET['brand_name'];
    $bougth_meal_name = $_GET['bougth_meal_name'];

    if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

    if(isset($_SESSION['cart'][$bougth_meal_name])){

        $_SESSION['cart'][$bougth_meal_name]['qty'] += 1;

    } else {

        $_SESSION['cart'][$bougth_meal_name] = [
            'brand' => $brand_name,
            'qty' => 1,
            'price' => $_GET['meal_price']
        ];
    }

    header("Location: menu.php?brand_name=".$brand_name);
    exit();
}

if(isset($_POST['logout'])){

    session_destroy();
    header("location: brand_login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Menu</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/menu.css" />
    
</head>

<body>

    <header class="navbar">

        <div class="nav-container">

            <h1 class="logo" style="cursor:pointer;" onclick="window.location.href='index.php';">FoodLynk</h1>

            <nav class="nav_links">

            <?php if (isset($_SESSION['client_email'])): ?>
                <a href="client_account.php">My account</a>

            <?php else: ?>
                <a href="client_login.php">Login</a>

            <?php endif; ?>

            <?php if (isset($_SESSION['brand_email'])): ?>
                <a href="brand_dashboard.php">My Brand</a>

            <?php else: ?>
                <a href="brand_creation.php">Make Your Brand</a>

            <?php endif; ?>
        </div>

    </header>

    <div class="menu-header">
    <a href="index.php" class="home-link">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Back to Home
    </a>

    <h1 class="section-title">
        <?php echo $brand_name ?> Menu
        <span class="title-underline"></span>
    </h1>
</div>


    <div class="menu-controls">

        <input type="text" id="searchMeal" placeholder="Search meals..." />
        
        <select id="filterCategory">
            <option value="">All Categories</option>
            <option value="starter">Starter</option>
            <option value="main">Main Course</option>
            <option value="drink">Drinks</option>
        </select>

        <select id="sortMeals">
            <option value="">Sort by</option>
            <option value="price-asc">Price: Low → High</option>
            <option value="price-desc">Price: High → Low</option>
        </select>

    </div>

    <div class="container">

        <div class="menu-grid" id="menuGrid">

            <?php 

            if(mysqli_num_rows($result_meals) > 0){

                $meal_counter = 1;

                while($meal = mysqli_fetch_assoc($result_meals)){

                    $badges = "";
                    if($meal['is_vegan']) $badges .= "<span class='badge vegan'>Vegan</span>";
                    if($meal['is_spicy']) $badges .= "<span class='badge spicy'>Spicy</span>";
                    if($meal['is_gluten_free']) $badges .= "<span class='badge gluten'>Gluten-Free</span>";
                    if($meal['is_nut_free']) $badges .= "<span class='badge nut-free'>Nut-Free</span>";
                    if($meal['is_halal']) $badges .= "<span class='badge halal'>Halal</span>";
                    if($meal['is_low_carb']) $badges .= "<span class='badge low-carb'>Low-Carb</span>";
                    if($meal['is_low_sugar']) $badges .= "<span class='badge low-sugar'>Low-Sugar</span>";
                    
                    if(empty($badges)) $badges .= "<span class='badge vegan'>None</span>";

                    $ratingStars = "";
                    for($i = 0; $i < 5; $i++){
                        $ratingStars .= ($i < $meal['rating']) ? "&#9733;" : "&#9734;";
                    }

                    echo '
                    <div class="meal-card" data-category="'.$meal['category'].'" data-price="'.$meal['meal_price'].'">
                        <div class="meal-image">
                            <img src="'.$meal['meal_image'].'" alt="Meal '.$meal_counter++.'">
                        </div>
                        <div class="meal-info">
                            <h4 class="meal-name">'.$meal['meal_name'].'</h4>
                            <div class="meal-badges">'.$badges.'</div>
                            <p class="meal-description">'.$meal['meal_description'].'</p>
                            <div class="meal-meta">
                                <span>Qty: '.($meal['meal_quantity']>0?$meal['meal_quantity']:'Unlimited').'</span>
                                <span>$'.$meal['meal_price'].'</span>
                            </div>
                            <div class="meal-rating">'.$ratingStars.'</div>
                            <button onclick="addToCart(\''.$meal['meal_name'].'\', \''.$_GET['brand_name'].'\', '.$meal['meal_price'].')" class="btn add-to-cart-btn">Add to Cart</button>
                        </div>
                    </div>';
                }
            }

            ?>

        </div>
    </div>

    <div class="sticky-cart" id="stickyCart">

        <h4>Cart</h4>

        <ul id="cartItems">

            <?php 

            if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): 

                $total = 0;
                
                foreach($_SESSION['cart'] as $name => $item):
                    $total += $item['price'] * $item['qty'];
            ?>
                <li><?php echo $name . ' x' . $item['qty'] . ' - $' . ($item['price'] * $item['qty']); ?></li>
            
            <?php 
                endforeach; 
            endif; 
            ?>

        </ul>

        <strong id="cartTotal">Total: $<?php echo isset($total) ? $total : '0'; ?></strong>

        <div id="checkoutSection" style="<?php echo (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) ? 'display:block;' : 'display:none;'; ?>">
            <form method="post" action="checkout.php" style="margin-top:10px;">
                <button type="submit" class="btn">Checkout</button>
            </form>
        </div>

    </div>

    <script>

        const searchInput = document.getElementById('searchMeal');
        const filterSelect = document.getElementById('filterCategory');
        const sortSelect = document.getElementById('sortMeals');
        const grid = document.getElementById('menuGrid');

        function filterMeals(){

            const search = searchInput.value.toLowerCase();
            const filter = filterSelect.value;
            const sort = sortSelect.value;
            let cards = Array.from(grid.getElementsByClassName('meal-card'));

            // Filter
            cards.forEach(card=>{
                const name = card.querySelector('.meal-name').innerText.toLowerCase();
                const category = card.dataset.category;
                card.style.display = (name.includes(search) && (filter=='' || filter==category)) ? 'flex':'none';
            });

            // Sort
            cards.sort((a,b)=>{
                let priceA = parseFloat(a.dataset.price);
                let priceB = parseFloat(b.dataset.price);
                if(sort=='price-asc') return priceA-priceB;
                if(sort=='price-desc') return priceB-priceA;
                return 0;
            });

            cards.forEach(card=>grid.appendChild(card));
        }

        searchInput.addEventListener('input', filterMeals);
        filterSelect.addEventListener('change', filterMeals);
        sortSelect.addEventListener('change', filterMeals);

        function addToCart(mealName, brandName, mealPrice) {

            const button = event.target;
            const originalText = button.textContent;
            
            button.disabled = true;
            button.textContent = 'Adding...';
            
            fetch('menu.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `ajax_add_to_cart=1&meal_name=${encodeURIComponent(mealName)}&brand_name=${encodeURIComponent(brandName)}&meal_price=${mealPrice}`
            })

            .then(response => response.json())
            .then(data => {
                if(data.success) {

                    document.getElementById('cartItems').innerHTML = data.cart_html;
                    document.getElementById('cartTotal').textContent = `Total: $${data.total}`;
                    
                    const checkoutSection = document.getElementById('checkoutSection');
                    checkoutSection.style.display = data.has_items ? 'block' : 'none';
                    
                    button.textContent = 'Added!';
                    button.style.backgroundColor = '#28a745';
                    
                    setTimeout(() => {
                        button.textContent = originalText;
                        button.style.backgroundColor = '';
                        button.disabled = false;
                    }, 1000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                button.textContent = 'Error';
                setTimeout(() => {
                    button.textContent = originalText;
                    button.disabled = false;
                }, 1000);
            });
        }

    </script>

</body>
</html>