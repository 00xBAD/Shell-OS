import modal from "./modal.js";
import notification from "./notification.js";
if (typeof window.settings === "undefined" || window.settings === null) {
  window.settings = {};
}
function applyTheme() {
  var theme = window.settings["theme-os"];
  $("html").removeClass().addClass(theme);
}
function getSettings() {
  $.ajax({
    type: "POST",
    url: "./api/settings.php",
    data: { getSettings: true },
    success: function (response) {
      window.settings = JSON.parse(response);
      $.each(window.settings, function (key, value) {
        $("#" + key).val(value);
      });
      applyTheme();
    },
  });
}
function setupEventHandlers() {
  $(document).on("click", "#settings-icon", function () {
    getSettings();
  });
  $(document).on("click", "#reset-settings-button", function () {
    $.ajax({
      type: "POST",
      url: "./api/settings.php",
      data: { resetSettings: true },
      success: function (response) {
        modal
          .promptUser("Settings have been reset. Reload the page?", "yesno")
          .then((response) => {
            if (response) {
              location.reload();
            }
          })
          .catch((error) => {
            notification.createNotification(
              "Settings have been reset",
              "success"
            );
          });
      },
      error: function (response) {
        notification.createNotification("Settings could not be reset", "error");
      },
    });
  });
  $(document).on("click", "#save-settings-button", function () {
    $("#settings")
      .find("input, select, checkbox, radio")
      .each(function () {
        window.settings[$(this).attr("id")] = $(this).val();
      });
    $.ajax({
      type: "POST",
      url: "./api/settings.php",
      data: { saveSettings: JSON.stringify(window.settings) },
      success: function (response) {
        notification.createNotification("Settings have been saved", "success");
        getSettings();
      },
      error: function (response) {
        notification.createNotification("Settings could not be saved", "error");
      },
    });
    $("#settings-window").remove();
  });
}
export default {
  getSettings: getSettings,
  setupEventHandlers: setupEventHandlers,
};
