<?php
  $userid = $_SESSION['user_id'];
  $username = $_SESSION['name'];

  require_once "config/database.php";
  $pdo = new Database();
  $con = $pdo->getConnection();
  $stmt = $con->prepare("SELECT * FROM users WHERE id = :userid");
  $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user) {
    $username = $user['name'];
  }

?>

<nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
  <div class="container-fluid">
    <nav
      class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex"
    >
    </nav>

    <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
      <li class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
        <a
          class="nav-link dropdown-toggle"
          data-bs-toggle="dropdown"
          href="#"
          role="button"
          aria-expanded="false"
          aria-haspopup="true"
        >
          <i class="fa fa-search"></i>
        </a>
        <ul class="dropdown-menu dropdown-search animated fadeIn">
          <form class="navbar-left navbar-form nav-search">
            <div class="input-group">
              <input
                type="text"
                placeholder="Search ..."
                class="form-control"
              />
            </div>
          </form>
        </ul>
      </li>

      <li class="nav-item topbar-user dropdown hidden-caret">
        <a
          class="dropdown-toggle profile-pic"
          data-bs-toggle="dropdown"
          href="#"
          aria-expanded="false"
        >
          <div class="avatar-sm">
            <img
              src="<?= $user['image'] ?? 'assets/img/admin.png' ?>"
              alt="..."
              class="avatar-img rounded-circle"
            />
          </div>
          <span class="profile-username">
            <span class="op-7">Hi,</span>
            <span class="fw-bold"><?= $username ?></span>
          </span>
        </a>
        <ul class="dropdown-menu dropdown-user animated fadeIn">
          <div class="dropdown-user-scroll scrollbar-outer">
            <li>
              <a class="dropdown-item" href="index.php?controller=admin&action=settings&id=<?= $userid ?>">Account Setting</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item text-danger" href="/watch_store/dashboard/views/layouts/components/logout.php">Logout</a>
            </li>
          </div>
        </ul>
      </li>
    </ul>
  </div>
</nav>