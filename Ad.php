<?php include 'Dbconnect.php'; ?>
<html>
    <head>
        <title>Admin-Panel</title>
        <link rel="stylesheet" href="admin.css">
    </head>
    <body>

          <!-- <script>
        const sideMenu = document.querySelector('.side-menu');
        const toggleButton = document.querySelector('.toggle-button');

        toggleButton.addEventListener('click', () => {
            sideMenu.classList.toggle('collapsed');
        });
    </script>
        -->
        <div class="side-menu">
            <div class="brand-name">
                <div class="logo">
                    <img src="logo2.png">
                </div>     
            <h1>Alliance</h1>
        </div>
       

        <ul class="ui">
          <li><a href="admin.php"><img src="ui.png"> Dashboard </a></li>
          <li><a href="Driver.php"><img src="ui.png">Drivers</a></li>
          <li><a href="Cust.php"><img src="ui.png">Customers</a></li>
          <li><a href="Bus.php"><img src="ui.png">Buses</a></li>
          <li><a href="Route.php"><img src="ui.png">Routes</a></li>
          <li><a href="Stop.php"><img src="ui.png">Stops</a></li>
         <li><a href="Driverbus.php"><img src="ui.png">Driver-Bus</a></li>
  <li><a href="Busroute.php"><img src="ui.png">Bus-Route</a></li>
          <li><a href="Farestage.php"><img src="ui.png">Fare Stage</a></li>
        </ul>
        </div>

        <div class="container">
            <div class="header">
                <div class="nav">
          <button class="toggle-button" id="menu">|||</button>
                <div class="search">
                    <input type="text" placeholder="search...">
                    <button type="submit">🔍</button>
                </div>
                <div class="user">
                <a href="#" class=""></a>
                <div class="img-case">
                    <img src="user.png" alt="user" id="userImage">
                </div>
                <div class="dropdown-container" id="dropdownContainer">
                <a href="Logout.php" class="btn">Logout</a>
                </div>
            </div>
                </div>
            </div>
            <div class="content">
                    <section class="hero">
                        <div class="hero-content">
                          <h1>Welcome, Admin</h1>
                          <p>Manage your bus ticket reservation system with ease</p>
                          <a href="Admin.php" class="btn">Get Started</a>
                        </div>
                      </section>
            </section>
            <section class="features">
              <h1>Features</h1>
              <br>
              <div class="feature">
                <img src="feature1.jpg" alt="Feature 1">
                <h3>Manage Tickets</h3>
                <p>View, edit, and delete bus tickets</p>
                <img src="feature2.jpg" alt="Feature 2">
                <h3>Manage Buses</h3>
                <p>Add, update, and remove bus details</p>
                <img src="feature3.jpg" alt="Feature 3">
                <h3>Manage Drivers</h3>
                <p>Manage Driver Details and routes</p>
              </div>
            </section>
        
                
            </div>
        </div>
    </body>
</html>