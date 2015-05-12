$(document).ready(function(){
    ajax.confirmDelete();
    form.fieldsetForm();
});


ajax = {
    confirmDelete: function() {
        $('.delete-entry').click(function(e) {
            e.preventDefault();
            var r = confirm("Are you sure?");
            if (r == true) {
                ajax.deleteElement($(this).attr('data-id'), $(this).attr('data-type'));
            } else {
                // do nothing
                return;
            } 

        });
    },

    deleteElement: function(id, type) {
        var url = '';
        if (type=='element') {
            url = '/admin/form/delete-element/'+id;
        } else if (type=='fieldset') {
            url = '/admin/form/delete-fieldset/'+id;
        }

        $.ajax({
            'url' : url,
            'type' : 'get',
            'data' : {},
            'success' : function( data ){
                data = jQuery.parseJSON(data);
                if (data.status == 'success') {
                     location.reload();
                }
            }
        });
    }
}

form = {
    fieldsetForm: function() {
        $('.input-class').on('change', function() {
            var selected = $("select.input-class option:selected").text();
            if (selected=='Checkbox' || selected=='Radio') {
                $('.value-options-container').show();
            } else {
                $('.value-options-container').hide();
            }
        });

        $('#create-fieldset').submit(function(e) {
            /*e.preventDefault();
            if ($('.value-options-container').is(":visible")) {
                var new_val = $(this).find('textarea[name=value_options]').val();
                if (new_val!='') {
                    $(this).find('.input-class').val(arr_val.serializeArray());
                    //alert($(this).find('.input-class').val());
                    alert(JSON.stringify(arr_val));
                }
            }*/
        });
    }
}