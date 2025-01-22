<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <style>
        .footer {
            background-color: #333;
            color: #fff;
            padding: 20px 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            text-align: center;
        }
        .footer-column {
            flex: 1;
            margin: 10px;
            min-width: 200px;
        }
        .footer-column h3 {
            margin-bottom: 15px;
        }
        .footer-column ul {
            list-style: none;
            padding: 0;
        }
        .footer-column ul li {
            margin-bottom: 10px;
        }
        .footer-column ul li a {
            color: #fff;
            text-decoration: none;
        }
        .footer-column ul li a:hover {
            text-decoration: underline;
        }
        .social-media {
            margin-top: 20px;
        }
        .social-media a {
            margin: 0 10px;
            color: #fff;
            font-size: 1.5rem;
            text-decoration: none;
        }
        .social-media a:hover {
            color: #ffcc00;
        }
        @media (max-width: 768px) {
            .footer-column {
                flex: 1 1 100%;
            }
        }
    </style>
</head>
<body>
    <footer class="footer">
        <div class="footer-column">
            <h3>Contact</h3>
            <ul>
                <li><a href="#">Find a Dealer</a></li>
                <li><a href="#">Request a Test Drive</a></li>
            </ul>
        </div>
        <div class="footer-column">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="#">Build your BMW</a></li>
                <li><a href="#">BMW Financial Services</a></li>
            </ul>
        </div>
        <div class="footer-column">
            <h3>Experience BMW</h3>
            <ul>
                <li><a href="#">BMW Group</a></li>
                <li><a href="#">BMW Excellence Club</a></li>
            </ul>
        </div>
        <div class="footer-column">
            <h3>Legal</h3>
            <ul>
                <li><a href="#">Legal Disclaimer/Imprint</a></li>
                <li><a href="#">Privacy Policy</a></li>
            </ul>
        </div>
        <div class="social-media">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-youtube"></i></a>
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
            <a href="#"><i class="fab fa-x"></i></a>
        </div>
    </footer>
</body>
</html>