import desktop from "./modules/desktop.js";
import settings from "./modules/settings.js";
import terminal from "./modules/terminal.js";
import fileManager from "./modules/fileManager.js";
import editor from "./modules/editor.js";
import contextMenu from "./modules/contextMenu.js";
import manuals from "./modules/manuals.js";
import phpInfo from "./modules/phpInfo.js";

$(document).ready(function () {
  settings.getSettings();
  settings.setupEventHandlers();
  desktop.getApplicationsData();
  desktop.createDesktopIcons();
  desktop.handleDesktopIconClick();
  terminal.setupEventHandlers();
  fileManager.setupEventHandlers();
  editor.setupEventHandlers();
  contextMenu.setupContextMenu();
  manuals.setupEventHandlers();
  phpInfo.setupEventHandlers();
});
