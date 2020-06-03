<!doctype html />
<html>
<head>

<link rel="stylesheet" href="Assets/bootstrap.min.css" />
<link rel="stylesheet" href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" />
<link rel="stylesheet" type="text/css" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">


</head>
<body> 

<header>

<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
  <h5 class="my-0 mr-md-auto font-weight-normal " >Домашний бухгалтер  </h5>

  <?php if(empty($users)):?>
  <a class="btn btn-outline-primary" href="Controllers/registration.php">Регистрация</a>
  <a class="btn ml-1 btn-outline-primary" href="Controllers/authorization.php">Авторизация</a>
  </div>
</header>
<div class="container alert alert-info" role="alert">
    <h5 class="mt-0">Добро пожаловать, для продолжения работы, пожалуйста, пройдите регистрацию или авторизируйтесь на сайте.</h5>
</div>
  <?php endif; ?>
  
  
  <?php if(!empty($users)):?>
  <nav class="my-2 my-md-0 mr-md-3">
  <table>
  <tr>
    
  <td>
    <button type="button" id="addModalButton" class="btn btn-outline-primary" data-toggle="modal" data-target="#addModal">Добавить</button>
  </td>
  </tr>
  </table>
  </nav>
   <a class="btn ml-1 btn-outline-primary" href="?logout">Выход</a>
   </div>
</header>

<div class="container">
  <div class="row">
    <div class="col-md-12">

        <div class="removeMessages"></div>
         <div class="editMessages"></div>


        <table id="myTable" class="display">
          <thead>
              <tr>
                  <th>Номер записи</th>
                  <th>Описание</th>
                  <th>Операция</th>
                  <th>Сумма</th>
                  <th>Дата создания</th>
                  <th>Дата редактирования</th>
                  <th>Действие</th>
              </tr>
          </thead>
    </table>

    </div>      
    </div>
</div>

   
<!-- Начало модального окна добавления записи --> 

  <div class="modal fade" tabindex="-1" role="dialog" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Добавить запись</h5>
                <button type="button" class="close" data-dismiss="modal">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <form class="form-horizontal" action="Controllers/addRecord.php" method="POST" id="addRecordForm">

              <div class="modal-body">

                


                <div class="form-group">
                  <label for="description" class="col-sm-2 control-label" >Описание</label>
                  <div class="col-sm-12">
                    <input type="text" name="description" id="description" class="form-control" placeholder="Описание"/>
                  </div>
                </div>

                <div class="form-group">
                  <label for="operation" class="col-sm-2 control-label" >Операция</label>
                  <div class="col-sm-12">
                    <select class="form-control" name="operation"  id="operation">
                      <option value="">Выбрать</option>
                      <option value="profit">Доходы</option>
                      <option value="spending">Затраты</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label for="amount" class="col-sm-2 control-label" >Сумма</label>
                  <div class="col-sm-12">
                     <input  type="number" step="any" min="0.01" name="amount" id="amount" value="1" class="form-control" placeholder="Сумма"/>
                  </div>
                </div>

                

                <div class="form-group">
                    <label for="balance" class="col-sm-2 control-label" >Баланс</label>
                  <div class="col-sm-12">
                    <input  type="number" readonly="readonly" id="balance" name="balance" step="any" value="<?=$user['balance']?>" class="form-control" placeholder="Баланс"/>
                  </div>
                  
                </div>

              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button type="submit" id="addButton" class="btn btn-primary">Добавить</button>
              </div>
              </form>
            </div>
          </div>
  </div>
  <!-- Конец модального окна добавления записи --> 

  <!-- Начало модального окна редактирования записи -->
      <div class="modal fade" tabindex="-1" role="dialog" id="editRecordModal">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header editHeader">
              <h5 class="modal-title"><span class="fa fa-pencil-square" aria-hidden="true"></span> Редактирование записи</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

              <form class="form-horizontal" action="Controllers/update.php" method="POST" id="updateRecordForm">

                <div class="modal-body editBody">

                    <div class="form-group">
                        <label for="description" class="col-sm-2 control-label" >Описание</label>
                        <div class="col-sm-12">
                          <input type="text" name="editDescription" id="editDescription" class="form-control" placeholder="Описание"/>
                        </div>
                      </div>

                       

                      <div class="form-group">
                        <label for="editAmount" class="col-sm-2 control-label" >Сумма</label>
                        <div class="col-sm-12">
                           <input  type="number" step="any" min="0.01" name="editAmount" id="editAmount" value="1" class="form-control" placeholder="Сумма"/>
                        </div>
                      </div>

                      <div class="form-group">
                          <label for="balance" class="col-sm-2 control-label" >Баланс</label>
                        <div class="col-sm-12">
                          <input  type="number" readonly="readonly" id="editBalance" name="editBalance" step="any" value="<?=$user['balance']?>" class="form-control" placeholder="Баланс"/>
                        </div>
                      </div>

                </div>
                <div class="modal-footer editFooter">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                  <button type="submit" class="btn btn-primary" id="editButton">Подтвердить</button>
                </div>
          </form>
          </div>
        </div>
      </div>

   <!-- Конец модального окна редактирования записи --> 


<!-- Начало модального окна удаления записи --> 
<div class="modal fade" tabindex="-1" role="dialog" id="deleteRecordModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><span class="fa fa-trash" aria-hidden="true"></span> Удалить запись</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Вы уверенны в своих действиях?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
        <button type="button" class="btn btn-primary" id="removeButton">Подтвердить</button>
      </div>
    </div>
  </div>
</div>
<!-- Конец модального окна удаления записи --> 

<?php   endif; ?>

<script src="Assets/jquery-3.5.1.min.js"> </script>
<script src="Assets/bootstrap.bundle.min.js"> </script>
<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"> </script>

<script src="Assets/scripts.js"></script>
<script src="Assets/bootstrap.js"></script>

</body>
</html>



