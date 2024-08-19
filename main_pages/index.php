<?php
session_start();
if (!isset($_SESSION['name'])) {
  header('location:../index.php');
  exit();
}
$name = $_SESSION['name'];

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "abc_database";

$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Update switch states
  if (isset($_POST['switchState1'])) {
    $switchState1 = $_POST['switchState1'] === '1' ? 1 : 0;
    $sql = "UPDATE control SET pompa_air = $switchState1 WHERE id = 1";
    if ($conn->query($sql) === TRUE) {
      echo "SwitchState1 updated successfully";
    } else {
      echo "Error updating SwitchState1: " . $conn->error;
    }
  }

  if (isset($_POST['switchState2'])) {
    $switchState2 = $_POST['switchState2'] === '1' ? 1 : 0;
    $sql = "UPDATE control SET gerbang_air = $switchState2 WHERE id = 1";
    if ($conn->query($sql) === TRUE) {
      echo "SwitchState2 updated successfully";
    } else {
      echo "Error updating SwitchState2: " . $conn->error;
    }
  }

  if (isset($_POST['switchState3'])) {
    $switchState3 = $_POST['switchState3'] === '1' ? 1 : 0;
    $sql = "UPDATE control SET auto = $switchState3 WHERE id = 1";
    if ($conn->query($sql) === TRUE) {
      echo "SwitchState3 updated successfully";
    } else {
      echo "Error updating SwitchState3: " . $conn->error;
    }
  }
}

// Jumlah data per halaman
$results_per_page = 100;

// Menghitung total baris
$sql_total = "SELECT COUNT(*) AS total FROM tabel_data";
$result_total = $conn->query($sql_total);
$row_total = $result_total->fetch_assoc();
$total_rows = $row_total['total'];

// Menghitung total halaman
$total_pages = ceil($total_rows / $results_per_page);

// Mendapatkan halaman saat ini dari URL
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
  $current_page = (int)$_GET['page'];
} else {
  $current_page = 1;
}

// Pastikan halaman saat ini berada dalam rentang yang valid
if ($current_page < 1) {
  $current_page = 1;
} elseif ($current_page > $total_pages) {
  $current_page = $total_pages;
}

// Menghitung offset
$offset = ($current_page - 1) * $results_per_page;

// Query untuk mengambil data
$sql1 = "SELECT * FROM tabel_data";
$sql2 = "SELECT kelembapan_tanah FROM tabel_data ORDER BY no DESC LIMIT 1";
$sql3 = "SELECT kedalaman_air FROM tabel_data ORDER BY no DESC LIMIT 1";
$sql4 = "SELECT pompa_air FROM tabel_data ORDER BY no DESC LIMIT 1";
$sql5 = "SELECT gerbang_air FROM tabel_data ORDER BY no DESC LIMIT 1";
// Mengambil data untuk halaman saat ini
$sql6 = "SELECT * FROM tabel_data LIMIT $offset, $results_per_page";

$result_data1 = $conn->query($sql1);
$result_data2 = $conn->query($sql2);
$result_data3 = $conn->query($sql3);
$result_data4 = $conn->query($sql4);
$result_data5 = $conn->query($sql5);
$result_data6 = $conn->query($sql6);

