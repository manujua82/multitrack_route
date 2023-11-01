import { Controller } from "stimulus";
import $ from "jquery";

export default class extends Controller {
  connect() {
    let dataJson = {};
    let rolesDefault = [];
    $.getJSON("/assets/permissions.json", function (data) {
      dataJson = data;
      const rolesUser = $("#user_rolesUser").val();
      rolesDefault = rolesUser.split(",");
      activeCheckbox(
        $("#user_roleGroup").val(),
        rolesDefault.length > 1 ? rolesDefault : null
      );
      populateRolesSelectInput();
    });
    $("#user_roleGroup").on("change", function () {
      const val = $(this).val();
      activeCheckbox(val);
      populateRolesSelectInput();
    });
    $(".form-check-input").on("change", function () {
      populateRolesSelectInput();
    });

    function activeCheckbox(val, userPermissions = null) {
      const permissions = dataJson.permissions[val];
      const roleGroup = $("#user_roleGroup").val();
      if (Object.keys(permissions).length > 0) {
        for (const values in permissions) {
          const id = formatPermissionId(values);
          if (userPermissions) {
            setActiveInactiveCheck(
              id,
              roleGroup == "admin"
                ? true
                : userPermissions.includes(permissions[values].rol),
              permissions[values].active
            );
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

    function populateRolesSelectInput() {
      const roles = [];
      const val = $("#user_roleGroup").val();
      const permissions = dataJson.permissions[val];
      if (Object.keys(permissions).length > 0) {
        for (const values in permissions) {
          const id = formatPermissionId(values);
          if ($("#" + id).is(":checked")) {
            roles.push(permissions[values].rol);
          }
        }
      }
      $("#user_rolesUser").val(roles.toString());
    }

    function formatPermissionId(text) {
      const capitalize = text.charAt(0).toUpperCase() + text.slice(1);
      const result = "user_roleAllow" + capitalize;
      return result;
    }
  }
}
