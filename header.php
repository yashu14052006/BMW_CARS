<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Header</title>
    <style>
        nav {
    display: flex;
    align-items: center;
    background-color: rgba(147, 146, 146, 0.5);
    margin-top: -4%;
    padding: 10px 20px;
    position: sticky;
    top: 0;
    z-index: 1000;
}

nav .logo {
    color: black;
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
    color: black;
    font-size: 1rem;
    transition: color 0.3 ease;
}

nav .nav-links a:hover {
    color: #ffcc00;
}
    </style>
</head>
<body>
<nav>
    <div class="logo">Fusion Cars</div>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="#categories">Categories</a></li>
        <li><a href="#cars">Contact Us</a></li>
        <li><a href="#contact">About Us</a></li>
    </ul>
</nav>
</body>
</html>
