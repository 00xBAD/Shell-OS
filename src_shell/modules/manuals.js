import desktop from "./desktop.js";
function setupEventHandlers() {
  $(document).on("click", "#manuals-icon", function (e) {
    e.preventDefault();
    $.ajax({
      url: "./api/fileManager.php",
      type: "POST",
      data: { fetchFiles: "../static/manuals" },
      success: function (data) {
        let manuals = JSON.parse(data);
        $("#manuals-list").html("");
        for (let manual of manuals) {
          if (manual.indexOf(".md") > -1) {
            $("#manuals-list").append(createManualIcon(manual));
          }
        }
      },
      error: function (xhr, status, error) {
        console.log(xhr.responseText);
      },
    });
  });
  $(document).on("click", ".manual-icon-container", function (e) {
    e.preventDefault();
    let manual = $(this).find("p").text();
    $.ajax({
      url: "./api/fileManager.php",
      type: "POST",
      data: { openFile: `../static/manuals/${manual}` },
      success: function (data) {
        data = marked.parse(data);
        data = data.replace(/<a href="#/g, "<p ");
        data = data.replace(/<a href="http/g, '<a target="_blank" href="http');
        data = data.replace(/<img/g, "<p ");
        data = `<div class="manual-viewport">${data}</div>`;
        let manualViewer = desktop.createWindow(
          "manual-viewer",
          manual,
          data,
          false
        );
        $("body").append(manualViewer);
      },
      error: function (xhr, status, error) {
        console.log(xhr.responseText);
      },
    });
  });
}
function createManualIcon(manual) {
  let manualIcon = document.createElement("div");
  manualIcon.classList.add("manual-icon-container");
  manualIcon.setAttribute("data-name", manual);
  manualIcon.innerHTML = `
        <img src="../static/manuals/${manual.replace(
          ".md",
          ""
        )}.svg" alt="${manual.replace(".md", "")}">
        <p>${manual}</p>
    `;
  return manualIcon;
}
export default { setupEventHandlers: setupEventHandlers };
