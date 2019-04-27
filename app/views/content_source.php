<div class="content app-listres">
    <div class="container">
        <div class="row">
            <div class="col">
                <h1 class="h1">Список анализируемых ресурсов</h1>
            </div>
        </div>
        <hr />
        <table class="table table-hover table-dark">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Наименование ресурса</th>
                    <th scope="col" class="text-right">Дата последнего запуска</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $n = 0;
                foreach ($ArraySourceObject as $item) {
                    $n++;
                    echo '<tr>';
                    echo '<th scope="row">'.$n.'</th>';
                    echo '<td>'.$item["name"].'</td>';
                    echo '<td class="text-right">'.$item["date"].'</td>';
                    echo '<td class="text-right"><a href="'.$item["url"].'" target="_blank" class="btn btn-outline-light ">Перейти на сайт</a>  </td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>

    </div>
</div>