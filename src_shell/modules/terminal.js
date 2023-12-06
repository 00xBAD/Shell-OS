function setupEventHandlers() {
  $(document).on("keypress", "#terminal-input", function (e) {
    if (e.which == 13) {
      var instanceId = $(this).attr("instance-id");
      executeCommand(instanceId);
    }
  });
}
function executeCommand(instanceId) {
  var cmd = $('#terminal-input[instance-id="' + instanceId + '"]').val();
  var mode = window.settings["terminal-execution-mode"];
  $.ajax({
    url: "./api/terminal.php",
    type: "POST",
    data: { cmd: cmd, mode: mode },
    success: function (response) {
      $('#terminal-viewport[instance-id="' + instanceId + '"]').html(response);
      $('#terminal-input[instance-id="' + instanceId + '"]').val("");
    },
  });
}
export default {
  executeCommand: executeCommand,
  setupEventHandlers: setupEventHandlers,
};
