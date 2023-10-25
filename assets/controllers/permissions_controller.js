import { Controller } from 'stimulus';
import $ from 'jquery';

export default class extends Controller { 

    connect() { 
        let dataJson = {}
        $.getJSON('/assets/permissions.json', function (data) {
            dataJson = data
            activeCheckbox($('#user_rolegroup').val());
         })
        $('#user_rolegroup').on('change', function () {
            const val = $(this).val();
            activeCheckbox(val);
        })

        function activeCheckbox(val) {
            const permissions = dataJson.permissions[val];
            if (Object.keys(permissions).length > 0) { 
                for (const values in permissions) { 
                    const id = formatId(values);
                    console.log(id, permissions[values].default)
                    $("#" + id).prop("checked", permissions[values].default);
                    if (permissions[values].active) {
                        $("#" + id).removeAttr("disabled");
                        $("label[for='" + id + "']").css({ 'color': '#525b75' });
                    } else {
                        $("#" + id).attr("disabled", true);
                        $("label[for='" + id + "']").css({ 'color': '#cbd0dd' });
                    }
                }
            }
        }

        function formatId(text) {
            const capitalize = text.charAt(0).toUpperCase() + text.slice(1);
            const result = 'user_roleAllow' + capitalize;
            return result;
        }
    }
}