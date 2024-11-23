<?php
include("connect.php");

if (isset($_POST['btnDelete'])) {
  $postID = $_POST['postID'];
  $deleteQuery = "DELETE FROM posts WHERE postID = '$postID'";
  executeQuery($deleteQuery);
  header("Location: index.php");
  exit();
}

if (isset($_POST['btnPost'])) {
  $userID = $_POST['userID'];
  $content = $_POST['content'];
  $dateTime = date("Y-m-d H:i:s");
 
  $postsQuery = "INSERT INTO posts (postID, userID, content, dateTime, privacy, isDeleted, attachment, cityID, provinceID) VALUES (NULL, '$userID', '$content', '$dateTime', 'public', 'no', '', 2, 2)";
  executeQuery($postsQuery);

  header("Location: index.php");
  exit();
}

if (isset($_POST['btnEdit'])) {
  $postID = $_POST['postID'];
  $editedContent = $_POST['editedContent'];
  $editQuery = "UPDATE posts SET content = '$editedContent' WHERE postID = '$postID'";
  executeQuery($editQuery);
  header("Location: index.php");
  exit();
}

$query = "SELECT * FROM posts LEFT JOIN userInfo ON posts.userID = userInfo.userID ORDER BY dateTime DESC";
$results = executeQuery($query);
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Twitter</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    body {
      background-color: #15202b;
      color: #ffffff;
      font-family: Arial, sans-serif;
    }

    .sidebar {
      background-color: #15202b;
      padding: 20px;
      position: sticky;
      top: 0;
      height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .sidebar .menu-item {
      color: #8899a6;
      text-decoration: none;
      font-size: 1.2rem;
      padding: 15px;
      display: flex;
      align-items: center;
      border-radius: 50px;
      transition: background-color 0.3s ease;
      width: 100%;
      text-align: center;
    }

    .sidebar .menu-item:hover {
      background-color: #3d3d3d4b;
      color: #ffffff;
    }

    .sidebar .menu-item i {
      margin-right: 8px;
    }

    .card,
    .post-form {
      background-color: #192734;
      border: none;
      border-radius: 16px;
      padding: 15px;
      margin-bottom: 15px;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover,
    .post-form:hover {
      transform: translateY(-4px);
      box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.4);
    }

    .post-form textarea {
      background-color: #192734;
      color: #ffffff;
      border: 1px solid #2f3336;
      border-radius: 12px;
      padding: 10px;
      resize: none;
      font-size: 1rem;
      width: 100%;
    }

    .post-form button {
      background-color: #1da1f2;
      color: #ffffff;
      border: none;
      padding: 8px 16px;
      border-radius: 24px;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }

    .post-form button:hover {
      background-color: #0d8cd2;
    }

    .action-btn {
      color: #8899a6;
      background: none;
      border: none;
      font-size: 1.1rem;
      margin-right: 20px;
      transition: color 0.3s ease;
    }

    .action-btn:hover {
      color: #1da1f2;
    }

    .timestamp {
      font-size: 0.8rem;
      color: #8899a6;
    }

    .profile-img {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      object-fit: cover;
    }

    .tweet-header {
      border-bottom: 1px solid #2f3336;
      padding: 10px 0;
    }

    .tweet-header h1 {
      font-size: 1.5rem;
      color: #1da1f2;
    }

    .post-icons {
      display: flex;
      gap: 10px;
      color: #1da1f2;
      font-size: 1.2rem;
    }

    .post-btn {
      background-color: #1da1f2;
      color: #ffffff;
      border: none;
      border-radius: 20px;
      padding: 5px 15px;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .post-btn:hover {
      background-color: #1991db;
    }

    .rounded-btn {
      color: #3585c7;
      background-color: #15202b;
      border: 1px solid #4fa2d9;
      border-radius: 24px;
      padding: 8px 16px;
      font-size: 0.9rem;
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    .rounded-btn:hover {
      color: #ffffff;
      background-color: #d9534f;
    }

    .post-content {
      color: #ffffff;
    }

    .modal-content {
      background-color: #192734;
      color: #ffffff;
      border-radius: 16px;
      padding: 15px;
    }

    .modal-header {
      border-bottom: 1px solid #2f3336;
    }

    .modal-footer {
      border-top: 1px solid #2f3336;
    }

    .edit-btn {
      background-color: #3585c7;
      color: white;
      border-radius: 24px;
      padding: 8px 16px;
      border: none;
      transition: background-color 0.3s;
    }

    .edit-btn:hover {
      background-color: #1991db;
    }
  </style>
</head>

<body>
  <div class="container-fluid">
    <div class="row">
      <nav class="col-md-3 col-lg-2 sidebar d-md-flex d-none flex-column align-items-center">
        <a href="#" class="menu-item"><i class="fas fa-home"></i> Home</a>
        <a href="#" class="menu-item"><i class="fas fa-hashtag"></i> Explore</a>
        <a href="#" class="menu-item"><i class="fas fa-bell"></i> Notifications</a>
        <a href="#" class="menu-item"><i class="fas fa-envelope"></i> Messages</a>
        <a href="#" class="menu-item"><i class="fas fa-bookmark"></i> Bookmarks</a>
        <a href="#" class="menu-item"><i class="fas fa-user"></i> Profile</a>
        <a href="#" class="menu-item"><i class="fas fa-cog"></i> Settings</a>
      </nav>

      <main class="col-md-9 col-lg-10 content">
        <div class="tweet-header text-center text-md-start">
          <h1>Twitter</h1>
        </div>

        <form action="index.php" method="post" class="post-form mb-4">
          <div class="d-flex align-items-start">
            <img src="Images/chan.jpg" alt="Profile Picture" class="profile-img me-3">
            <div class="flex-grow-1">
              <textarea name="content" rows="3" placeholder="What's happening?" required></textarea>
              <input type="hidden" name="userID" value="1">
              <div class="d-flex justify-content-between align-items-center mt-2">
                <div class="post-icons">
                  <i class="fas fa-image"></i>
                  <i class="fas fa-gift"></i>
                  <i class="fas fa-align-left"></i>
                  <i class="far fa-smile"></i>
                  <i class="fas fa-calendar"></i>
                  <i class="fas fa-map-marker-alt"></i>
                </div>
                <button type="submit" name="btnPost" class="post-btn">Tweet</button>
              </div>
            </div>
          </div>
        </form>

        <div>
          <?php if (mysqli_num_rows($results) > 0) {
    while ($post = mysqli_fetch_assoc($results)) { ?>
          <div class="card">
            <div class="d-flex align-items-start">
              <img src="Images/<?php echo empty($post['attachment']) ? 'chan.jpg' : $post['attachment']; ?>"
                alt="Profile Picture" class="profile-img me-3">
              <div>
                <h5 class="m-0 text-white">
                  <?php echo $post['firstName'] . " " . $post['lastName']; ?>
                </h5>
                <small class="timestamp">
                  <?php echo date("M j", strtotime($post['dateTime'])); ?>
                </small>
                <p class="post-content mt-1 mb-2">
                  <?php echo $post['content'] ?>
                </p>
                <div class="d-flex">
                  <button class="action-btn"><i class="far fa-comment"></i>
                    <?php echo rand(0, 20); ?>
                  </button>
                  <button class="action-btn"><i class="fas fa-retweet"></i>
                    <?php echo rand(5, 50); ?>
                  </button>
                  <button class="action-btn"><i class="far fa-heart"></i>
                    <?php echo rand(10, 100); ?>
                  </button>
                  <button class="action-btn"><i class="fas fa-share"></i>
                    <?php echo rand(100, 500); ?>
                  </button>
                </div>
                <form method="POST" action="index.php" class="mt-2">
                  <input type="hidden" name="postID" value="<?php echo $post['postID']; ?>">
                  <button type="button" class="rounded-btn" data-bs-toggle="modal"
                    data-bs-target="#editModal<?php echo $post['postID']; ?>">Edit</button>
                  <button type="submit" name="btnDelete" class="rounded-btn">Delete</button>
                </form>
              </div>
            </div>
          </div>

          <div class="modal fade" id="editModal<?php echo $post['postID']; ?>" tabindex="-1"
            aria-labelledby="editModalLabel<?php echo $post['postID']; ?>" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="editModalLabel<?php echo $post['postID']; ?>">Edit Post</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php" method="POST">
                  <div class="modal-body">
                    <textarea name="editedContent" rows="4" class="form-control"
                      required><?php echo $post['content']; ?></textarea>
                    <input type="hidden" name="postID" value="<?php echo $post['postID']; ?>">
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="btnEdit" class="edit-btn">Save Changes</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <?php } } ?>
        </div>
      </main>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>