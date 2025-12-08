    <nav class="navbar navbar-expand-lg navbar-custom shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">🐾 PetConnect</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">

                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="pet_list.php">All Pets</a></li>

                    <?php if ($isLogged): ?>
                        <li class="nav-item"><a class="nav-link" href="pet_create.php">Add Pet</a></li>
                    <?php endif; ?>

                    <li class="nav-item"><a class="nav-link" href="pet_post.php">Posts</a></li>

                    <?php if ($isLogged): ?>
                        <li class="nav-item"><a class="nav-link" href="user_profile.php">My Profile</a></li>
                        <li class="nav-item"><a class="nav-link logout-btn" id="logoutBtn">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="signup.php">Sign Up</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>