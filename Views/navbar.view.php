<?php
@session_start();
$products = \Datainterface\Selection::selectById('cart',["ownerid"=>2356]);
$totalItemUser = count($products);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="titlepage">collage project</title>
    <link rel="stylesheet" href="Css/home.css">
    <script src="https://kit.fontawesome.com/d03a15e86b.js" crossorigin="anonymous"></script>
</head>

<body>
<section id="header">
    <a href="#"> <img src="images/RBU Logo - Edited.png" alt=""></a>
    <h1><span>R</span>BU<span> E-COMMERCE</span></h1>
    <ul id="navbar">
        <li><a class="active" href="homepage">Home</a></li>
        <li><a href="categories">category</a></li>
        <li><a href="contact.html">Contact </a></li>
        <li id="lg-bag"><a href="cart"><i class="fa-solid fa-bag-shopping"></i><?php echo $totalItemUser; ?></a></li>
        <a href="#" id="close"><i class="fa-regular fa-circle-xmark"></i></a>
    </ul>
    </div>
    <div id="mobile" style="list-style:none;">
        <li><a href="cart.html"><i class="fa-solid fa-bag-shopping"></i></a></li>
        <i id="bar" class="fas fa-outdent"></i>
    </div>
</section>
<body>
