$(document).ready(function(){
    $('input.editexist').focus(function() {
        $(this).css('background-color','#ffffff');
    });
    
    
    /*
    *DATOS DE CONTACTO
    */
    $('.editexist').blur(function(){
        var field = $(this);
        var parent = field.parent().attr('id');
        var emtyvalue = $(this).val();
        var datafield = $(this).attr('data-field');
        var pathfile = $("#pathfile").val();
        var pathdir = $("#pathdir").val();
        var paththisfile = $("#paththisfile").val();
        var post = $("#itemexistform").val();
        
        field.css('background-color','#F3F3F3');
                        
        $('#'+parent).append('<div class="loaderrow"><img src="../../appweb/img/loader.gif"/></div>').fadeIn('slow');
        
        var dataString = 'value='+$(this).val()+'&field='+$(this).attr('name')+'&fieldedit='+$(this).attr('data-field')+'&post='+post;

        //console.log(dataString);

        $.ajax({
            type: "POST",
            url: "crud-user.php",
            data: dataString,
            success: function(data) {
                //field.val(data);
                var response = JSON.parse(data);
                                
                //console.log(existeJSON);
                if (response['error']) {
                    
                    $("#err"+datafield).fadeOut();
                    $('#'+parent+' .loaderrow').fadeOut();

                }else{
                   
                    $('#'+parent+' .loaderrow').fadeOut();
                    field.val(response);

                }
            }
        });
        
    }); 
    
    
    /*
    *GEO DOMICILIO
    */
    $('.editexistgeodir').blur(function(){
        var field = $(this);
        var parent = field.parent().attr('id');
        var emtyvalue = $(this).val();
        var datafield = $(this).attr('data-field');
        var pathfile = $("#pathfile").val();
        var pathdir = $("#pathdir").val();
        var paththisfile = $("#paththisfile").val();
        var post = $("#itemexistform").val();
        
        field.css('background-color','#F3F3F3');
        
        if(post != ""){
            
            $('#'+parent).append('<div class="loaderrow"><img src="../../appweb/img/loader.gif"/></div>').fadeIn('slow');

            var addressfull = $('#txtEndereco').val();
            var citi1 = $('#locality').val();
            var citi2 = $('#administrative_area_level_2').val();
            var citi3 = $('#administrative_area_level_3').val();                
            var zip = $('#postal_code').val();
            var state = $('#administrative_area_level_1').val();
            var shortLevel1= $('#administrative_area_short_level_1').val();
            var country = $('#country').val();
            var codCountry = $('#countrycod').val();
            var idplace = $('#idplacegeomap').val();
            var lati = $('#txtLatitude').val();
            var long = $('#txtLongitude').val();

            var dataString = 'citi1var='+citi1+'&citi2var='+citi2+'&citi3var='+citi3+'&addressfullvar='+addressfull+'&zipvar='+zip+'&statevar='+state+'&shortStatevar'+shortLevel1+'&countryvar='+country+'&codecountryvar='+codCountry+'&idplacevar='+idplace+'&lativar='+lati+'&longvar='+long+'&post='+post+'&fieldedit='+$(this).attr('data-field')+'&value='+addressfull+'&field='+$(this).attr('name');

            //console.log(dataString);
        
        
            $.ajax({
                type: "POST",
                url: "crud-user.php",
                data: dataString,
                success: function(data) {
                    //field.val(data);
                    var response = JSON.parse(data);

                    //console.log(existeJSON);
                    if (response['error']) {

                        $("#err"+datafield).fadeOut();
                        $('#'+parent+' .loaderrow').fadeOut();

                    }else{

                        $('#'+parent+' .loaderrow').fadeOut();
                        field.val(response);
                        $("#geodomicilio").html(response).fadeIn();

                    }
                }
            });   
        }
                
    }); 
                                
});