<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BMW Customer Support</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            
        }
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #333;
            padding: 10px 20px;
        }
        nav .logo {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 600;
        }
        nav .nav-links {
            list-style: none;
            display: flex;
        }
        nav .nav-links li {
            margin-left: 20px;
        }
        nav .nav-links a {
            text-decoration: none;
            color: #fff;
            font-size: 1rem;
            transition: color 0.3s ease;
        }
        nav .nav-links a:hover {
            color: #ffcc00;
        }
        .hero {
            position: relative;
            width: 100%;
            height: 60vh;
            background: url('car-background.jpg') no-repeat center center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            text-align: center;
        }
        .hero h1 {
            font-size: 3rem;
            margin: 0;
        }
        .section {
            padding: 40px 20px;
            text-align: center;
        }
        .section h2 {
            font-size: 2rem;
            margin-bottom: 20px;
        }
        .icons {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .icon {
            width: 150px;
            text-align: center;
        }
        .icon img {
            width: 100px;
            height: 100px;
        }
        .contacts {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
        }
        .contact {
            width: 45%;
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .contact img {
            width: 100%;
            border-radius: 8px;
        }
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <?php include('header.php'); ?>

    <div class="hero">
        <h1>BMW Customer Support</h1>
    </div>

    <div class="section">
        <h2>BMW Useful Relationships</h2>
        <div class="icons">
            <div class="icon">
                <img src="./assets/images/M.jpg" alt="Models and technologies">
                <h3>Models and technologies</h3>
            </div>
            <div class="icon">
                <img src="./assets/images/M1.jpg" alt="Financial services">
                <h3>Financial services</h3>
            </div>
            <!-- Add more icons as needed -->
        </div>
    </div>

    <div class="section">
        <h2>BMW Dealer Contacts</h2>
        <div class="contacts">
            <div class="contact">
                <img src="./assets/images/M2.jpg" alt="Dealer 1">
                <p>Contact details for Dealer 1</p>
            </div>
            <div class="contact">
                <img src="" alt="Dealer 2">
                <p>Contact details for Dealer 2</p>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>BMW India Customer Interaction Centre</h2>
        <div class="contacts">
            <div class="contact">
                <img src="" alt="Interaction Centre 1">
                <p>Details for Interaction Centre 1</p>
            </div>
            <div class="contact">
                <img src="interaction2.jpg" alt="Interaction Centre 2">
                <p>Details for Interaction Centre 2</p>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>