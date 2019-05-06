<div class="content app-authentication">
    <div class="container text-center">
        <div class="row">
            <div class="col">
                <div class="login">
                    <form id="FormLogin">
                        <div class="form-group">
                            <img class="mb-4" src="img/logo.png" height="64px" alt="CCA">
                            <h1 class="h3 mb-3 font-weight-normal">Авторизация</h1>
                        </div>
                        <div class="form-group">
                            <input type="text" name="login" id="inputLogin" class="form-control"
                                placeholder="Логин пользователя" value="">
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" id="inputPassword" class="form-control"
                                placeholder="Пароль" value="">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="login">
                    <button class="btn btn-lg btn-primary btn-block" id="ButtonAuth">Войти</button>
                    <a href="registration.php" class="btn btn-lg btn-outline-primary btn-block">Зарегистрироваться</a>
                </div>
            </div>
        </div>
        <div class="row" style="min-height: 150px;">
            <div class="col">
                <div class="card text-white bg-danger mb-3" id="DivAuthError">
                    <div class="card-header">Внимание!</div>
                    <div class="card-body">
                        <h4 class="card-title">Не правильный логин или пароль пользователя!</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>