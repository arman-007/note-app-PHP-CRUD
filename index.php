<?php
//connect to database
$servername = "localhost";
$username = "root";
$password = "";
$database = "notes";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
  echo "Database Connection isn't stablished due to -->" . mysqli_connect_error();
}

$success_note_insertion = false;
$update = false;
$delete = false;

if (isset($_GET['delete'])) {
  $sno = $_GET['delete'];
  $delete = true;
  $sql = "DELETE FROM `notes` WHERE `sno` = '$sno'";
  $result = mysqli_query($conn, $sql);
}
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  if (isset($_POST['snoEdit'])) {
    //Update the record
    $sno = $_POST['snoEdit'];
    $title = $_POST['titleEdit'];
    $description = $_POST['descriptionEdit'];

    $sql = "UPDATE `notes` SET `title` = '$title' , `description` = '$description' WHERE `notes`.`sno` ='$sno'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
      $update = true;
    }
  } else {
    $title = $_POST['title'];
    $description = $_POST['description'];

    $sql = "INSERT INTO `notes` (`title`, `description`) VALUES ('$title', '$description')";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
      echo "Insertion failed dur to -->" . mysqli_error($conn);
    } else {
      $success_note_insertion = true;
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>iNotes - Making Notes Easy</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous" />
  <link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
</head>

<body>
  <!-- Edit modal -->
  <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
    Edit Modal
  </button> -->

  <!--Edit Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="editModalLabel">Edit Note</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="/crud/index.php" method="post">
          <div class="modal-body">
            <input type="hidden" name="snoEdit" id="snoEdit">
            <div class="mb-3">
              <label for="title" class="form-label">Note Title</label>
              <input type="text" class="form-control" id="titleEdit" name="titleEdit" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Note Description</label>
              <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" rows="5"></textarea>
            </div>
          </div>
          <div class="modal-footer d-block mr-auto">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- <h1>Hello, world!</h1> -->
  <nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">iNotes</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll"
        aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarScroll">
        <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Contact</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Link
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Action</a></li>
              <li><a class="dropdown-item" href="#">Another action</a></li>
              <li>
                <hr class="dropdown-divider" />
              </li>
              <li>
                <a class="dropdown-item" href="#">Something else here</a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link disabled">Link</a>
          </li>
        </ul>
        <form class="d-flex" role="search">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" />
          <button class="btn btn-outline-success" type="submit">
            Search
          </button>
        </form>
      </div>
    </div>
  </nav>

  <!-- successful note insertion alert -->
  <?php
  if ($success_note_insertion) {
    echo "
      <div class='alert alert-success alert-dismissible fade show' role='alert'>
      <strong>Success!</strong> Your note has been added successfully.
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>
      ";
  }
  if ($delete) {
    echo "
      <div class='alert alert-warning alert-dismissible fade show' role='alert'>
      <strong>Success!</strong> Your note has been deleted successfully.
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>
      ";
  }
  if ($update) {
    echo "
      <div class='alert alert-success alert-dismissible fade show' role='alert'>
      <strong>Success!</strong> Your note has been updated successfully.
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>
      ";
  }
  ?>

  <!-- note form -->
  <div class="container my-4">
    <h2>Add a note</h2>
    <form action="/crud/index.php" method="post">
      <div class="mb-3">
        <label for="title" class="form-label">Note Title</label>
        <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Note Description</label>
        <textarea class="form-control" id="description" name="description" rows="5"></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Add Note</button>
    </form>
  </div>

  <!-- display saved notes -->
  <div class="container my-4">
    <table class="table" id="myTable">
      <thead>
        <tr>
          <th scope="col">S. No.</th>
          <th scope="col">Title</th>
          <th scope="col">Description</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // INSERT INTO `notes` (`sno`, `title`, `description`, `tstamp`) VALUES (NULL, 'Buy books', 'Buy 3 books.\r\nDelete once done.', current_timestamp());
        $sql = "SELECT * FROM `notes`";
        $result = mysqli_query($conn, $sql);

        $sno = 0;

        while ($row = mysqli_fetch_assoc($result)) {
          $sno = $sno + 1;
          echo "<tr>
                  <th scope='row'>" . $sno . "</th>
                  <td>" . $row['title'] . "</td>
                  <td>" . $row['description'] . "</td>
                  <td>
                    <button class='edit btn btn-sm btn-primary' id=" . $row['sno'] . ">Edit</button>
                    <button class='delete btn btn-sm btn-primary' id=d" . $row['sno'] . ">Delete</button>
                  </td>
                </tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
  <hr>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
    crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"
    integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
  <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script>
    let table = new DataTable('#myTable');
  </script>
  <script>
    edits = document.getElementsByClassName('edit');
    Array.from(edits).forEach((element) => {
      element.addEventListener("click", (e) => {
        console.log("edit ",);
        tr = e.target.parentNode.parentNode;
        title = tr.getElementsByTagName("td")[0].innerText;
        description = tr.getElementsByTagName("td")[1].innerText;
        console.log(title, description);
        titleEdit.value = title;
        descriptionEdit.value = description;
        snoEdit.value = e.target.id;
        console.log(e.target.id);
        $('#editModal').modal('toggle');
      })
    })

    deletes = document.getElementsByClassName('delete');
    Array.from(deletes).forEach((element) => {
      element.addEventListener("click", (e) => {
        console.log("edit ",);
        sno = e.target.id.substr(1,);

        if (confirm("Are you sure to delete this note?")) {
          console.log("Yes");
          window.location = `/crud/index.php?delete=${sno}`;
          //Further Update: create a form and use post request to submit a form
        }
        else {
          console.log("No");
        }
      })
    })
  </script>
</body>

</html>