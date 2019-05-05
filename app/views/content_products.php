<script defer type="text/javascript" charset="utf8" src="js/app-products.js"></script>
<div class="content app-products">
  <div class="container">
    <hr />
    <div class="row">
      <div class="col-md-4">
        Сканируемый ресурс:
        <select id="SelectResourceName" class="custom-select" onchange="fnVeiwScanDate()">
          <option selected>Выберите сканируемый ресурс</option>
          <?php print($htmlOptionSelectSelectResourceName); ?>
        </select>
      </div>
      <div class="col-md-4">
        Дата сканирования:
        <select id="SelectScanDate" class="custom-select">

        </select>
      </div>
      <div class="col-md-4 text-right align-self-end">
        <button id="ButtonViewData" class="btn btn-success">Показать</button>
      </div>
    </div>
    <hr />
    <div class="row">
      <div class="col">
        <div id="DivDivTableLoad" class="text-center">
          <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
          </div>
        </div>
        <div id="DivTableProducts">
          <table id="TableProducts" class="display">
            <thead>
              <tr>
                <th>Наименование продукта</th>
                <th>Наименование магазина</th>
                <th>Цена (руб.)</th>
                <th>Дата сканирования</th>
                <th></th>
              </tr>
            </thead>
            <tbody><?php print($htmlTableContent); ?></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>



<!-- Modal -->
<div class="modal fade" id="DivModalObjectInfo" tabindex="-1" role="dialog" aria-labelledby="HTitleObjectInfo"
  aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="HTitleObjectInfo">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-3 text-right">Дополнительная информация:</div>
          <div class="col-md-3">
            <div id="DivObjectInfo"></div>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-6"><span id="DivObjectTextPrice">Последняя цена:</span></div>
          <div class="col-md-6 text-right"><span id="DivObjectPrice"></span><span> руб.</span></div>
        </div>
        <hr />
        <div class="row">
          <div class="col">
            <h5 class="h5">Динамика изменения цен</h5>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <div class="demo-container" style="box-sizing: content-box;">
              <div id="placeholder" class="demo-placeholder"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
      </div>
    </div>
  </div>
</div>