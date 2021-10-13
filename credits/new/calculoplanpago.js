(function ($, window, document) {
    function siRespuesta(r){
        
        var rHtml = '<div class="row">';
        rHtml += '<div class="col-xs-12 col-sm-6">';
        rHtml += '<div class="form-group">';
        rHtml += '<h4>número de cuotas</h4>';
        rHtml += '<input type="text" name="numecuotasinput" class="form-control col-xs-6" value="'+r.totalcuotas+'" placeholder="0" disabled>';
        rHtml += '</div>';//FIN FORM GROUP  
        rHtml += '</div>';//FIN COL
        
        rHtml += '<div class="col-xs-12 col-sm-6">';
        rHtml += '<div class="form-group">';
        rHtml += '<h4>Fecha final de pago</h4>';
        rHtml += '<input type="text" name="numecuotasinput" class="form-control col-xs-6" value="'+r.fechaplazo+'" disabled>';
        rHtml += '</div>';//FIN FORMGROUP    
        rHtml += '</div>';//FIN COL
        
        rHtml += '</div>';//FIN ROW
        
        rHtml += '<h4>Valor neto Cuota <small>(Capital)<small></h4>';
        rHtml += '<div class="form-group">';
        rHtml += '<div class="input-group">';                 
        rHtml += '<div class="input-group-addon"><i class="fa fa-dollar"></i></div>'; 
        rHtml += '<input type="text" name="valorcuotainput" class="form-control " value ="'+r.totalcuotaval+'"  placeholder="0" disabled>';
        rHtml += '<input type="hidden" name="capitalcuotainput" class="form-control justnumber" value ="'+r.totalcapital+'"  placeholder="0" disabled>';
        rHtml += '</div>';//INPOUT GROUP        
        rHtml += '</div>';//FIN FORMGROUP
                                
        $('#resplanpago').html(rHtml); 
        
        var capitalcuota = r.valorcapitalhidden
        var valorcuota = r.valorcuotahidden
        
        $('#capitalcuotahidden').val(capitalcuota);
        $('#valorcuotahidden').val(valorcuota);
    }
 
    function siError(data){
        alert('Erro: '+e.responseText);               
    }
 
    function peticion(e,idfull){
        
		//var var1 = $('input[name="iditem"]').val(); 
        var var1 = idfull; 
        /*$('input[name="iditem"]').on('change', function(){
            var1 = $(this).val();           
        });*/
        
        
        /*var var1 = $('.iditem').each(function(){
            $(this).val();                   
        });*/
		
		var post = $.post("detailtmpl.php", {variable1: var1}, siRespuesta, 'json');
 
    	post.error(siError);  
    }
 
    //$('.viewfull').each(function(){
    //$('button[name="viewfull"]').each(function(){
    $('button#calplanpago').click(function(){
        var valmontocredito = $('#montoinput').val();        
        var valpagar = $('#valtotalpagarinput').val();        
        var fechainicial = $("input[name='fechainiciocreditoinput']").val();        
        var plazo = $("input[name='plazoinput']").val();        
        var periocidad;
        $("input[name='periocidadcuotainput']").each(function(){
            if($(this).is(":checked")){
                periocidad = $(this).attr("data-tagperio");                 
            }
        });
        
        
        
        //$(this).click(peticion(idfull));                    
        //alert(idfull);
        
        //$(this).click(function(){
            var var1 = valmontocredito;
            var var2 = fechainicial;
            var var3 = plazo;
            var var4 = periocidad;
            var var5 = valpagar;
            var post = $.post("calculoplanpago.php", {variable1: var1, variable2: var2, variable3: var3, variable4: var4, variable5: var5 }, siRespuesta, 'json');
            //var post = $.post("detailtmpl.php", {variable1: var1}, detaFull);
            
            /////////////////////////////////////////////////////////////
            
            //var detaFull = angular.module('morphDemo', ['ngMorph']);//angular.module('myApp.emailModal', ['ui.bootstrap', 'ui.bootstrap.tpls']);                        
            ////////////////////////////////////////////////////////////
            post.error(siError);  
        //console.log(post);
        //});    
    });
    
}(jQuery, window, document));