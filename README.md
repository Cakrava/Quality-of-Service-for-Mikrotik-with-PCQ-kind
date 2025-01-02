<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Website</title>
    <style>
        /* Global styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        
        header {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header .logo {
            font-size: 24px;
            font-weight: bold;
        }
        header nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
        }
        header nav a:hover {
            text-decoration: underline;
        }
        
        .hero {
            background: url('https://via.placeholder.com/1500x500') no-repeat center center/cover;
            color: #fff;
            text-align: center;
            padding: 100px 20px;
        }
        .hero h1 {
            font-size: 48px;
            margin: 0;
        }
        .hero p {
            font-size: 20px;
            margin: 10px 0 30px;
        }
        .hero button {
            padding: 10px 20px;
            background-color: teal;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .hero button:hover {
            background-color: darkcyan;
        }
        
        .products {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .product {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            transition: box-shadow 0.3s;
        }
        .product:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .product img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .product .info {
            padding: 15px;
        }
        .product .info h3 {
            margin: 0 0 10px;
            font-size: 18px;
        }
        .product .info p {
            margin: 0 0 15px;
            color: #666;
        }
        .product .info .price {
            font-size: 20px;
            color: #333;
            font-weight: bold;
        }
        .product .info button {
            padding: 10px 20px;
            background-color: teal;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .product .info button:hover {
            background-color: darkcyan;
        }
        
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">MyShop</div>
        <nav>
            <a href="#">Home</a>
            <a href="#">Shop</a>
            <a href="#">About</a>
            <a href="#">Contact</a>
        </nav>
    </header>

    <div class="hero">
        <h1>Welcome to MyShop</h1>
        <p>Discover the best products at unbeatable prices.</p>
        <button>Shop Now</button>
    </div>

    <section class="products">
        <div class="product">
            <img src="https://via.placeholder.com/300x200" alt="Product 1">
            <div class="info">
                <h3>Product Name 1</h3>
                <p>Short product description goes here.</p>
                <p class="price">$29.99</p>
                <button>Add to Cart</button>
            </div>
        </div>
        <div class="product">
            <img src="https://via.placeholder.com/300x200" alt="Product 2">
            <div class="info">
                <h3>Product Name 2</h3>
                <p>Short product description goes here.</p>
                <p class="price">$49.99</p>
                <button>Add to Cart</button>
            </div>
        </div>
        <div class="product">
            <img src="https://via.placeholder.com/300x200" alt="Product 3">
            <div class="info">
                <h3>Product Name 3</h3>
                <p>Short product description goes here.</p>
                <p class="price">$19.99</p>
                <button>Add to Cart</button>
            </div>
        </div>
    </section>

    <footer>
        &copy; 2025 MyShop. All Rights Reserved.
    </footer>
</body>
</html>
