<?php

if (!isset($link_main)) $link_main = "";
if (!isset($link_products)) $link_products = "";
if (!isset($link_list_res)) $link_list_res = "";
if (!isset($link_about)) $link_about = "";
?>
<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-transparent">
  <div class="container">
    <a class="navbar-brand" href=".">
      <img src="img/logo.png" height="30" alt="CCA">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item <?php if ($link_main != "") print("active"); ?>">
          <a class="nav-link" href=".">Главная <?php print($link_main); ?></span></a>
        </li>
        <li class="nav-item <?php if ($link_products != "") print("active"); ?>">
          <a class="nav-link" href="products.php">Продукция <?php print($link_products); ?></a>
        </li>
        <li class="nav-item <?php if ($link_list_res != "") print("active"); ?>">
          <a class="nav-link" href="listres.php">Список анализируемых ресурсов <?php print($link_list_res); ?></a>
        </li>
        <li class="nav-item <?php if ($link_about != "") print("active"); ?>">
          <a class="nav-link" href="about.php">О системе <?php print($link_about); ?></a>
        </li>
      </ul>
      <?php 
      $isAuth = FALSE;
      if ($isAuth) include_once("app/views/header_logout.php");
      else include_once("app/views/header_login.php");
      ?>
    </div>
  </div>
</nav>