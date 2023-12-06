import fileManager from "./fileManager.js";
import desktop from "./desktop.js";
import modal from "./modal.js";
import notification from "./notification.js";
function setupContextMenu() {
  $.ajax({
    url: "./static/yaml/applications.yaml",
    type: "POST",
    dataType: "text",
    async: true,
    success: function (data) {
      var doc = jsyaml.load(data);
      $(document).on("contextmenu", function (e) {
        e.preventDefault();
        if (e.target.id === "file-manager") {
          var menuItems = doc["file-manager"].context["empty"];
          var elementType = "empty";
        } else {
          if ($(e.target).hasClass("file")) {
            elementType = "file";
          } else if ($(e.target).hasClass("dir")) {
            elementType = "dir";
          }
          var menuItems = doc["file-manager"].context[elementType];
        }
        if (!menuItems) {
          return;
        }
        if ($("#contextMenu").length === 0) {
          $("<div>").attr("id", "contextMenu").appendTo("body");
        }
        $("#contextMenu").empty();
        $.each(menuItems, function (i, item) {
          var menuItem = $("<li>").text(item.name).appendTo("#contextMenu");
          menuItem.on("click", function () {
            var dataAttributes = $(e.target).data();
            var currentAction = actions[item.action];
            if (currentAction) {
              currentAction(dataAttributes);
            }
          });
        });
        $("#contextMenu")
          .css({ top: e.pageY + "px", left: e.pageX + "px" })
          .show();
      });
      $(document).on("click", function (e) {
        if (!$(e.target).closest("#contextMenu").length) {
          $("#contextMenu").hide();
        }
      });
      $(document).on("click", "#contextMenu li", function () {
        $("#contextMenu").hide();
      });
    },
  });
}
const makeAjaxCall = (url, method, data) => {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: url,
      type: method,
      data: data,
      success: resolve,
      error: reject,
    });
  });
};
const refreshFileManager = () => {
  const dir = $("#file-manager-status-bar").attr("data-path");
  fileManager.fetchFiles(dir);
};
const actionSuccess = (message) => {
  notification.createNotification(message, "success");
};
const actionError = (message) => {
  notification.createNotification(message, "error");
};
var actions = {
  openFile: function (dataAttributes) {
    var path = decodeURIComponent(dataAttributes.path);
    makeAjaxCall("./api/fileManager.php", "POST", { openFile: path }).then(
      (response) => {
        $.ajax({
          url: "./static/yaml/applications.yaml",
          dataType: "text",
          async: true,
          success: function (data) {
            var apps = jsyaml.load(data);
            var editorApp = apps["editor"];
            var instanceId = desktop.generateUniqueId(8);
            var editorWindow = desktop.createWindow(
              "editor-window",
              editorApp["title"],
              editorApp["content"],
              instanceId
            );
            $("body").append(editorWindow);
            $('#editor-text-area[instance-id="' + instanceId + '"]').val(
              response
            );
            $('#editor-status-bar[instance-id="' + instanceId + '"]').attr(
              "data-path",
              path
            );
          },
          error: function (error) {
            notification.createNotification(
              "Error loading opening file!",
              "error"
            );
          },
        });
      }
    );
  },
  renameFile: function (dataAttributes) {
    modal.promptUser("Enter the new file name.", "input").then((response) => {
      if (response) {
        var fullPath = decodeURIComponent(dataAttributes.path);
        var path = fullPath.substring(0, fullPath.lastIndexOf("/"));
        var newName = response;
        makeAjaxCall("./api/fileManager.php", "POST", {
          renameFile: fullPath,
          newName: path + "/" + newName,
        })
          .then((response) => {
            refreshFileManager();
            actionSuccess("File renamed successfully.");
          })
          .catch((error) => {
            actionError("Error renaming file.");
          });
      }
    });
  },
  deleteFile: function (dataAttributes) {
    modal
      .promptUser("Are you sure to delete this file?", "yesno")
      .then((response) => {
        if (response) {
          var path = decodeURIComponent(dataAttributes.path);
          makeAjaxCall("./api/fileManager.php", "POST", { deleteFile: path })
            .then((response) => {
              refreshFileManager();
              actionSuccess("File deleted successfully.");
            })
            .catch((error) => {
              actionError("Error deleting file.");
            });
        }
      });
  },
  downloadFile: function (dataAttributes) {
    var path = decodeURIComponent(dataAttributes.path);
    makeAjaxCall("./api/fileManager.php", "POST", { downloadFile: path }).then(
      (response) => {
        var form = $("<form>", {
          action: "./api/fileManager.php",
          method: "POST",
        });
        var input = $("<input>", {
          type: "hidden",
          name: "downloadFile",
          value: path,
        });
        form.append(input);
        $("body").append(form);
        form.submit();
        form.remove();
        notification.createNotification(
          "File downloaded successfully.",
          "success"
        );
      },
      (error) => {
        notification.createNotification("Error downloading file.", "error");
      }
    );
  },
  renameDir: function (dataAttributes) {
    modal
      .promptUser("Enter the new directory name.", "input")
      .then((response) => {
        if (response) {
          var fullPath = decodeURIComponent(dataAttributes.path);
          var path = fullPath.substring(0, fullPath.lastIndexOf("/"));
          makeAjaxCall("./api/fileManager.php", "POST", {
            renameDir: fullPath,
            newName: path + "/" + response,
          })
            .then((response) => {
              refreshFileManager();
              actionSuccess("Directory renamed successfully.");
            })
            .catch((error) => {
              actionError("Error renaming directory.");
            });
        }
      });
  },
  deleteDir: function (dataAttributes) {
    modal
      .promptUser("Are you sure to delete this Directory?", "yesno")
      .then((response) => {
        if (response) {
          var path = decodeURIComponent(dataAttributes.path);
          makeAjaxCall("./api/fileManager.php", "POST", { deleteDir: path })
            .then((response) => {
              refreshFileManager();
              actionSuccess("Directory deleted successfully.");
            })
            .catch((error) => {
              actionError("Error deleting directory.");
            });
        }
      });
  },
  newFile: function () {
    modal
      .promptUser("Enter the name of the new file.", "input")
      .then((response) => {
        if (response) {
          var name = response;
          var path = decodeURIComponent(
            $("#file-manager-status-bar").attr("data-path")
          );
          makeAjaxCall("./api/fileManager.php", "POST", {
            newFile: path + "/" + name,
          })
            .then((response) => {
              refreshFileManager();
              actionSuccess("File created successfully.");
            })
            .catch((error) => {
              actionError("Error creating file.");
            });
        }
      });
  },
  newDir: function () {
    modal
      .promptUser("Enter the name of the new directory.", "input")
      .then((response) => {
        if (response) {
          var name = response;
          var path = decodeURIComponent(
            $("#file-manager-status-bar").attr("data-path")
          );
          makeAjaxCall("./api/fileManager.php", "POST", {
            newDir: path + "/" + name,
          })
            .then((response) => {
              refreshFileManager();
              actionSuccess("Directory created successfully.");
            })
            .catch((error) => {
              actionError("Error creating directory.");
            });
        }
      });
  },
  uploadFile: function () {
    var fileInput = $("<input>", {
      type: "file",
      name: "uploadFile",
      style: "display: none",
    });
    fileInput.appendTo("body").trigger("click");
    fileInput.on("change", function () {
      var path = decodeURIComponent(
        $("#file-manager-status-bar").attr("data-path")
      );
      var file = this.files[0];
      var fileName = file.name;
      var reader = new FileReader();
      reader.onload = function (e) {
        var fileData = e.target.result;
        var uploadPath = path + "/" + fileName;
        fileData = btoa(fileData);
        makeAjaxCall("./api/fileManager.php", "POST", {
          uploadFile: uploadPath,
          fileData: fileData,
        })
          .then((response) => {
            refreshFileManager();
            actionSuccess("File uploaded successfully.");
          })
          .catch((error) => {
            actionError("Error uploading file.");
          });
      };
      reader.readAsBinaryString(file);
    });
  },
};
export default { setupContextMenu: setupContextMenu };
