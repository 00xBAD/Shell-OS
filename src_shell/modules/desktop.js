var maxZIndex = 0;
function getApplicationsData() {
  $.ajax({
    url: "./static/yaml/applications.yaml",
    dataType: "text",
    async: true,
    success: function (data) {
      var apps = jsyaml.load(data);
      createDesktopIcons(apps);
      handleDesktopIconClick(apps);
    },
  });
}
function createDesktopIcons(apps) {
  $.each(apps, function (key, app) {
    var button = $("<button/>", { id: key + "-icon", class: "desktop-icon" });
    var img = $("<img/>", {
      src: "./static/svg/" + key + ".svg",
      width: "64",
      height: "64",
    });
    var p = $("<p/>", { class: "icon-text", text: app.title.toUpperCase() });
    button.append(img, p);
    $("#desktop").append(button);
  });
}
function generateUniqueId(length) {
  var result = "";
  var characters =
    "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  var charactersLength = characters.length;
  for (var i = 0; i < length; i++) {
    result += characters.charAt(Math.floor(Math.random() * charactersLength));
  }
  return result;
}
function createApplicationWindows(apps) {
  var application = $(this).attr("id").replace("-icon", "");
  var id = application + "-window";
  var title = apps[application]["title"];
  var content = apps[application]["content"];
  var instances = apps[application]["instances"];
  if (instances) {
    var instanceId = generateUniqueId(8);
    var window = createWindow(id, title, content, instanceId);
    $("body").append(window);
  } else {
    if ($("#" + id).length === 0) {
      var window = createWindow(id, title, content);
      $("body").append(window);
    }
  }
}
function handleDesktopIconClick(apps) {
  $(".desktop-icon").click(function () {
    createApplicationWindows.call(this, apps);
  });
}
function createWindow(name, title, content, instanceId) {
  var windowContainer = $("<div id=" + name + " class='window'></div>");
  if (instanceId) {
    windowContainer.attr("instance-id", instanceId);
  }
  var windowTitle = $("<div class='window-title'></div>");
  var windowTitleText = $("<p class='window-title-text'>" + title + "</p>");
  var windowCloseButton = $(
    "<img src='./static/svg/close-button.svg' class='window-close-button'></img>"
  );
  var windowContent = $("<div class='window-content'>" + content + "</div>");
  windowCloseButton.click(function () {
    windowContainer.remove();
  });
  windowTitle.append(windowTitleText);
  windowTitle.append(windowCloseButton);
  windowContainer.append(windowTitle);
  windowContainer.append(windowContent);
  if (instanceId) {
    windowContent.find("*").attr("instance-id", instanceId);
  }
  windowContainer.draggable({
    handle: ".window-title",
    containment: "body",
    start: function () {
      incrementZIndex.call(this);
    },
  });
  windowContainer.resizable({
    minWidth: 640,
    minHeight: 320,
    maxWidth: $("body").width() * 0.85,
    maxHeight: $("body").height() * 0.85,
  });
  windowContainer.click(function () {
    incrementZIndex.call(this);
  });
  function incrementZIndex() {
    if ($(this).css("z-index") != maxZIndex) {
      maxZIndex++;
      $(this).css("z-index", maxZIndex);
    }
  }
  maxZIndex++;
  windowContainer.css("z-index", maxZIndex);
  return windowContainer;
}
export default {
  getApplicationsData: getApplicationsData,
  createApplicationWindows: createApplicationWindows,
  createWindow: createWindow,
  generateUniqueId: generateUniqueId,
  handleDesktopIconClick: handleDesktopIconClick,
  createDesktopIcons: createDesktopIcons,
};
