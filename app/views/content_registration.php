<div class="content app-registration">
    <div class="container">
        <div class="row">
            <div class="col text-center">
                <h1 class="h1">
                    Регистрация нового пользователя
                </h1>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-6">
                <img src="img/multiple-users-silhouette.png" class="rounded mx-auto d-block" style="width: 80%;"
                    alt="...">
            </div>
            <div class="col-md-6">
                <form id="FormRegistration">
                    <div class="form-group">
                        <label for="InputFullName">Полное имя</label>
                        <input type="text" class="form-control" name="name" aria-describedby="emailHelp"
                            placeholder="Введите полное имя" value="">
                        <small id="Smal" class="form-text text-muted">Ваше полное имя (как к вам обращаться)</small>
                    </div>
                    <div class="form-group">
                        <label for="InputEmail">Электронная почта</label>
                        <input type="email" class="form-control" name="email" aria-describedby="SmalEmail"
                            placeholder="Введите адрес" value="">
                        <small id="SmalEmail" class="form-text text-muted">Ваш адрес электронной почты</small>
                    </div>
                    <div class="form-group">
                        <label for="InputLogin">Логин</label>
                        <input type="text" class="form-control" name="login" placeholder="Введите логин" value="">
                    </div>
                    <div class="form-group">
                        <label for="InputPassword">Пароль</label>
                        <input type="password" class="form-control" name="password" placeholder="Введите пароль">
                    </div>
                    <div class="form-group">
                        <label for="InputRepeatPassword">Повторите пароль</label>
                        <input type="password" class="form-control" placeholder="Повторите пароль">
                    </div>
                </form>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col text-center">
                <button id="ButonRegistration" class="btn btn-primary btn-lg">Зарегистрироваться</button></div>
        </div>
    </div>
</div>