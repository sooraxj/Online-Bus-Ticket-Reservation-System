<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alliance</title>
    <link rel="stylesheet" href="tkthdr.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&family=Roboto:wght@400&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif; 
        }

        /* LOGO */
        .brand-name {
            height: 10vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 2%;
        }

        .brand-name h1 {
            margin-left: -20%;
            font-family: 'Dancing Script', cursive;
            font-weight: bold;
            font-size: 58px;
            color: #090979;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .brand-name h1:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease-in-out;
            color: #001F3F; 
        }

        .tagline {
            font-family: 'Roboto', sans-serif; 
            font-size: 20px;
            color: #4CAF50;
            margin-top: 5px;
        }

        .header_t {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            background-color: #c6bbbb22;
            color: #000000;
            border-radius: 10px;
        }

        .customer-welcome {
            font-weight: bold;
            margin-right: 10px;
        }

        .name-highlight {
            color: #ff0000;
        }

        .name-highlight:hover {
            text-decoration: none; 
            color: green;
            cursor: pointer; 
        }

        .quote {
            color: rgb(4, 90, 4);
        }
    </style>
</head>
<body>
    <header class="header_t">
        <div class="brand-name">
            <h1>Alliance</h1>
        </div>
        <p class="tagline">We'll Lead your way</p>

        <div class="user-actions">
            <div class="profile-dropdown">
            </div>
        </div>
    </header>
</body>
</html>
