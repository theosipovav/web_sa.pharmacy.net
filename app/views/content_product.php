<div class="content app-product">
  <div class="container">
    <nav aria-label="breadcrumb" style="background: transparent;">
      <ol class="breadcrumb" style="background: transparent;">
        <li class="breadcrumb-item"><a href=".">sa.pharmacy.net</a></li>
        <li class="breadcrumb-item"><a href="#"><?php echo $sProductSource; ?></a></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $dataProductScan; ?></li>
      </ol>
    </nav>
    <hr>
    <div class="row">
      <div class="col">
        <h3 class="h3">
          Наименование товара
        </h3>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <h5 class="display-5"><?php echo $sProductName; ?></h5>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col-md-6">
        <div class="row">
          <div class="col">
            <h3 class="h3">
              Дополнительная информация
            </h3>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <?php echo $sProductInfo; ?>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="row">
          <div class="col text-right">
            <h3 class="h3">
              Магазин
            </h3>
          </div>
        </div>
        <div class="row">
          <div class="col text-right">
            <h1 class="h1 text-success">
              <?php echo $sProductSource; ?>
            </h1>
          </div>
        </div>
        <div class="row">
          <div class="col text-right">
            <a href="<?php echo $sProductScanUrl; ?>" target="_blank" class="btn btn-outline-primary btn-lg">Перейти</a>
          </div>
        </div>
      </div>
    </div>
    <hr>
    <div class="row ">
      <div class="col">
        <h3 class="h3">
          Последняя цена
        </h3>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <?php echo $dataProductScan; ?>
      </div>
      <div class="col-md-6 text-right">
        <h3 class="h3 text-danger"><?php echo $nProductPrice; ?> руб.</h3>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col-md-6">
        <h3 class="h3">
          Динамика изменения цены:
        </h3>
      </div>
      <div class="col-md-6 text-right">
        <button class="btn btn-info btn-lg" onclick="fnLoadDataChartsPrice(<?php echo $nObjectId; ?>)">Показать</button>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <div class="demo-container">
          <div id="placeholder" class="demo-placeholder"></div>
        </div>
      </div>
    </div>
  </div>
</div>