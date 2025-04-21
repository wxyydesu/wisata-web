<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Bootstrap Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <style>
    body {
      overflow-x: hidden;
    }
    .sidebar {
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      width: 250px;
      background-color: #343a40;
      padding-top: 60px;
    }
    .sidebar a {
      color: #ddd;
      padding: 10px 20px;
      display: block;
      text-decoration: none;
    }
    .sidebar a:hover {
      background-color: #495057;
    }
    .content {
      margin-left: 250px;
      padding: 20px;
    }
    @media (max-width: 768px) {
      .sidebar {
        left: -250px;
      }
      .sidebar.active {
        left: 0;
      }
      .content {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
      <button class="btn btn-outline-light d-md-none" id="toggleSidebar"><i class="bi bi-list"></i></button>
      <a class="navbar-brand ms-2" href="#">Dashboard</a>
      <div class="dropdown">
        <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
          <i class="bi bi-person-circle"></i> Admin
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="#">Profile</a></li>
          <li><a class="dropdown-item" href="#">Settings</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="#">Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Sidebar -->
  <div class="sidebar bg-dark text-white" id="sidebar">
    <a href="#"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="#"><i class="bi bi-people me-2"></i> Users</a>
    <a href="#"><i class="bi bi-bar-chart me-2"></i> Reports</a>
    <a href="#"><i class="bi bi-gear me-2"></i> Settings</a>
  </div>

  <!-- Content -->
  <div class="content">
    <div class="pt-4 mt-4">
      <h2>Welcome Back, Admin!</h2>
      <div class="row mt-4">
        <div class="col-md-3">
          <div class="card text-bg-primary mb-3">
            <div class="card-body">
              <h5 class="card-title">Users</h5>
              <p class="card-text fs-4">1,240</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-bg-success mb-3">
            <div class="card-body">
              <h5 class="card-title">Sales</h5>
              <p class="card-text fs-4">$35,000</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-bg-warning mb-3">
            <div class="card-body">
              <h5 class="card-title">Visitors</h5>
              <p class="card-text fs-4">4,520</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-bg-danger mb-3">
            <div class="card-body">
              <h5 class="card-title">Orders</h5>
              <p class="card-text fs-4">980</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Activity or Chart Section -->
      <div class="card mt-4">
        <div class="card-header">
          Recent Activity
        </div>
        <div class="card-body">
          <p class="card-text">This is where you can place recent activity logs, tables, or charts.</p>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleSidebar');
    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('active');
    });
  </script>
</body>
</html>
