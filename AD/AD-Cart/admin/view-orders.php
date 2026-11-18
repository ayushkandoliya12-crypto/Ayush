<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Categories & Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light text-dark">

<?php 
include 'header.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 rounded-4 p-4">
                <h2 class="text-center mb-4 fw-bold text-primary">View Products & Categories</h2>

                <?php
                $cn = mysqli_connect("localhost", "root", "", "project") or die("Server not Found" . mysqli_error($cn));

                $result = mysqli_query($cn, "SELECT * FROM product JOIN category ON category.c_id = product.c_id") or die("Select Error" . mysqli_error($cn)); 

                if (mysqli_num_rows($result) > 0) {
                    echo '<div class="table-responsive">';
                    echo '<table class="table table-striped table-hover align-middle border">';
                    echo '<thead class="table-primary">';
                    echo '<tr>';
                    echo '<th scope="col">ID</th>';
                    echo '<th scope="col">Products</th>';
                    echo '<th scope="col">Category Name</th>';
                    echo '<th scope="col">Image</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';

                    while($record = mysqli_fetch_array($result)) {
                        echo "<tr>";
                        echo "<td>" . $record['p_id'] . "</td>";
                        echo "<td>" . $record['p_name'] . "</td>";
                        echo "<td>" . $record['c_name'] . "</td>";
                        echo "<td><img src=' img/".$record['p_photo']."'height=120 width=140></td>";
                        echo "</tr>";
                    }

                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                } else {
                    echo '<div class="alert alert-warning text-center" role="alert">No records found!</div>';
                }
                ?>

            </div>
        </div>
    </div>
</div>

<?php 
include 'footer.php';
?>

<!-- Bootstrap 5 JS Bundle CDN (Optional for interactive components) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>