
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alliance</title>
    <link rel="stylesheet" href="tkthdr.css"> 
</head>
<body>
<header class="header_t">
<div class="brand-name">  
            <h1>Alliance</h1>
        </div>
        
        <div class="user-actions">
    <div class="profile-dropdown">
        <?php
                if (isset($_SESSION["username"])) {
                    // Fetch customer's first name from tbl_Customer
                    $loggedInUsername = $_SESSION["username"];
                    $getCustomerNameQuery = "SELECT C_fname FROM tbl_Customer WHERE Username = '$loggedInUsername'";
                    $nameResult = $conn->query($getCustomerNameQuery);

                    if ($nameResult->num_rows > 0) {
                        $nameRow = $nameResult->fetch_assoc();
                        $customerFirstName = $nameRow["C_fname"];

                        // Display the customer's first name in a different color
                        echo "<span class='customer-welcome'><br>Thank you <span class='name-highlight'>$customerFirstName</span> For Choosing Alliance!<br><div class='quote'>Safe travels on Alliance's wings – your voyage, our priority</div><br></span>";
                    }
                }
        ?>
        </button>
    </div>
</div>

    </header>
            </body>
            </html>