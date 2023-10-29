import { Controller } from "stimulus";
import $ from "jquery";

export default class extends Controller {
  connect() {
    let dataJson = {};
    let rolesDefault = [];
    $.getJSON("/assets/permissions.json", function (data) {
      dataJson = data;
      const rolesuser = $("#user_rols").val();
      rolesDefault = rolesuser.split(",");
      activeCheckbox(
        $("#user_rolegroup").val(),
        rolesDefault.length > 1 ? rolesDefault : null
      );
      getRoles();
    });
    $("#user_rolegroup").on("change", function () {
      const val = $(this).val();
      activeCheckbox(val);
      getRoles();
    });
    $(".form-check-input").on("change", function () {
      getRoles();
    });

    function activeCheckbox(val, defVal = null) {
      const permissions = dataJson.permissions[val];
      if (Object.keys(permissions).length > 0) {
        for (const values in permissions) {
          const id = formatId(values);
          if (defVal) {
            if (defVal.includes(permissions[values].rol)) {
              setActiveInactiveCheck(id, true, permissions[values].active);
            } else if (permissions[values].active) {
              setActiveInactiveCheck(id, false, permissions[values].active);
            } else {
              setActiveInactiveCheck(
                id,
                permissions[values].default,
                permissions[values].active
              );
            }
          } else {
            setActiveInactiveCheck(
              id,
              permissions[values].default,
              permissions[values].active
            );
          }
        }
      }
    }

    function setActiveInactiveCheck(id, initVal, active) {
      $("#" + id).prop("checked", initVal);
      if (active) {
        $("#" + id).removeAttr("disabled");
        $("label[for='" + id + "']").css({ color: "#525b75" });
      } else {
        $("#" + id).attr("disabled", true);
        $("label[for='" + id + "']").css({ color: "#cbd0dd" });
      }
    }

    function getRoles() {
      const roles = [];
      const val = $("#user_rolegroup").val();
      const permissions = dataJson.permissions[val];
      if (Object.keys(permissions).length > 0) {
        for (const values in permissions) {
          const id = formatId(values);
          if ($("#" + id).is(":checked")) {
            roles.push(permissions[values].rol);
          }
        }
      }
      $("#user_rols").val(roles.toString());
    }

    function formatId(text) {
      const capitalize = text.charAt(0).toUpperCase() + text.slice(1);
      const result = "user_roleAllow" + capitalize;
      return result;
    }
  }
}
