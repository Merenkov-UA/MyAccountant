
<!DOCTYPE html />
<html>
<head>

<link rel="stylesheet" href="../Assets/bootstrap.min.css" />
<link rel="stylesheet" href="../Assets/style.css" />

</head>
<body class="text-center">

<header>

<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
  <h5 class="my-0 mr-md-auto font-weight-normal " >Домашний бухгалтер </h5>
  <button class="btn btn- btn-primary  mt-1 mainBtn" type="button" ><a href="../index.php" >На главную</a></button>
</div>

</header>
    <form class="form-signin authForm" method="POST" action="../Controllers/authorization.php">
  <div class="container authContainer">
  <h1 class="h3 mb-3 font-weight-normal" >Пожалуйста авторизируйтесь</h1>
  <label for="inputLogin"  class="sr-only">Логин</label>
  <input type="text" id="inputLogin" name="login" class="form-control-sm" placeholder="Логин" required="" autofocus="">
  <label for="inputPassword" class="sr-only">Password</label>
  <input type="password"  id="inputPassword" name="password" class="form-control-sm" placeholder="Пароль" required=""><br/>
  
  <button class="btn btn- btn-primary mt-3" type="submit">Войти</button>
  </div>
  
</form>

<script src="../Assets/jquery-3.4.1.min.js"></script>
<script src="../Assets/bootstrap.min.js"></script>
</body>
</html>