
var config = {        
    form : '#codeudorform',    
    validate : {
        'cedulaform' : {
            'validation' : 'required, length, number',
            'length' : '7-12',
             'help' : 'Escribe un número de cédula valido, no uses puntos, simbolos o espacios',
             '_data-sanitize': 'trim',
            'error-msg-required' : 'Escribe el número de cedula',
            'error-msg-length' : 'La cedula debe tener entre 7 y 12 caracteres',
            'error-msg-number' : 'Sólo puedes usar numeros, sin espacios o simbolos',
        },
        'nombre1form' : {
            'validation' : 'required, length, custom',
            'regexp' : '^[a-zA-ZÀ-ÖØ-öø-ÿ]+$',
            'length' : '4-25',  
            '_data-sanitize': 'trim',
            'error-msg-required' : 'Escribe el primer nombre del deudor',
            'error-msg-length' : 'El nombre debe tener entre 4 y 25 caracteres',
            'error-msg-custom' : 'Sólo puedes usar letras, sin espacios o simbolos',
        },
         'nombre2form' : {
            'validation' : 'length, custom',
            'regexp' : '^[a-zA-ZÀ-ÖØ-öø-ÿ]+$',
            'length' : '4-25',  
            '_data-sanitize': 'trim',
             'optional' : 'true',
            'error-msg-length' : 'El nombre debe tener entre 4 y 25 caracteres',
            'error-msg-custom' : 'Sólo puedes usar letras, sin espacios o simbolos',
        },
         'apellido1form' : {
            'validation' : 'required, length, custom',
            'regexp' : '^[a-zA-ZÀ-ÖØ-öø-ÿ]+$',
            'length' : '4-25',  
            '_data-sanitize': 'trim',
             'error-msg-required' : 'Escribe el primer apellido del deudor',
            'error-msg-length' : 'El apellido debe tener entre 4 y 25 caracteres',
            'error-msg-custom' : 'Sólo puedes usar letras, sin espacios o simbolos',
        },
         'apellido2form' : {
            'validation' : 'length, custom',
            'regexp' : '^[a-zA-ZÀ-ÖØ-öø-ÿ]+$',
            'length' : '4-25',  
            '_data-sanitize': 'trim',
             'optional' : 'true',
            'error-msg-length' : 'El apellido debe tener entre 4 y 25 caracteres',
            'error-msg-custom' : 'Sólo puedes usar letras, sin espacios o simbolos',
        },         
        'Nacimientoform' : {
            validation : 'birthdate',
            format : 'dd/mm/yyyy',
            'optional' : 'true',
            'error-msg-birthdate' : 'No puedes usar fechas posteriores a la actual',
        },
        'LugarNacimientoform' : {
            'validation' : 'custom',
            'regexp' : '^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*',
            //'length' : 'max50',
            //'optional' : true,
            //'_data-sanitize': 'trim',
            'depends-on' : 'deptonace',
            'error-msg' : 'Selecciona una ciudad',
            //'error-msg-custom' : 'Escribe un nombre valido, no uses simbolos ni signos de puntuación',
        },
        'Generoform' : {
            'optional' : 'true'
        },
        'EstadoCivilform' : {
            'optional' : 'true'
        },
        'escolaridadform' : {            
            'optional' : true,
        },
        'Profesionform' : {
            'validation' : 'length, custom',
            'regexp' : '^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*',
            'length' : '4-50',
            'optional' : true,
            '_data-sanitize': 'trim',
            'error-msg-length' : 'La profesión debe tener entre 4 y 50 caracteres',
            'error-msg-custom' : 'Escribe una profesion valida, no uses simbolos ni signos de puntuación',
        },
        'Oficioform' : {
            'validation' : 'length, custom',
            'regexp' : '^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*',
            'length' : '4-50',
            'optional' : true,
            '_data-sanitize': 'trim',
            'error-msg-length' : 'El oficio debe tener entre 4 y 50 caracteres',
            'error-msg-custom' : 'Escribe un oficio valido, no uses simbolos ni signos de puntuación',
        },
        'NombreEmpresaform' : {
            'validation' : 'length, custom',
            'regexp' : '^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*',
            'length' : '4-60',
            'optional' : true,
            '_data-sanitize': 'trim',
            'error-msg-length' : 'El nombre de la empresa debe tener entre 4 y 60 caracteres',
            'error-msg-custom' : 'Escribe un nombre de empresa valido, no uses simbolos ni signos de puntuación',
        },
        'Cargoform' : {
            'validation' : 'length, custom',
            'regexp' : '^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*',
            'length' : '4-50',
            'optional' : true,
            '_data-sanitize': 'trim',
            'error-msg-length' : 'El cargo debe tener entre 4 y 50 caracteres',
            'error-msg-custom' : 'Escribe un cargo valido, no uses simbolos ni signos de puntuación',
        },
        'Telefonoform' : {
            'validation' : 'length, custom',
            'length' : 'max13',
            'regexp' : '0{0,2}([\+]?[\d]{1,3} ?)?([\(]([\d]{2,3})[)] ?)?[0-9][0-9 \-]{6,}( ?([xX]|([eE]xt[\.]?)) ?([\d]{1,5}))?',            
            'help' : 'Ej. (55) 555-5555 Si el indicativo es un sólo digito, escribelo y luego presiona [ESPACIO] en tu teclado',
            'optional' : true,
            '_data-sanitize': 'trim',
            'error-msg-length' : 'El número de teléfono debe tener maximo 13 caracteres',
            'error-msg-custom' : 'No se reconoce el formato de este número de teléfono, intenta de la forma (##) ###-####',
        },
        'Direccionform' : {
            'validation' : 'length, custom',
            'length' : '10-80',
            'regexp' : '^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*',
            'optional' : true,
            'help' : 'Dirección: calle/carrera/avenida y el numero. Puedes incluir barrio, Nombre edificio o unidad, # bloque, # apto. # piso: Calle 55 # 55-55 Barrio Piso 4',
            'error-msg-length' : 'Escribe una dirección entre 10 y 80 caracteres',
            'error-msg-custom' : 'Escribe una dirección valida: Calle 55 # 55-55 Barrio Piso 4',
        },
        'CiudadEmpresaform' : {
            'validation' : 'custom',
            'regexp' : '^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*',
            //'length' : 'max50',
            //'optional' : true,
            //'_data-sanitize': 'trim',
            'depends-on' : 'deptotrabaja',
            'error-msg' : 'Selecciona una ciudad',
            //'error-msg-custom' : 'Escribe un nombre valido, no uses simbolos ni signos de puntuación',
        },
        'Emailform' : {
            'validation' : 'email',
            'help' : 'Escribe una cuenta Email valida Ej. usuario@sitioweb.com',
            '_data-sanitize': 'trim',
            //'error-msg-required' : 'Escribe una cuenta Email',
            'error-msg-email' : 'Escribe una cuenta email valida, puedes utilizar letras, números y puntos',
        },
        'Telefono1form' : {
            'validation' : 'length, custom',
            'length' : 'max13',
            'regexp' : '0{0,2}([\+]?[\d]{1,3} ?)?([\(]([\d]{2,3})[)] ?)?[0-9][0-9 \-]{6,}( ?([xX]|([eE]xt[\.]?)) ?([\d]{1,5}))?',
            'help' : 'Ej. (55) 555-5555 Si el indicativo es un sólo digito, escribelo y luego presiona [ESPACIO] en tu teclado',
            '_data-sanitize': 'trim',
            'optional-if-answered' : 'Telefono2form',
            //'error-msg-required' : 'Escribe un número de teléfono fijo',
            //'error-msg-container' : '#phoneerror',
            'error-msg-length' : 'El número de teléfono debe tener maximo 13 caracteres',
            'error-msg-custom' : 'No se reconoce el formato de este número de teléfono, intenta de la forma (##) ###-####',
            //'error-msg' : 'Escribe al menos un número de teléfono',
        },
        'Telefono2form' : {
            'validation' : 'length, custom',
            'length' : 'max12',
            'regexp' : '0{0,2}([\+]?[\d]{1,3} ?)?([\(]([\d]{2,3})[)] ?)?[0-9][0-9 \-]{6,}( ?([xX]|([eE]xt[\.]?)) ?([\d]{1,5}))?',
            //'error-msg' : 'Completa uno de los dos tipos de celular',
            'help' : 'Escribe un número celular Ej. 300 555-5555',
            'optional-if-answered' : 'Telefono1form',
            '_data-sanitize': 'trim',
            //'error-msg-container' : '#phoneerror',
            'error-msg-length' : 'El número de teléfono celular debe tener maximo 12 caracteres',
            'error-msg-custom' : 'No se reconoce el formato de este número celular, intenta de la forma ### ###-####',
            //'error-msg' : 'Escribe al menos un número de teléfono',
        },
        'existTelefono1form' : {
            'validation' : 'length, custom',
            'length' : 'max13',
            'regexp' : '0{0,2}([\+]?[\d]{1,3} ?)?([\(]([\d]{2,3})[)] ?)?[0-9][0-9 \-]{6,}( ?([xX]|([eE]xt[\.]?)) ?([\d]{1,5}))?',
            'help' : 'Ej. (55) 555-5555 Si el indicativo es un sólo digito, escribelo y luego presiona [ESPACIO] en tu teclado',
            '_data-sanitize': 'trim',
            'optional-if-answered' : 'existTelefono2form',
            //'error-msg-required' : 'Escribe un número de teléfono fijo',
            //'error-msg-container' : '#phoneerror',
            'error-msg-length' : 'El número de teléfono debe tener maximo 13 caracteres',
            'error-msg-custom' : 'No se reconoce el formato de este número de teléfono, intenta de la forma (##) ###-####',
            //'error-msg' : 'Escribe al menos un número de teléfono',
        },
        'existTelefono2form' : {
            'validation' : 'length, custom',
            'length' : 'max12',
            'regexp' : '0{0,2}([\+]?[\d]{1,3} ?)?([\(]([\d]{2,3})[)] ?)?[0-9][0-9 \-]{6,}( ?([xX]|([eE]xt[\.]?)) ?([\d]{1,5}))?',
            //'error-msg' : 'Completa uno de los dos tipos de celular',
            'help' : 'Escribe un número celular Ej. 300 555-5555',
            'optional-if-answered' : 'existTelefono1form',
            '_data-sanitize': 'trim',
            //'error-msg-container' : '#phoneerror',
            'error-msg-length' : 'El número de teléfono celular debe tener maximo 12 caracteres',
            'error-msg-custom' : 'No se reconoce el formato de este número celular, intenta de la forma ### ###-####',
            //'error-msg' : 'Escribe al menos un número de teléfono',
        },
        'DireccionResidenciaform' : {
            'validation' : 'length, custom',
            'length' : '10-80',
            'regexp' : '^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*',  
            '_data-sanitize': 'trim',
            'help' : 'Domicilio: calle/carrera/avenida y el numero: Calle 55 # 55-55',
            'optional' : true,
            'error-msg-length' : 'Escribe una dirección entre 10 y 80 caracteres',
            'error-msg-custom' : 'Escribe una dirección valida: Calle 55 # 55-55',
        },
        'existeDireccionResidenciaform' : {
            'validation' : 'length, custom',
            'length' : '10-80',
            'regexp' : '^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*',  
            '_data-sanitize': 'trim',
            'help' : 'Domicilio: calle/carrera/avenida y el numero: Calle 55 # 55-55',
            'optional' : true,
            'error-msg-length' : 'Escribe una dirección entre 10 y 80 caracteres',
            'error-msg-custom' : 'Escribe una dirección valida: Calle 55 # 55-55',
        },
        'Complementoform' : {
            'validation' : 'length, custom',
            'regexp' : '^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*',
            'length' : 'max80',
            'help' : 'Incluye más detalles del domicilio: Nombre edificio o unidad, # bloque, # apto. # piso ',
            'optional' : true,
            '_data-sanitize': 'trim',
            'error-msg-length' : 'Utiliza hasta 80 caracteres',
            'error-msg-custom' : 'Puedes usar letras números y simbolos',
        },
        'Barrioform' : {
            'validation' : 'length, custom',
            'regexp' : '^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*',
            'length' : 'max50',
            'optional' : true,
            '_data-sanitize': 'trim',
            'error-msg-length' : 'Utiliza hasta 50 caracteres',
            'error-msg-custom' : 'Escribe un nombre de barrio valido',
        },
        'TipoViviendaform' : {
            'optional' : true,
        },
        'Estratoform' : {
            'validation' : 'length, number',
            'length' : 'max2',
            'optional' : true,
            'error-msg-length' : 'Max. 2 caracteres',
            'error-msg-number' : 'Selecciona una de las opciones mostradas',
        },
        'txtEndereco' : {
            'validation' : 'required',            
            'help' : 'Escribe la dirección domicilio, incluye la ciudad y pais. Debes seleccionarla, cuando la veas en la lista ',
            'error-msg-required' : 'Escribe la dirección de domicilio para ubicarla en el mapa',
        },
        'comentariosform' :{
            'validation' : 'length, custom',
            'length' : 'max200',
            'regexp' : '^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*',
            'optional' : true,
            'error-msg-length' : 'Utiliza hasta 200 caracteres',
            'error-msg-custom' : 'Escribe alguna reseña para este usuario, puedes usar letras, números y signos de puntuación',
        }
    }
};

$.validate({
    modules : 'jsconf, html5, sanitize, date, logic',
    lang : 'es',    
    onModulesLoaded : function() {
        $.setupValidation(config);
    }
});

