import modal from "./modal.js";
import notification from "./notification.js";
function setupEventHandlers() {
  $(document).on("click", "#editor-save-button", function (e) {
    e.preventDefault();
    var instanceId = $(this).attr("instance-id");
    var path = $('#editor-status-bar[instance-id="' + instanceId + '"]').attr(
      "data-path"
    );
    var content = $(
      '#editor-text-area[instance-id="' + instanceId + '"]'
    ).val();
    if (!path) {
      modal
        .promptUser(
          "Where do you want to save this file? ex. /tmp/tempfile.txt",
          "input"
        )
        .then(function (path) {
          $.ajax({
            url: "./api/fileManager.php",
            type: "POST",
            data: { saveFile: path, content: content },
            success: function (data) {
              notification.createNotification(
                "File saved successfully",
                "success"
              );
            },
            error: function (data) {
              notification.createNotification("Error saving file", "error");
            },
          });
        });
    }
  });
  $(document).on("click", "#editor-local-load-button", function (e) {
    var instanceId = $(this).attr("instance-id");
    var fileInput = $('<input type="file">');
    fileInput.on("change", function (e) {
      var file = e.target.files[0];
      var reader = new FileReader();
      reader.onload = function (e) {
        var contents = e.target.result;
        $('#editor-text-area[instance-id="' + instanceId + '"]').val(contents);
        fileInput.remove();
      };
      reader.readAsText(file);
    });
    fileInput.click();
  });
  $(document).on("click", "#editor-local-save-button", function (e) {
    e.preventDefault();
    var instanceId = $(this).attr("instance-id");
    var content = $(
      '#editor-text-area[instance-id="' + instanceId + '"]'
    ).val();
    var blob = new Blob([content], { type: "text/plain;charset=utf-8" });
    var url = URL.createObjectURL(blob);
    var link = document.createElement("a");
    link.href = url;
    modal
      .promptUser("Type the filename (e.g. loot.txt)", "input")
      .then(function (path) {
        link.download = path;
        link.click();
      });
  });
}
export default { setupEventHandlers: setupEventHandlers };
