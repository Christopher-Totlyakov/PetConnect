<?php
session_start();
$isLogged = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sign Up - 🐾 PetConnect</title>
    <link rel="icon" type="image/png" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSXNVYInU6n-54guKmWlNqLHhMtasX6o6wAgQ&s">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <?php include 'common/nav.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3"></div>

            <div class="col-lg-6">
                <div id="signup-form" style="border:1px solid grey; margin:10% 0; padding:10% 5%">
                    <h2>Sign up</h2>

                    <div class="form-group">
                        <input name="name" class="form-control mb-2" placeholder="Full name">
                        <input name="email" class="form-control mb-2" placeholder="Email">
                        <input name="password" type="password" class="form-control mb-2" placeholder="Password">
                        <input name="password_confirm" type="password" class="form-control mb-2" placeholder="Confirm password">
                    </div>

                    <div class="g-recaptcha mb-2" data-sitekey="6LcU1RwsAAAAAKSZllpA-NINo9q7TiEkiqu-k3Uj"></div>

                    <button class="btn btn-primary mt-2" id="signup-submit">Sign up</button>

                    <div class="mt-2">
                        Have an account?
                        <a href="login.php" style="color:orange">Sign in here</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/petAjax.js"></script>
    <script src="assets/js/common.js"></script>
</body>

</html>