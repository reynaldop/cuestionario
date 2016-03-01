/**
 * Created by ReynaldoPG on 30/01/2016.
 */

$(document).ready(function () {
    var getUrlSite = null;
    getUrlSite = $.ajax({
        url: '../wp-content/plugins/cuestionario/functions/general_functions.php',
        data: {"url": true},
        type: 'GET',
        async: false,
        contentType: "application/json",
        dataType: 'json',
        success: function (data) {
        }
    });
    var urlSite = JSON.parse(getUrlSite.responseText).home;
    var field = JSON.parse(getUrlSite.responseText).field;
    //Actions
    if ($('#Table_1')) {
        $('#Table_1').DataTable({
            ordering: false
        });
        initial();
    } else {
        //initial();
    }

    function initial() {
        var question = null;
        var val = null;
        $("input:radio").each(function () {
            if ($(this).prop('checked')) {
                question = $(this).prop('name').split('_r')[0];
                val = $(this).val();
                checkRadio(question, val);
            }
        });
    }

    function fieldContent() {
        $( "div" ).remove( "#required" );
        var fields = new Object();
        var rdio = new Object();
        var rinput = new Object();
        var rtextarea = new Object();
        var resultrt = new Object();
        var validate = false;
        var email = $('#'+field+'_correo').val();

        $('#general-form-1 input').each(
            function (index) {
                var input = $(this);
                if (input.attr('type') != 'radio' && input.attr('type') != 'submit') {
                    if(!input.val() && input.attr('id') != field+'_id_datos'){
                        //console.log(input.attr('id'));
                        $('label[for='+input.attr('id')+']').before( "<div id='required' style='color: red;'>* Requerido</div>" );;
                        validate = true;
                    }
                    rinput[input.attr('id')] = input.val();
                }
            }
        );
        email = isEmail(email);
        if(!email){
            validate = true;
            $('label[for='+field+'_correo]').before( "<div id='required' style='color: red;'>* Correo Invalido</div>" );;
        }
        //throw new Error('Validar campos requeridos');
        if(validate){
            throw new Error('Validar campos requeridos');
        }

        $('#general-form-1 textarea').each(
            function (index) {
                var input = $(this);
                if (input.val()) {
                    rtextarea[input.attr('id')] = input.val();
                }
            }
        );

        $("input:radio").each(function () {
            if ($(this).prop('checked')) {
                rdio [$(this).attr('id').split('_r')[0]] = $(this).val();
            }
        });

        for (var key in rdio) {
            if (rtextarea[key] != undefined) {
                resultrt[key] = rdio[key] + ' | ' + rtextarea[key];
            } else {
                resultrt[key] = rdio[key];
            }
        }
        resultrt = $.extend({}, rtextarea, resultrt);
        fields = $.extend({}, rinput, resultrt);

        //console.log(rinput);
        return fields;
    }

    function isEmail(email){
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    function checkRadio(question, val) {
        var no = false;
        var show_questions_no = new Array('tres', 'cuatro');
        for (var i = 0; i < show_questions_no.length; i++) {
            if (1 < question.search(show_questions_no[i])) {
                no = true;
            }
        }
        if (no) {
            if (val == 'no') {
                $('#' + question).show();
            } else {
                $('#' + question).hide();
                $('textarea#' + question).text(null);
            }
        } else {
            if (question == field + '_pregunta_dieciseis') {
                $('#' + question).show();
                return true;
            }
            if (val == 'si') {
                $('#' + question).show();
            } else {
                //console.log(question);
                $('#' + question).hide();
                $('textarea#' + question).text(null);
            }
        }
    }

    $('#general-form-1 input[type="radio"]').click(function () {
        var question = $(this).prop('name').split('_r')[0];
        var val = $(this).val();
        checkRadio(question, val);
    });

    $("a").click(function (event) {
        var id = null;
        id = $(this).attr('rel').split('_')[1];
        if (id) {
            generalPdf(id);
        }
    });

    function generalPdf(id) {
        $.ajax({
            url: '../wp-content/plugins/cuestionario/functions/download_pdf.php',
            data: {"id": id},
            type: 'GET',
            beforeSend: function () {
                $("#general_download_" + id).hide();
                $("#general_download_pdf_" + id).show();
                $("#general_download_loader_" + id).show();
            },
            success: function (data) {
                if (1 < data.search('error')) {
                    alert('A ocurrido un error intente mas tarde.');
                    $("#general_download_pdf_" + id).hide();
                    $("#general_download_loader_" + id).hide();
                    $("#general_download_" + id).show();
                    throw new Error(data);
                }   
                $("#general_download_pdf_" + id).hide();
                generalWord(id);
            }
        });
    }

    function generalWord(id) {
        $.ajax({
            url: '../wp-content/plugins/cuestionario/functions/download_word.php',
            data: {"id": id},
            type: 'GET',
            beforeSend: function () {
                $("#general_download_word_" + id).show();
            },
            success: function (data) {
                $("#general_download_word_" + id).hide();
                generalZip(id);
            }
        });
    }

    function generalZip(id) {
        $.ajax({
            url: '../wp-content/plugins/cuestionario/functions/download.php',
            data: {"id": id},
            type: 'GET',
            beforeSend: function () {
            },
            success: function (data) {
                $("#general_download_loader_" + id).hide();
                $("#general_download_" + id).show();
                window.location = urlSite + data;
                 generalDeleteZip(id);
            }
        });
    }

    function generalDeleteZip(id){
        $.ajax({
            url: '../wp-content/plugins/cuestionario/functions/download.php',
            data: {
                "id": id,
                "del":true,
            },
            type: 'GET',
            success: function (data) {
                //console.log(data);
            }
        });
    }

    $('#general-save').click(function (e) {
        e.preventDefault();
        generalSave();
    });

    function generalSave() {
        var fields = fieldContent();
        //throw new Error('stop');
        var url = '../wp-content/plugins/cuestionario/functions/form.php';
        $.ajax({
            url: url,
            data: fields,
            type: "POST",
            dataType: 'json',
            beforeSend: function () {
                $("#general-save").hide();
                $("#loading").show();
            },
            success: function (data) {
                $("#loading").hide();
                $("#general-save").show();
                $(location).attr('href', urlSite + '/wp-admin/admin.php?page=cuestionario');
            }
        });
    }
});
