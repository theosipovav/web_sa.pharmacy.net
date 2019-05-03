<div class="content app-product">
  <div class="container">
    <div class="row">
      <div class="col">
        <h5 class="display-5"><?php echo $sProductName; ?></h5>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col">
        <h3 class="h3">
          Дополнительная информация:
        </h3>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <?php echo $sProductInfo; ?>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col">
        <h3 class="h3">
          Магазин:
        </h3>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8">
        <h1 class="h1 text-success">
          <?php echo $sProductSource; ?>
        </h1>
      </div>
      <div class="col-md-4 text-right">
        <a href="#" class="btn btn-outline-primary">Перейти</a>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col-md-8">
        <h3 class="h3">
          Последняя цена
        </h3>
      </div>
      <div class="col-md-4 text-right">
        <h3 class="h3 text-danger">
          <?php echo $nProductPrice; ?>
        </h3>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col">
        <h3 class="h3">
          Динамика изменения цены:
        </h3>
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