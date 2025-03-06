<!-- tuqa section -->

<?php
/* include 'config.php';

$categories = $conn->query("SELECT * FROM categories"); */
?>

<?php
include './config.php'; 
include './controllers/CategoryController.php';
include './controllers/ProductController.php';
include './controllers/CartController.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watch Store</title>
    <style>
  body {
    background-color: #F2F2F2;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

.navbar {
    background-color: #0D0000;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: flex-start; 
}

.navbar a, .dropdown  {
    display: block;
    color: #F2F2F2;
    text-align: center;
    padding: 10px 15px; 
    text-decoration: none;
    margin-right: 10px; 
}
.navbar a:hover, .dropdown:hover .dropbtn .reset-filter-btn:hover, .filter-btn:hover {
    background-color: #403431;
}
.dropdown {
    display: inline-block;
}
.dropdown-content {
    display: none;
    position: absolute;
    background-color: rgba(13, 0, 0, 0.8); 
    min-width: 200px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}
.dropdown-content a {
    color: #F2F2F2;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    text-align: left;
}
.dropdown-content a:hover {
    background-color: #403431;
}
.dropdown:hover .dropdown-content {
    display: block;
}

/* Filter Box */
.filter-box {
    display: none; 
    width: 250px; 
    padding: 15px; 
    background-color: #FFFFFF;
    border: 1px solid #DDD;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    position: fixed; 
    top: 70px; 
    left: 10px; 
    box-sizing: border-box;
    z-index: 1000; 
}
.filter-box label {
    display: block;
    margin-bottom: 10px;
    font-size: 16px; 
}
.filter-box select, .filter-box input {
    width: calc(100% - 20px);
    padding: 8px; 
    margin-bottom: 15px; 
    border: 1px solid #CCC;
    border-radius: 4px;
    display: block;
}
.filter-box button {
    background-color: #403431;
    color: #FFFFFF;
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin: 5px 0; 
    width: 100%;
}
.filter-box button:hover {
    background-color: #6c757d;
}

.filter-btn {
    background: transparent;
    border: none;
    color: #F2F2F2;
    cursor: pointer;
    padding: 10px 15px;
}
.filter-btn i {
    font-size: 20px;
}
 
    </style>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<!-- Navigation Bar -->
<div class="navbar">
    <a href="#home">Home</a>
    <a href="#Cart">Cart</a>
    <a href="#contact">Contact</a>
    <a href="#about">About</a>
    <div class="dropdown">
        <a href="javascript:void(0)" class="dropbtn" onclick="toggleFilterBox()">Categories</a>
        <div class="dropdown-content">
            <a href="javascript:void(0)" onclick="showFilterBox('Men')">Men</a>
            <a href="javascript:void(0)" onclick="showFilterBox('Women')">Women</a>
            <a href="javascript:void(0)" onclick="showFilterBox('Kids')">Kids</a>
        </div>
    </div>
    <button type="button" class="reset-filter-btn" onclick="resetFilter()">Clear</button>
    <button type="button" class="filter-btn" onclick="toggleFilterBox()"><i class="fas fa-filter"></i></button>

<!-- Filter Box -->
<div id="filterBox" class="filter-box">
    <form id="filterForm">
        <label for="color">Color:</label>
        <select id="color" name="color">
            <option value="all">All</option>
            <option value="black">Black</option>
            <option value="Gold">Gold</option>
            <option value="silver">Silver</option>
        </select>

        <label for="brand">Brand:</label>
        <select id="brand" name="brand">
            <option value="all">All</option>
            <option value="brand1">Brand 1</option>
            <option value="brand2">Brand 2</option>
        </select>

        <label for="price">Price Range:</label>
        <input type="number" id="priceMin" name="priceMin" placeholder="Min Price">
        <input type="number" id="priceMax" name="priceMax" placeholder="Max Price">

        <label for="type">Watch Type:</label>
        <select id="type" name="type">
            <option value="all">All</option>
            <option value="stainless_steel">Stainless Steel</option>
            <option value="leather">Leather</option>
            <option value="digital">Digital</option>
        </select>
        <button type="button" onclick="applyFilter()">Apply Filter</button>
        <button type="button" onclick="resetFilter()">Reset Filter</button>
    </form>
</div>

<script>
function toggleFilterBox() {
    var filterBox = document.getElementById('filterBox');
    if (filterBox.style.display === 'none' || filterBox.style.display === '') {
        filterBox.style.display = 'block';
    } else {
        filterBox.style.display = 'none';
    }
}

function showFilterBox(category) {
    var filterBox = document.getElementById('filterBox');
    filterBox.style.display = 'block';
}

function applyFilter() {
    alert('Filter applied!');
    document.getElementById('filterBox').style.display = 'none'; 
}

function resetFilter() {
    document.getElementById('filterForm').reset();
    alert('Filter reset!');
    document.getElementById('filterBox').style.display = 'none'; 
}
</script>

</body>
</html>