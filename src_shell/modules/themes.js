import settings from "./settings.js";
function setTheme() {
  $(document).ready(function () {
    var theme = window.settings["theme-os"];
    console.log(theme);
    $("html").attr("class", "theme-" + theme);
  });
}
export default { setTheme: setTheme };
