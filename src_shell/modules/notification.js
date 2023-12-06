function createNotification(message, type) {
  let notification = document.createElement("div");
  notification.classList.add("notification");
  notification.classList.add(type);
  notification.classList.add("fadeIn");
  notification.innerHTML = message;
  document.body.appendChild(notification);
  setTimeout(function () {
    notification.classList.add("fadeOut");
    setTimeout(function () {
      notification.remove();
    }, 500);
  }, 3e3);
}
export default { createNotification: createNotification };