// Periksa hasil query
if (!$result_data1 || !$result_data2 || !$result_data3 || !$result_data4) {
  die("Query error: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Main Station- Dashboard</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fa fa-desktop"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Main Station</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="index.html">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Search
          <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
              <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
              <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form> -->

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                  <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li>


            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $name; ?></span>
                <img class="img-profile rounded-circle" src="https://picsum.photos/id/64/60/60">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                <!-- <a class="dropdown-item" href="#">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Settings
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                  Activity Log
                </a> -->
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>
          <!-- Content Row -->
          <div class="row">

            <!-- Kelembapan Tanah -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Kelembapan Tanah (RH)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php
                        $kelembapan_tanah = 0;
                        if ($result_data2->num_rows > 0) {
                          $row_data = $result_data2->fetch_assoc();
                          $kelembapan_tanah = $row_data['kelembapan_tanah'];
                          echo $kelembapan_tanah;
                        }
                        ?>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-thermometer-empty fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Kedalaman Air -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Kedalaman Air (CM)</div>
                      <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                          <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                            <?php
                            $kedalaman_air = 0;
                            if ($result_data3->num_rows > 0) {
                              $row_data = $result_data3->fetch_assoc();
                              $kedalaman_air = $row_data['kedalaman_air'];
                              echo $kedalaman_air;
                            }
                            ?>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-tachometer-alt fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Toggle Switch 1 -->
            <div class="col-xl-2 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pompa Air</div>
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="customSwitch1" onchange="updateSwitchState1(this)">
                        <label class="custom-control-label" for="customSwitch1" id="customSwitchLabel1">On/Off</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Toggle Switch 2 -->
            <div class="col-xl-2 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Gerbang Air</div>
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="customSwitch2" onchange="updateSwitchState2(this)">
                        <label class="custom-control-label" for="customSwitch2" id="customSwitchLabel2">On/Off</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Toggle Switch 3 -->
            <div class="col-xl-2 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Otomatis</div>
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="customSwitch3" onchange="updateSwitchState3(this)">
                        <label class="custom-control-label" for="customSwitch3" id="customSwitchLabel3">On/Off</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
          <!-- /.row -->

          <!-- Content Row -->
          <div class="row justify-content-center">

            <!-- Area Chart -->
            <div class="col-xl-6 col-lg-6">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Kelembapan Tanah</h6>
                  <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                      <div class="dropdown-header">Dropdown Header:</div>
                      <a class="dropdown-item" href="#">Action</a>
                      <a class="dropdown-item" href="#">Another action</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                  </div>
                </div>
                <!-- Card Body -->
                <div class="card-body text-center">
                  <div class="chart-area mx-auto">
                    <!-- Embedding ThingSpeak chart -->
                    <iframe width="450" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/2197249/charts/1?bgcolor=%23ffffff&color=%23d62020&dynamic=true&results=60&title=KELEMBAPAN+TANAH&type=line"></iframe>
                    <!-- <br>source: <a href="https://tradingeconomics.com/indonesia/temperature">tradingeconomics.com</a> -->
                  </div>
                </div>
              </div>
            </div>

          </div>
          <!-- /.row -->


          <!-- Begin Page Content -->
          <!-- <div class="container-fluid">
             Page Heading -->
          <!-- <h1 class="h3 mb-4 text-gray-800">Dashboard Monitoring</h1> -->


          <!-- Data Table Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Data Tabel</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Kelembapan Tanah (%RH)</th>
                      <th>Kedalaman Air (CM)</th>
                      <th>Pompa Air</th>
                      <th>Gerbang Air</th>
                      <th>Waktu</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if ($result_data6 && $result_data6->num_rows > 0) {
                      while ($row_data = $result_data6->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row_data['no'] . "</td>";
                        echo "<td>" . $row_data['kelembapan_tanah'] . "</td>";
                        echo "<td>" . $row_data['kedalaman_air'] . "</td>";
                        echo "<td>" . ($row_data['pompa_air'] ? 'ON' : 'OFF') . "</td>";
                        echo "<td>" . ($row_data['gerbang_air'] ? 'Open' : 'Closed') . "</td>";
                        echo "<td>" . $row_data['waktu'] . "</td>";
                        echo "</tr>";
                      }
                    } else {
                      echo "<tr><td colspan='6'>No data found</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>

                <!-- Pagination Controls -->
                <nav aria-label="Page navigation">
                  <ul class="pagination justify-content-center">
                    <li class="page-item <?php if ($current_page <= 1) echo 'disabled'; ?>">
                      <a class="page-link" href="?page=<?php echo $current_page - 1; ?>" tabindex="-1">Previous</a>
                    </li>
                    <?php
                    for ($page = 1; $page <= $total_pages; $page++) {
                      echo '<li class="page-item ' . ($page == $current_page ? 'active' : '') . '">';
                      echo '<a class="page-link" href="?page=' . $page . '">' . $page . '</a>';
                      echo '</li>';
                    }
                    ?>
                    <li class="page-item <?php if ($current_page >= $total_pages) echo 'disabled'; ?>">
                      <a class="page-link" href="?page=<?php echo $current_page + 1; ?>">Next</a>
                    </li>
                  </ul>
                </nav>

              </div>
            </div>
          </div>
          <!-- End of Main Content -->

          <!-- Footer -->
          <!-- <footer class="sticky-footer bg-white">
    <div class="container my-auto">
      <div class="copyright text-center my-auto">
        <span>Copyright &copy; Your Website 2020</span>
      </div>
    </div>
  </footer> -->
          <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

      </div>
      <!-- End of Page Wrapper -->

      <!-- Scroll to Top Button-->
      <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
      </a>

      <!-- Logout Modal-->
      <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
              <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
              <a class="btn btn-primary" href="logout.php">Logout</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Bootstrap core JavaScript-->
      <script src="vendor/jquery/jquery.min.js"></script>
      <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

      <!-- Core plugin JavaScript-->
      <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

      <!-- Custom scripts for all pages-->
      <script src="js/sb-admin-2.min.js"></script>

      <!-- Page level plugins -->
      <script src="vendor/chart.js/Chart.min.js"></script>

      <!-- Page level custom scripts -->
      <script src="js/demo/chart-area-demo.js"></script>
      <script src="js/demo/chart-pie-demo.js"></script>

      <!-- <script>
        function updateSwitchLabel(labelId, element) {
          const label = document.getElementById(labelId);
          label.textContent = element.checked ? 'ON' : 'OFF';
        }
      </script> -->

      <script>
        function updateSwitchState1(checkbox) {
          const isChecked = checkbox.checked;
          const label = document.getElementById('customSwitchLabel1');
          label.textContent = isChecked ? 'ON' : 'OFF';

          const xhr = new XMLHttpRequest();
          xhr.open("POST", "index.php", true);
          xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
              console.log(xhr.responseText);
            }
          };
          xhr.send("switchState1=" + (isChecked ? '1' : '0')); // Mengirim nilai '1' atau '0'
        }

        function updateSwitchState2(checkbox) {
          const isChecked = checkbox.checked;
          const label = document.getElementById('customSwitchLabel2');
          label.textContent = isChecked ? 'ON' : 'OFF';

          const xhr = new XMLHttpRequest();
          xhr.open("POST", "index.php", true);
          xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
              console.log(xhr.responseText);
            }
          };
          xhr.send("switchState2=" + (isChecked ? '1' : '0')); // Mengirim nilai '1' atau '0'
        }

        function updateSwitchState3(checkbox) {
          const isChecked = checkbox.checked;
          const label = document.getElementById('customSwitchLabel3');
          label.textContent = isChecked ? 'ON' : 'OFF';

          document.getElementById('customSwitch1').disabled = isChecked;
          document.getElementById('customSwitch2').disabled = isChecked;

          const xhr = new XMLHttpRequest();
          xhr.open("POST", "index.php", true);
          xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
              console.log(xhr.responseText);
            }
          };
          xhr.send("switchState3=" + (isChecked ? '1' : '0')); // Mengirim nilai '1' atau '0'
        }
      </script>

</body>

</html>