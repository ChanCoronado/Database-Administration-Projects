<?php
include("connect.php");

$query = "SELECT * FROM posts LEFT JOIN userInfo ON posts.userID = userInfo.userID";
$results = executeQuery(query: $query);
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Twitter</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    body {
      background-color: #15202b;
      color: #ffffff;
      font-family: Arial, sans-serif;
    }

    .card {
      background-color: #192734;
      border: none;
      border-bottom: 1px solid #2f3336;
      border-radius: 0;
      margin-bottom: 15px;
      padding: 15px;
    }

    .logo-container {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .logo-img {
      max-width: 36px; 
      height: auto;
      margin-right: 10px;
    }

    .profile-img {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      object-fit: cover;
    }

    .text-twitter {
      color: #1da1f2;
    }

    .action-btn {
      color: #8899a6;
      background: none;
      border: none;
      font-size: 1.1rem;
    }

    .action-btn:hover {
      color: #1da1f2;
    }

    .post-content {
      font-size: 1rem;
      color: #e1e8ed;
    }

    .timestamp {
      font-size: 0.8rem;
      color: #8899a6;
    }
  </style>
</head>

<body>
  <div class="container mt-5">
    <div class="row mb-4">
      <div class="col text-center">
        <img src="Images/twitter.png" alt="Twitter Logo" class="logo-img">
        <h1 class="text-twitter d-inline-block align-middle">Twitter</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <?php
        if (mysqli_num_rows($results) > 0) {
          while ($post = mysqli_fetch_assoc($results)) {
            ?>
            <div class="card">
              <div class="d-flex align-items-start">
                <img src="Images/<?php echo $post['attachment'] ?>" alt="Profile Picture" class="profile-img me-3">
                <div class="w-100">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <h5 class="m-0 text-white"><?php echo $post['firstName'] . " " . $post['lastName'] ?></h5>
                    </div>
                    <small class="timestamp"><?php echo date("M j", strtotime($post['dateTime'])) ?></small>
                  </div>
                  <p class="post-content mt-1 mb-2"><?php echo $post['content'] ?></p>
                  <div class="d-flex justify-content-start">
                    <button class="action-btn me-3"><i class="far fa-comment"></i> <span class="ms-1">4</span></button>
                    <button class="action-btn me-3"><i class="fas fa-retweet"></i> <span class="ms-1">10</span></button>
                    <button class="action-btn me-3"><i class="far fa-heart"></i> <span class="ms-1">15</span></button>
                    <button class="action-btn"><i class="fas fa-share"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <?php
          }
        }
        ?>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
