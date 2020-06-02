
var table;

$(document).ready(function() {

table = $('#myTable').DataTable( {
    		"ajax":'Controllers/loadData.php',
    		"order" : []
		}); 

getBalance();
	
$("#addModalButton").on('click', function(){
		getBalance();

		$("#addRecordForm")[0].reset();
		$(".form-group").removeClass('has-error');
		$(".text-danger").remove();

	$("#addRecordForm").submit(function(e){
	//Отключение стандартной отправки формы
	e.preventDefault();
	$(".text-danger").remove();

	var $form = $(this);
	var description = $("#description").val();
	var operation = $("#operation").val();
    var amount = $("#amount").val();

	var url = $form.attr('action');

	if(description == ""){
				$("#description").closest('.form-group').addClass('has-error');
				$("#description").after('<p class="text-danger">Поле описания не может быть пустым</p>');
			}else {
				$("#description").closest('.form-group').removeClass('has-error');
			}

	if(operation == ""){
				$("#operation").closest('.form-group').addClass('has-error');
				$("#operation").after('<p class="text-danger">Операция не может быть пустой</p>');
			}else {
				$("#operation").closest('.form-group').removeClass('has-error');
			}
	if(description && operation ){
		var posting = $.post(
				url,
			{
				description : description,
				operation : operation,
				amount : amount
			},
				testFunc
			);

	 	}
	
 			$("#addRecordForm")[0].reset();
 		
 			$("#addModal").modal('hide');

 			table.ajax.reload(null,false);
 		
		});
	});

});

function testFunc(data){
	getBalance();
	
	alert(data);
}

function deleteRecord(id = null){
	if(id)
	{
		$("#removeButton").on('click', function() {
			$.ajax({
				url: 'Controllers/delete_record.php',
				type: 'post',
				data: {recordId : id},
				dataType: 'json',
				success: function(response){
					if(response.success == true){
						$(".removeMessages").html('<div class="alert alert-success" role="alert">'+
							'<button type="button" class="close" data-dismiss="alert" aria-label="Close">Закрыть</button>'
							+response.messages+
							'</div>');

							
						table.ajax.reload(null,false);

						$("#deleteRecordModal").modal('hide');

					}else{
						$(".removeMessages").html('<div class="alert alert-warning" role="alert">'+
							'<button type="button" class="close" data-dismiss="alert" aria-label="Close">Закрыть</button>'
							+response.messages+
							'</div>');
					}
				}
			});
		});
	}else{
		alert("Ошибка, перезагрузите страницу.");
	}
}

function getBalance(){
	$.ajax({
		url: 'Controllers/getUserBalance.php',
		dataType:'json',
		success: function(response){
			 $("#balance").val(response.balance) ;
		}
	});
}

function editRecord(id = null){
	if(id){

		$(".form-group").removeClass('has-error');
		$(".text-danger").remove();
		$('.editMessages').html('');

		$("#editId").remove();

		$.ajax({
			url: 'Controllers/getSelectedRecords.php',
			type: 'post',
			data: { recordId : id },
			dataType : 'json',
			success: function(response){

					$("#editDescription").val(response.description);
					$("#editOperation").val(response.operation);
					$("#editAmount").val(response.amount);
					$(".editFooter").append('<input type="hidden" name="editId" id="editId" value="'+response.id+'" />');

					$("#updateRecordForm").unbind('submit').bind('submit', function(){
						
						$(".text-danger").remove();

						var formEdit = $(this);
						var editId = response.id;

						var editDescription = $("#editDescription").val();
						var editOperation = $("#editOperation").val();
					    var editAmount = $("#editAmount").val();
						var url = formEdit.attr('action');

						if(editDescription == ""){
									$("#editDescription").closest('.form-group').addClass('has-error');
									$("#editDescription").after('<p class="text-danger">Поле описания не может быть пустым</p>');
								}else {
									$("#editDescription").closest('.form-group').removeClass('has-error');
								}

						if(editOperation == ""){
									$("#editOperation").closest('.form-group').addClass('has-error');
									$("#editOperation").after('<p class="text-danger">Операция не может быть пустой</p>');
								}else {
									$("#editOperation").closest('.form-group').removeClass('has-error');
								}
						if(editDescription && editOperation ){

							$.ajax({
								url: 'Controllers/update.php',
								type: 'post',
								data: formEdit.serialize(),
								dataType: 'json',
								success: function(response){
									if(response.success == true){

										$(".editMessages").html('<div class="alert alert-success" role="alert">'+
										'<button type="button" class="close" data-dismiss="alert" aria-label="Close">Закрыть</button>'
										+response.messages+
										'</div>');

										table.ajax.reload(null,false);
										
										$("#editRecordModal").modal('hide');

									}else{

										$(".editMessages").html('<div class="alert alert-warning" role="alert">'+
										'<button type="button" class="close" data-dismiss="alert" aria-label="Close">Закрыть</button>'
										+response.messages+
										'</div>');

									}
								}
							});
						}
						return false;
					});
			}

		});
	}else{
		alert('Ошибка, перезагрузите страницу.');
	}
}



