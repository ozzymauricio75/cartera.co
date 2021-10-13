
var config = {        
    form : '#creditoform',    
    validate : { 
        'montoinput' : {
            'validation' : 'required, length, number',            
            'length' : 'max9',
            '_data-sanitize': 'trim',
            'error-msg-required' : 'Escribe el valor a prestar',
            'error-msg-length' : 'Escribe Max. 9 digitos',
            'error-msg-number' : 'Escribe un valor numerico, no uses puntos, simbolos o espacios',
            'help' : 'Escribe el valor total a prestar, usa números'
        },'valtotalpagarinput' : {
            'validation' : 'required, length, number',            
            'length' : 'max9',
            '_data-sanitize': 'trim',
            'error-msg-required' : 'Escribe el valor del credito',
            'error-msg-length' : 'Escribe Max. 9 digitos',
            'error-msg-number' : 'Escribe un valor numerico, no uses puntos, simbolos o espacios',
            'help' : 'Escribe el valor a pagar del credito, usa números'
        },
         'tipocreditoinput' : {
            'validation' : 'required',   
            'error-msg-required' : 'Escoge un tipo de crédito'
        },
         'descricreditoinput' : {
            'validation' : 'length, custom',
            'regexp' : '^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*',
            'length' : 'max200',
            'optional' : true,
            'help' : 'Haz una descripción. Esto te ayudará a conocer el concepto del prestamo ',
            'error-msg-length' : 'Puedes escribir hasta 200 caracteres',
            'error-msg-custom' : 'Puedes usar letras, números y signos de puntuación',
        },
         /*'capitalcuotainput' : {
            'validation' : 'required, length, number',
            //'help' : 'Escribe un valor numerico, no uses puntos, simbolos o espacios',
            'error-msg' : 'Este campo es obligatorio<br>Max. 9 digitos<br>Escribe un valor numerico, no uses puntos, simbolos o espacios',
            'length' : 'max9',
            '_data-sanitize': 'trim',
        },*/
        'interescuotainput' : {
            'validation' : 'required, length, number',
            //'help' : 'Escribe un valor numerico, no uses puntos, simbolos o espacios',
            'error-msg-required' : 'Escribe el interes de la cuota',
            'error-msg-length' : 'Max. 6 digitos',
            'error-msg-number' : 'Escribe un valor numerico, no uses puntos, simbolos o espacios',
            'length' : 'max6',
            '_data-sanitize': 'trim',
        },
        
        'moracutoainput' : {
            'validation' : 'length, number',
            //'help' : 'Escribe un valor numerico, no uses puntos, simbolos o espacios',
            'error-msg' : 'Este campo es obligatorio<br>Max. 9 digitos<br>Escribe un valor numerico, no uses puntos, simbolos o espacios',
            'length' : 'max9',
            'optional' : true,
            '_data-sanitize': 'trim',
        },
        'sobcargocuotainput' : {
            'validation' : 'length, number',
            //'help' : 'Escribe un valor numerico, no uses puntos, simbolos o espacios',
            'error-msg' : 'Este campo es obligatorio<br>Max. 9 digitos<br>Escribe un valor numerico, no uses puntos, simbolos o espacios',
            'length' : 'max9',
            'optional' : true,
            '_data-sanitize': 'trim',
        },
        /*'numecuotasinput' : {
            'validation' : 'required, length, number',
            'help' : 'Escribe un valor numerico, no uses puntos, simbolos o espacios',
            'length' : 'max6',
            '_data-sanitize': 'trim',
        },*/
        /*'.datepicker' : {
            validation : 'date',
            format : 'yyyy-mm-dd'
        },
        'fechainiciocreditoinput' : {
            'validation' : 'required',
            //'format' : 'd/m/yyyy',
            'error-msg-required' : 'Escribe la fecha de inicio del prestamo',
            //'error-msg-date' : 'Selecciona una fecha del calendario',
        },*/
        'plazoinput' : {
            'validation' : 'required, number',
            //format : 'yyyy-mm-dd'
            'error-msg-required' : 'Escribe la fecha de inicio del prestamo',
            'error-msg-number' : 'Sólo puedes escribir números',
        },
        'periocidadcuotainput' : {
            'validation' : 'required',   
             'error-msg-required' : 'Selecciona la periocidad de pago'
        },
        'cobradorinput' : {
            'validation' : 'required',   
             'error-msg' : 'Selecciona una opción'
        }                                         
    }
};

$.validate({
    modules : 'jsconf, html5, sanitize, date',
    lang : 'es',    
    onModulesLoaded : function() {
        $.setupValidation(config);
    }
});

