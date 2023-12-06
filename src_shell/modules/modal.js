function createModal(config) {
  return new Promise((resolve, reject) => {
    let modalOverlay = $("<div>").addClass("modal-overlay");
    let modal = $("<div>").addClass("modal");
    $("<p>").text(config.message).appendTo(modal);
    let buttonContainer = $("<div>").addClass("button-container");
    if (config.type === "yesno") {
      $("<button>")
        .text("YES")
        .addClass("modal-yes")
        .click(function () {
          modal.remove();
          modalOverlay.remove();
          resolve(true);
        })
        .appendTo(buttonContainer);
      $("<button>")
        .text("NO")
        .addClass("modal-no")
        .click(function () {
          modal.remove();
          modalOverlay.remove();
          reject(true);
        })
        .appendTo(buttonContainer);
    } else if (config.type === "input") {
      let input = $("<input>").appendTo(modal);
      $("<button>")
        .text("OK")
        .addClass("modal-yes")
        .click(function () {
          modal.remove();
          modalOverlay.remove();
          resolve(input.val());
        })
        .appendTo(buttonContainer);
      $("<button>")
        .text("CANCEL")
        .addClass("modal-no")
        .click(function () {
          modal.remove();
          modalOverlay.remove();
          reject(true);
        })
        .appendTo(buttonContainer);
    }
    modal.append(buttonContainer);
    $("body").append(modal);
    $("body").append(modalOverlay);
  });
}
function promptUser(message, type) {
  var config = { message: message, type: type };
  return createModal(config);
}
export default { createModal: createModal, promptUser: promptUser };
