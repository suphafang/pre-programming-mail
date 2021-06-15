<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #f5aa42">
  <div class="container">

    <a class="navbar-brand" href="/">
      <img src="logo.png" style="max-height: 35px;" class="my-3">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <?php
          if(isset($_SESSION['authorization'])) {
        ?>
        <li class="nav-item px-2">
          <a class="nav-link" href="/">Home</a>
        </li>
        <li class="nav-item px-2">
          <a class="nav-link" href="/logout.php">Logout</a>
        </li>
        <?php
          } else {
        ?>
        <li class="nav-item px-2">
          <a class="nav-link" href="/">Login</a>
        </li>
        <?php
          }
        ?>
      </ul>
    </div>

  </div>
</nav>