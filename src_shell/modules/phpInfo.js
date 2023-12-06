function setupEventHandlers() {
  $(document).on("click", "#phpinfo-icon", function (e) {
    e.preventDefault();
    $.ajax({
      url: "./api/phpInfo.php",
      type: "POST",
      success: function (data) {
        data = data.replace(/<style([\s\S]*?)<\/style>/gi, "");
        data = data.replace(/<title([\s\S]*?)<\/title>/gi, "");
        data = data.replace(/<meta([\s\S]*?)>/gi, "");
        $("#phpinfo-viewport").html(data);
      },
    });
  });
}
export default { setupEventHandlers: setupEventHandlers };
