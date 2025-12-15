<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bus Search</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    /* Custom CSS */
    body {
      background-color: #f5f5f5;
    }
    .search-form {
      position: absolute;
      width: 70%;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: #fff;
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    /* Additional styling for suggestions dropdown */
    .custom-option {
      background-color: #f2f2f2;
      color: #333;
      padding: 5px 10px;
      border: 1px solid #ccc;
      border-radius: 3px;
      cursor: pointer;
    }
    .custom-option[value=""]::before {
      content: attr(value);
      background-color: #ccc;
      font-weight: bold;
      padding: 3px 6px;
      margin: 5px 0;
      border-radius: 3px;
    }
    .custom-option[selected] {
      background-color: yellow;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 col-md-10">
        <div class="search-form">
          <h2 class="text-center mb-4">Find Your Bus</h2>
          <form id="searchForm" method="post" action="search_stops.php">
            <div class="form-row">
              <div class="col-md-6">
                <input type="text" id="start" name="start" list="startStops" placeholder="Starting From" required class="form-control">
                <datalist id="startStops">
                  <?php
                  include "Dbconnect.php";
                  $sql = "SELECT DISTINCT Stop_name FROM tbl_Stop ORDER BY Stop_name ASC";
                  $result = $conn->query($sql);
                  while ($row = $result->fetch_assoc()) {
                    $stopName = $row['Stop_name'];
                    echo "<option value='$stopName'></option>";
                  }
                  ?>
                </datalist>
              </div>
              <div class="col-md-6">
                <input type="text" id="destination" name="destination" list="destinationStops" placeholder="Destination" required class="form-control">
                <datalist id="destinationStops">
                  <?php
                  $result = $conn->query($sql);
                  while ($row = $result->fetch_assoc()) {
                    $stopName = $row['Stop_name'];
                    echo "<option value='$stopName'></option>";
                  }
                  ?>
                </datalist>
              </div>
            </div>
            <div class="form-row mt-3">
              <div class="col-md-6">
                <input type="date" id="journeyDate" name="journeyDate" min="<?= date('Y-m-d'); ?>" required class="form-control">
              </div>
              <div class="col-md-6">
                <button type="submit" class="btn btn-primary btn-block">Search</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Your JavaScript code if needed
  </script>
</body>
</html>
