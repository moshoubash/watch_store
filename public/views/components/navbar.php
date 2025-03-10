
<nav class="navbar">
  <ul class="nav-list">
    <li class="logo">
      <a href="/watch_store/public">WATCH</a>
      <button class="hamburger">
        <i class="fas fa-bars"></i>
      </button>
    </li>

    <div class="left-items">
      <li class="dropdown">
        <a href="/watch_store/public/views/category.php?categories=all" class="dropdown-toggle">Categories</a>
        <ul class="dropdown-menu">
          <li><a href="/watch_store/public/views/category.php?categories=men">Men's Watches</a></li>
          <li><a href="/watch_store/public/views/category.php?categories=women">Women's Watches</a></li>
          <li><a href="/watch_store/public/views/category.php?categories=kids">Kid's Watches</a></li>
          <li><a href="/watch_store/public/views/category.php?categories=sport">Sport Watches</a></li>
          <li><a href="/watch_store/public/views/category.php?categories=digital">Digital Watches</a></li>
        </ul>
      </li>
      <li>
        <a href="/watch_store/public/views/about_us.php"
          >About Us</a
        >
      </li>
      <li>
        <a href="/watch_store/public/views/contact_us.php"
          >Contact Us</a
        >
      </li>
      
    </div>

    <div class="right-items">
      <form action="/watch_store/public/views/search.php" method="POST" class="search-form">
        <input type="text" name="keyword" placeholder="Search" />
        <button type="submit" name="submit-search">
          <i class="fas fa-search"></i>
        </button>
      </form>
      <li class="nav-icons">
       
        <li class="dropdown">
          <i class="fas fa-user" style="margin-right: 30px;"></i></a>
          <ul class="dropdown-menu">
            <li><a href="/watch_store/public/views/profile_page/profile.php">Profile</a></li>
            <li><a href="/watch_store/public/views/profile_page/pro_edit.php">Setting</a></li>
            <li><a href="/watch_store/public/views/logout.php" style="color:red;">Logout</a></li>
          </ul>
        </li>
         
        <a href="/watch_store/public/views/cart_page.php">
          <i class="fa-solid fa-cart-shopping"></i>
        </a>
      </li>
    </div>
  </ul>
</nav>
