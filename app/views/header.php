<?php
if (isset($_SESSION))
{
  if (isset($_SESSION["id"]) && isset($_SESSION["login"]) && isset($_SESSION["name"]))
  {
    $isAuth = TRUE;
  }
  else
  {
    $isAuth = FALSE;
  }
}
else
{
  $isAuth = FALSE;
}

if ($isAuth) 
{
  ob_start();
  $username = $_SESSION["name"];
  include "app/views/header_logout.php";
  $widgetAuth = ob_get_contents();
  ob_end_clean();
}
else 
{
  ob_start();
  include "app/views/header_login.php";
  $widgetAuth = ob_get_contents();
  ob_end_clean();
}

$link_main = "";
$link_products = "";
$link_list_res = "";
$link_about = "";
$sScriptFileName = $_SERVER["SCRIPT_FILENAME"];
$sPathInfo = pathinfo($sScriptFileName);    
switch ($sPathInfo['basename']) 
{
  case 'index.php':
    $link_main = '<span class="sr-only">(current)</span>';
    break;
  case 'products.php':
    $link_products = '<span class="sr-only">(current)</span>';
    break;
  case 'listres.php':
    $link_list_res = '<span class="sr-only">(current)</span>';
    break;
  case 'about.php':
    $link_about = '<span class="sr-only">(current)</span>';
    break;
  default:
    break;
}
?>
<nav class="navbar fixed-top navbar-expand-lg navbar-dark">
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
          <a class="nav-link" href=".?r=products">Продукция <?php print($link_products); ?></a>
        </li>
        <li class="nav-item <?php if ($link_list_res != "") print("active"); ?>">
          <a class="nav-link" href=".?r=source">Список анализируемых ресурсов <?php print($link_list_res); ?></a>
        </li>
        <li class="nav-item <?php if ($link_about != "") print("active"); ?>">
          <a class="nav-link" href=".?r=about">О системе <?php print($link_about); ?></a>
        </li>
      </ul>
      <?php print($widgetAuth); ?>
    </div>
  </div>
</nav>


<div class="modal fade" id="ModalUserInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content usercard">
      <div class="usercard-header">
        <div class="avatar">
        </div>
      </div>
      <div class="usercard-body">
        <div class="row">
          <div class="col">
            <h3>
              <span id="SpanUserName" class="display-5"></span>
            </h3>
          </div>
        </div>
        <hr/>
        <div class="row">
          <div class="col">
            <div class="usercard-label" >Логин пользователя</div>
            <div class="usercard-value" ><span id="SpanLogin"></span></div>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <div class="usercard-label" >Электронная почта</div>
            <div class="usercard-value" ><span id="SpanEmail"></span></div>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <div class="usercard-label" >Дата регистрации</div>
            <div class="usercard-value" ><span id="SpanDateRegistration"></span></div>
          </div>
        </div>
        <hr/>
      </div>
      <div class="usercard-footer text-right">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
      </div>
    </div>

  </div>
</div>
