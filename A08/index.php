<?php
include("connect.php");

$airlineTypeFilter = $_GET['airlineType']??'';
$departureAirportFilter = $_GET['departureAirport']??'';
$arrivalAirportFilter = $_GET['arrivalAirport']??'';
$sort = $_GET['sort']??'';
$order = $_GET['order']??'';

$flightQuery = "SELECT * FROM flightLogs";

if ($airlineTypeFilter != '' || $departureAirportFilter != '' || $arrivalAirportFilter != '') {
    $flightQuery .= " WHERE";

    if ($airlineTypeFilter != '') {
        $flightQuery .= " aircraftType='$airlineTypeFilter'";
    }

    if ($airlineTypeFilter != '' && ($departureAirportFilter != '' || $arrivalAirportFilter != '')) {
        $flightQuery .= " AND";
    }

    if ($departureAirportFilter != '') {
        $flightQuery .= " departureAirportCode='$departureAirportFilter'";
    }

    if ($departureAirportFilter != '' && $arrivalAirportFilter != '') {
        $flightQuery .= " AND";
    }

    if ($arrivalAirportFilter != '') {
        $flightQuery .= " arrivalAirportCode='$arrivalAirportFilter'";
    }
}

if ($sort != '') {
    $flightQuery .= " ORDER BY $sort $order";
}

$flightResults = executeQuery($flightQuery);

$airlineTypeQuery = "SELECT DISTINCT(aircraftType) FROM flightLogs";
$airlineTypeResults = executeQuery($airlineTypeQuery);

$departureAirportQuery = "SELECT DISTINCT(departureAirportCode) FROM flightLogs";
$departureAirportResults = executeQuery($departureAirportQuery);

$arrivalAirportQuery = "SELECT DISTINCT(arrivalAirportCode) FROM flightLogs";
$arrivalAirportResults = executeQuery($arrivalAirportQuery);
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Airport Flight Logs</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    crossorigin="anonymous">
  <style>
    body {
      background-color: #f4f8fb;
    }

    .header {
      background-color:rgb(83, 10, 0);
      color:#fccc3c;
      padding: 20px;
      text-align: center;
    }

    .header h1 {
      font-weight: bold;
    }

    .filter-card {
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
      background-color: #ffc107;
      border: none;
    }

    .btn-primary:hover {
      background-color:#fccc3c;
    }

    .table-hover tbody tr:hover {
      background-color: #f1f3f5;
    }

    .table-dark th {
      background-color:rgb(83, 10, 0);
      color:#fccc3c;
    }
  </style>
</head>

<body>
  <div class="header">
    <h1>PUP Airport Flight Logs</h1>
    <p>Track flights, airlines, and passenger statistics.</p>
  </div>

  <div class="container my-5">
    <form>
  <div class="card p-4 filter-card">
    <h5 class="mb-3">Filter Flights</h5>
    <div class="row">
      <div class="col-md-4">
        <label for="airlineType" class="form-label">Airline Type</label>
        <select id="airlineType" name="airlineType" class="form-select">
          <option value="">Any</option>
          <?php
          if (mysqli_num_rows($airlineTypeResults) > 0) {
            while ($row = mysqli_fetch_assoc($airlineTypeResults)) {
              ?>
              <option 
                <?php if ($airlineTypeFilter == $row['aircraftType']) {echo "selected";} ?> 
                value="<?php echo $row['aircraftType']; ?>">
                <?php echo $row['aircraftType']; ?>
              </option>
              <?php
            }
          }
          ?>
        </select>
      </div>

      <div class="col-md-4">
        <label for="departureAirport" class="form-label">Departure Airport</label>
        <select id="departureAirport" name="departureAirport" class="form-select">
          <option value="">Any</option>
          <?php
          if (mysqli_num_rows($departureAirportResults) > 0) {
            while ($row = mysqli_fetch_assoc($departureAirportResults)) {
              ?>
              <option 
                <?php if ($departureAirportFilter == $row['departureAirportCode']) {echo "selected";} ?> 
                value="<?php echo $row['departureAirportCode']; ?>">
                <?php echo $row['departureAirportCode']; ?>
              </option>
              <?php
            }
          }
          ?>
        </select>
      </div>

      <div class="col-md-4">
        <label for="arrivalAirport" class="form-label">Arrival Airport</label>
        <select id="arrivalAirport" name="arrivalAirport" class="form-select">
          <option value="">Any</option>
          <?php
          if (mysqli_num_rows($arrivalAirportResults) > 0) {
            while ($row = mysqli_fetch_assoc($arrivalAirportResults)) {
              ?>
              <option 
                <?php if ($arrivalAirportFilter == $row['arrivalAirportCode']) {echo "selected";} ?> 
                value="<?php echo $row['arrivalAirportCode']; ?>">
                <?php echo $row['arrivalAirportCode']; ?>
              </option>
              <?php
            }
          }
          ?>
        </select>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-md-6">
        <label for="sort" class="form-label">Sort By</label>
        <select id="sort" name="sort" class="form-select">
          <option value="">None</option>
          <option value="flightNumber" <?= $sort == 'flightNumber' ? 'selected' : '' ?>>Flight Number</option>
          <option value="airlineName" <?= $sort == 'airlineName' ? 'selected' : '' ?>>Airline Name</option>
        </select>
      </div>

      <div class="col-md-6">
        <label for="order" class="form-label">Order</label>
        <select id="order" name="order" class="form-select">
          <option value="ASC" <?= $order == 'ASC' ? 'selected' : '' ?>>Ascending</option>
          <option value="DESC" <?= $order == 'DESC' ? 'selected' : '' ?>>Descending</option>
        </select>
      </div>
    </div>

    <div class="text-center mt-4">
      <button type="submit" class="btn btn-primary">Apply Filters</button>
    </div>
  </div>
</form>


    <div class="card mt-5">
      <table class="table table-hover">
        <thead class="table-dark">
          <tr>
            <th>Flight Number</th>
            <th>Airline Name</th>
            <th>Airline Type</th>
            <th>Departure</th>
            <th>Arrival</th>
            <th>Passengers</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if (mysqli_num_rows($flightResults) > 0) {
              while ($row = mysqli_fetch_assoc($flightResults)) {
                  echo "<tr>
                          <td>{$row['flightNumber']}</td>
                          <td>{$row['airlineName']}</td>
                          <td>{$row['aircraftType']}</td>
                          <td>{$row['departureAirportCode']}</td>
                          <td>{$row['arrivalAirportCode']}</td>
                          <td>{$row['passengerCount']}</td>
                        </tr>";
              }
          } else {
              echo "<tr><td colspan='6' class='text-center'>No records found</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>
