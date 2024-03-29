<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="/vsite/cms/pages/delivery_order/index.php"><img src="/vsite/cms/assets/pic/logo ptsn.jpg" width="30" alt="logo ptsn" class="rounded-circle me-2">Central
      Management System</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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
            <li><button class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#createModal">Add
                DO</button>
            </li>
            <li><button class="dropdown-item" onclick="window.location.href='/vsite/cms/pages/delivery_order/do_sum.php'">DO Summary</button></li>
            <li><button class="dropdown-item" onclick="window.location.href='/vsite/cms/pages/delivery_order/isl.php'">ISL Search</button></li>
            <li><button class="dropdown-item" onclick="window.location.href='/vsite/cms/pages/delivery_order/performance.php'" <?php echo ($utype == 3) ? 'disabled' : ''; ?>>Performance</button></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Inventory
          </a>
          <ul class="dropdown-menu">
            <li><button class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addPeriodModal">Add New
                Period</button>
            </li>
            <li><button class="dropdown-item" onclick="window.location.href='/vsite/cms/pages/inventory/period_list.php'">Period List</button>
            </li>
            <li><button class="dropdown-item" onclick="window.location.href='/vsite/cms/pages/inventory/reporting.php'">Summary</button>
            </li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Material Control
          </a>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <li><a class="dropdown-item" href="/vsite/cms/pages/material/ms_material_doc.php">Main Store Issue</a></li>
            <li><a class="dropdown-item" href="/vsite/cms/pages/material/ps_material_doc.php">Production Store Receive</a></li>
            <!-- <li>
              <a class="dropdown-item" href="#">
                Transaction &raquo;
              </a>
              <ul class="dropdown-menu dropdown-submenu">
                <li>
                  <a class="dropdown-item" href="#">Main Store</a>
                </li>
                <li>
                  <a class="dropdown-item" href="#">Sub Store</a>
                </li>
                <li>
                  <a class="dropdown-item" href="#">Production Store</a>
                </li>
              </ul>
            </li> -->
            <li>
              <a class="dropdown-item" href="/vsite/cms/pages/material/mt_report.php">Transaction Report</a>
            </li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Equipment
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/vsite/cms/pages/equipment/equ_list.php">Master List</a></li>
            <li><a class="dropdown-item" href="/vsite/cms/pages/equipment/equ_report.php">Report</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            User Management
          </a>
          <ul class="dropdown-menu">
            <li><button class="dropdown-item" onclick="window.location.href='/vsite/cms/register.php'" <?php echo ($utype == 3) ? 'disabled' : ''; ?>>Add User</button>
            </li>
            <li><button class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#changePassModal">Change
                Password</button></li>
          </ul>
        </li>
        <!-- <li class="nav-item dropdown">
          <a class="nav-link" href="/vsite/cms/pages/label/rule.php">
            Label Check
          </a>
        </li> -->
      </ul>
      <span class="text-light">Welcome,
        <?php echo $uname; ?>
      </span>
      <button style="margin-left: 10px" class="btn btn-danger" onclick="location.href='/vsite/cms/users/logout.php'">Exit</button>
    </div>
  </div>
</nav>