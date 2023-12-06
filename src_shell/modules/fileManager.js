function fetchFiles(path) {
  path = decodeURIComponent(path);
  $.ajax({
    url: "./api/fileManager.php",
    type: "POST",
    data: {
      listFiles: path,
      viewMode: window.settings["file-manager-view-mode"],
    },
    success: function (data) {
      $("#file-manager-status-bar").val(decodeURIComponent(path));
      $("#file-manager-status-bar").attr("data-path", path);
      $("#file-manager").html(data);
    },
  });
}
function setupEventHandlers() {
  $(document).on("click", "#file-manager-icon", function (e) {
    e.preventDefault();
    if (window.settings["file-manager-view-mode"] == "grid") {
      $("#grid-view-button").trigger("click");
    } else {
      $("#list-view-button").trigger("click");
    }
    fetchFiles(window.settings["file-manager-initial-path"]);
  });
  $(document).on("click", ".dir", function (e) {
    e.preventDefault();
    var path = $(this).attr("data-path");
    fetchFiles(path);
  });
  $(document).on("keypress", "#file-manager-status-bar", function (e) {
    if (e.which == 13) {
      var path = $(this).val();
      fetchFiles(path);
    }
  });
  $(document).on("click", "#grid-view-button", function (e) {
    e.preventDefault();
    window.settings["file-manager-view-mode"] = "grid";
    $("#file-manager").removeClass("flex-list");
    $("#file-manager").addClass("flex-grid");
    $("#grid-view-button").addClass("active");
    $("#list-view-button").removeClass("active");
    fetchFiles($("#file-manager-status-bar").attr("data-path"));
  });
  $(document).on("click", "#list-view-button", function (e) {
    e.preventDefault();
    window.settings["file-manager-view-mode"] = "list";
    $("#file-manager").removeClass("flex-grid");
    $("#file-manager").addClass("flex-list");
    $("#list-view-button").addClass("active");
    $("#grid-view-button").removeClass("active");
    fetchFiles($("#file-manager-status-bar").attr("data-path"));
  });
}
export default {
  setupEventHandlers: setupEventHandlers,
  fetchFiles: fetchFiles,
};
