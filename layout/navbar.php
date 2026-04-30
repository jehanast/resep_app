<nav class="navbar navbar-expand-lg navbar-dark nav-glass sticky-top">
  <div class="container">

    <!-- LOGO -->
    <a class="navbar-brand d-flex align-items-center" href="/resep-app/index.php">
      <img src="/resep-app/assets/logo.png" height="40" class="me-2">
      <strong>Seperdua Recipe</strong>
    </a>

    <!-- TOGGLE MOBILE -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- MENU -->
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-lg-center">

        <li class="nav-item">
          <a class="nav-link" href="/resep-app/index.php">Home</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="#fitur">Fitur</a>
        </li>

        <?php if(isset($_SESSION['user'])) { ?>
          <li class="nav-item">
            <?php if($_SESSION['user']['role']=='admin'){ ?>
              <a href="/resep-app/admin/dashboard.php" class="btn btn-main ms-lg-3">Dashboard</a>
            <?php } else { ?>
              <a href="/resep-app/karyawan/dashboard.php" class="btn btn-main ms-lg-3">Dashboard</a>
            <?php } ?>
          </li>

          <li class="nav-item ms-lg-2">
            <a href="/resep-app/auth/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
          </li>
        <?php } else { ?>
          <li class="nav-item ms-lg-3">
            <a href="/resep-app/auth/login.php" class="btn btn-main">Login</a>
          </li>
        <?php } ?>

      </ul>
    </div>

  </div>
</nav>