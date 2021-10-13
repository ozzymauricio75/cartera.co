$(document).ready(function(){
    
    /*
    *OPCIONES ADMINISTRA CREDITO
    */    
    $('.admincreditbtn').each(function(){
                                
        function adminItem(titleEv, msjEv, fieldedit, post){
            swal({
              title: titleEv, 
              text: msjEv, 
              type: 'warning',
              showCancelButton: true,
              confirmButtonText: "Continuar",
              cancelButtonText: "Cancelar",
              closeOnConfirm: true,
              closeOnCancel: true,
              animation: false,
              html: true
            }, function(isConfirm){
                  if (isConfirm) {
                    confiAdminItem(fieldedit, post);
                  } else {
                    return false;	
                  }
                });
          }
        
        
        function confiAdminItem(fieldedit, post){
            
            var dataString = 'fieldedit='+fieldedit+'&post='+post; 
            
            $.ajax({
                type: "POST",
                url: "crud-credit.php",
                data: dataString,
                success: function(data) {

                    var response = JSON.parse(data);                        
                    if (response['error']) {           
                        
                        $("#erradmincredit").html(response['error']).fadeIn('slow');                            
                    } else {
                                                
                        $("#erradmincredit").fadeOut();    
                        $("#successadmincredit").html("<div class='alert bg-success alert-dismissible box50 text-success'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><p><i class='icon fa fa-check fa-2x margin-right-xs'></i> Los cambios fueron guardados correctamente</p></div>").fadeIn(function(){
                            location.reload();
                        });
                                                
                    }
                }
            });
        };
        
        $(this).click(function(e) {
            e.preventDefault(); 
            
            var post = $(this).attr("data-post");
            var titleEv = $(this).attr("data-title");
            var msjEv = $(this).attr("data-msj");            
            var fieldedit = $(this).attr('data-field');
            
            adminItem(titleEv, msjEv, fieldedit, post);
        });
    });
    
    
    
    
    /*
    *REFINANCIAR CREDITO
    */    
    $('.refinanciarcredits').each(function(){
                                
        function refinanciarItem(titleEv, msjEv, fieldedit, post, deudor, href){
            swal({
              title: titleEv, 
              text: msjEv, 
              type: 'warning',
              showCancelButton: true,
              confirmButtonText: "Continuar",
              cancelButtonText: "Cancelar",
              closeOnConfirm: true,
              closeOnCancel: true,
              animation: false,
              html: true
            }, function(isConfirm){
                  if (isConfirm) {
                    redirectRefinanciarItem(fieldedit, post, deudor, href);
                  } else {
                    return false;	
                  }
                });
          }
        
        
        function redirectRefinanciarItem(fieldedit, post, deudor, href){
            $.redirect(href,{ creditcod: post, deudorcod: deudor, creditevent: fieldedit});             
        };
        
        $(this).click(function(e) {
            e.preventDefault(); 
            
            var href = $("#pathnewcredit").val();
            var post = $(this).attr("data-post");
            var deudor = $(this).attr("data-deudor");
            var titleEv = $(this).attr("data-title");
            var msjEv = $(this).attr("data-msj");            
            var fieldedit = $(this).attr('data-field');
            
            refinanciarItem(titleEv, msjEv, fieldedit, post, deudor, href);
        });
    });
                                
});