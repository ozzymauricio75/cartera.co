//$(document).load(function() {   
    var config = {        
        form : '#newstoreform',    
        validate : {
            'nomerepre' : {
                'validation' : 'required, custom',
                'length' : '4-40',
                'regexp' : '^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*'
            },
            'commentrepre' :{
                'validation' : 'custom',
                'length' : '4-60',
                'regexp' : '^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*',
                'optional' : true
            },
            'nomestore' : {
                'validation' : 'custom',
                'length' : '4-40',
                'regexp' : '^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*',
                'optional' : true                
            },
            'nitstore' : {
                'validation' : 'alphanumeric',
                'length' : '6-15',
                'allowing' : '-_.',
                'optional' : true                
            },
            'landlinestore' : {
                'validation' : 'custom',
                'length' : '9-15',
                'regexp' : '0{0,2}([\+]?[\d]{1,3} ?)?([\(]([\d]{2,3})[)] ?)?[0-9][0-9 \-]{6,}( ?([xX]|([eE]xt[\.]?)) ?([\d]{1,5}))?',
                'error-msg' : 'Escribe un número fijo valido',
                'help' : 'Escribe un número valido  Ej. (5) 555-5555 ext 134',
                'optional' : true                
            },
            'cellstore' : {
                'validation' : 'custom',
                'length' : '9-15',
                'regexp' : '0{0,2}([\+]?[\d]{1,3} ?)?([\(]([\d]{2,3})[)] ?)?[0-9][0-9 \-]{6,}( ?([xX]|([eE]xt[\.]?)) ?([\d]{1,5}))?',
                'error-msg' : 'Escribe un número celular valido',
                'help' : 'Escribe un número valido  Ej. 300 555-5555',
                'optional' : true                
            },
            'addressstore' : {
                'validation' : 'custom',
                'length' : '10-80',
                'regexp' : '^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*',
                'optional' : true                
            }
        }
    };

    $.validate({
        modules : 'jsconf, toggleDisabled, html5',
        lang : 'es',
        showErrorDialogs : false,
        disabledFormFilter : '#newstoreform',
        onModulesLoaded : function() {
            $.setupValidation(config);
        }
    });
//});

