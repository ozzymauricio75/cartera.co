//CALCULA VALORES
$(document).ready(function(){

    $(".addprodlist").keyup(function() {

            //cubicaje
        var cantList=$(this).find("input[name=cantListAdd]").val(); 
        var VentaList=$(this).find("input[name=priceVentaListAdd]").val();
        var dctoList=$(this).find("input[name=dctoList]").val();//
        var priceCostListEdit=$(this).find("input[name=priceCostListEdit]").val();
        var dctListFormat=(100-parseFloat(dctoList))/100;

        if(VentaList > 0){

            $(this).find("input[name=dctoList]").val(((parseFloat(VentaList)-parseFloat(priceCostListEdit))/parseFloat(VentaList))*100); 
            var dctoListInsert = ((parseFloat(VentaList)-parseFloat(priceCostListEdit))/parseFloat(VentaList))*100; 
            var dctListInsertFormat=(100-parseFloat(dctoListInsert))/100;

            $(this).find("[class=valorVentaList]").html(parseFloat(priceCostListEdit)/parseFloat(dctListInsertFormat)).number( true, 2 ); 
            var valUnitListInsert=parseFloat(priceCostListEdit)/parseFloat(dctListInsertFormat);

            $(this).find("[class=subTotalList]").html(parseInt(cantList)*parseFloat(valUnitListInsert)).number( true, 2 );

            $(this).find("[class=valorVentaListMsj]").text( parseFloat(dctoListInsert) < 0 ? "El precio de venta esta por debajo del costo!!!" : "" );

        }else{
                    //$(this).find("input[name=dctoList]").val().number( false ); 

            $(this).find("[class=valorVentaList]").html(parseFloat(priceCostListEdit)/parseFloat(dctListFormat)).number( true, 2 ); 
            var valUnitList=parseFloat(priceCostListEdit)/parseFloat(dctListFormat); 

            $(this).find("[class=subTotalList]").html(parseInt(cantList)*parseFloat(valUnitList)).number( true, 2 );

            $(this).find("[class=valorVentaListMsj]").text( parseFloat(dctoList) < 0 ? "El precio de venta esta por debajo del costo!!!" : "" );
        }

    }); 

});
