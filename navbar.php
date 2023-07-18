<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="do_list.php"><img src="assets/pic/logo ptsn.jpg" width="30" alt="logo ptsn" class="rounded-circle me-2">Central Management System</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <!-- <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li> -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Delivery Order
          </a>
          <ul class="dropdown-menu">
            <li><button class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#createModal">Add DO</button>
            </li>
            <li><button class="dropdown-item" onclick="window.location.href='do_sum.php'">DO Summary</button></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            User Management
          </a>
          <ul class="dropdown-menu">
            <li><button class="dropdown-item" onclick="window.location.href='register.php'" <?php echo ($utype == 3) ? 'disabled' : ''; ?>>Add User</button>
            </li>
            <li><button class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#changePassModal">Change Password</button></li>
          </ul>
        </li>
      </ul>
      <span class="text-light">Welcome,
        <?php echo $uname; ?>
      </span>
      <button style="margin-left: 10px" class="btn btn-danger" onclick="location.href='users/logout.php'">Exit</button>
    </div>
  </div>
</nav>