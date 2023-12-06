<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST)) {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(["message" => "Error: POST data is empty"]);
        exit();
    }
    if (isset($_POST['fetchFiles'])) {
        $path = $_POST['fetchFiles'];
        if (is_dir($path)) {
            $files = fetchFiles($path);
            echo json_encode($files);
        }
    }
    if (isset($_POST['listFiles'])) {
        $path = $_POST['listFiles'];
        $viewMode = $_POST['viewMode'];
        if (is_dir($path)) {
            listFiles($path, $viewMode);
        }
    }
    if (isset($_POST['openFile'])) {
        $path = $_POST['openFile'];
        if (file_exists($path)) {
            echo file_get_contents($path);
        }
    }
    if (isset($_POST['renameFile']) && isset($_POST['newName'])) {
        $oldPath = $_POST['renameFile'];
        $newPath = $_POST['newName'];
        if (file_exists($oldPath)) {
            rename($oldPath, $newPath);
        }
    }
    if (isset($_POST['deleteFile'])) {
        $path = $_POST['deleteFile'];
        if (file_exists($path)) {
            unlink($path);
        }
    }
    if (isset($_POST['downloadFile'])) {
        $path = $_POST['downloadFile'];
        if (file_exists($path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($path) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($path));
            readfile($path);
            exit;
        }
    }
    if (isset($_POST['renameDir']) && isset($_POST['newName'])) {
        $oldPath = $_POST['renameDir'];
        $newPath = $_POST['newName'];
        if (is_dir($oldPath)) {
            rename($oldPath, $newPath);
        }
    }
    if (isset($_POST['deleteDir'])) {
        $path = $_POST['deleteDir'];
        if (is_dir($path)) {
            rmdir($path);
        }
    }
    if (isset($_POST['newFile'])) {
        $path = $_POST['newFile'];
        file_put_contents($path, '');
    }
    if (isset($_POST['newDir'])) {
        $path = $_POST['newDir'];
        mkdir($path, 0777, true);
    }
    if (isset($_POST['saveFile']) && isset($_POST['content'])) {
        $path = $_POST['saveFile'];
        $content = $_POST['content'];
        file_put_contents($path, $content);
    }
    if (isset($_POST['uploadFile']) && isset($_POST['fileData'])) {
        $uploadPath = $_POST['uploadFile'];
        $fileData = base64_decode($_POST['fileData']);
        $uploadDir = dirname($uploadPath);
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $result = file_put_contents($uploadPath, $fileData);
        exit();
    }
}
function listFiles($path, $viewMode)
{
    $dirContent = scandir($path);
    $files = [];
    $folders = [];
    foreach ($dirContent as $item) {
        if (is_dir($path . '/' . $item)) {
            $folders[] = $item;
        } else {
            $files[] = $item;
        }
    }
    sort($folders);
    sort($files);
    $sortedFiles = array_merge($folders, $files);
    if ($viewMode == "list") {
        echo "<div class='table' id='file-manager-list'>";
        echo "<div class='row' id='file-manager-list-header-row'>";
        echo "<div class='cell list-text icon'></div>";
        echo "<div class='cell list-text name'>Name</div>";
        echo "<div class='cell list-text perms'>Permissions</div>";
        echo "<div class='cell list-text owner'>Owner:Group</div>";
        echo "<div class='cell list-text size'>Size</div>";
        echo "<div class='cell list-text date'>Last Modified</div>";
        echo "</div>";
    }
    if ($path != ".") {
        $parentPath = dirname($path);
        $encodedParentPath = urlencode($parentPath);
        if ($viewMode == "list") {
            echo "<div id='file-manager-data-row' class='dir row' data-path='$encodedParentPath'>";
            echo "<div class='list-icon cell icon'><img src='static/svg/folder-generic.svg' class='list-icon'></img></div>";
            echo "<div class='list-text cell name'>..</div>";
            echo "<div class='list-text cell perms'></div>";
            echo "<div class='list-text cell owner'></div>";
            echo "<div class='list-text cell size'></div>";
            echo "<div class='list-text cell date'></div>";
            echo "</div>";
        } else if ($viewMode == "grid") {
            echo "<div id='file-manager-icon-wrapper' class='dir grid-icon-wrapper' data-path='$encodedParentPath'>";
            echo "<img src='static/svg/folder-generic.svg' class='grid-icon'></img>";
            echo "<div class='grid-text'>..</div>";
            echo "</div>";
        }
    }
    foreach ($sortedFiles as $file) {
        if ($file == '.' || $file == '..') continue;
        $fullPath = $path . '/' . $file;
        $encodedFullPath = urlencode($fullPath);
        $fileType = is_dir($fullPath) ? "dir" : "file";
        $icon = is_dir($fullPath) ? "folder-generic.svg" : "file-generic.svg";
        if ($viewMode == "list") {
            if ($fileType == "file") {
                $nodeSize = is_dir($fullPath) ? '' : ' (' . filesize($fullPath) . ' bytes)';
                $nodeDate = is_dir($fullPath) ? '' : date("d.m.y H:i", filemtime($fullPath));
                $nodePermissions = substr(sprintf('%o', fileperms($fullPath)), -4);
                $nodePermissions = strtr($nodePermissions, ["0" => "---", "1" => "--x", "2" => "-w-", "3" => "-wx", "4" => "r--", "5" => "r-x", "6" => "rw-", "7" => "rwx"]);
                $nodeOwner = posix_getpwuid(fileowner($fullPath))['name'];
                $nodeGroup = posix_getgrgid(filegroup($fullPath))['name'];
                $nodeOwnerGroup = $nodeOwner . ':' . $nodeGroup;
            } else if ($fileType == "dir") {
                $nodeSize = '';
                $nodeDate = is_dir($fullPath) ? date("d.m.y H:i", filemtime($fullPath)) : '';
                $nodePermissions = substr(sprintf('%o', fileperms($fullPath)), -4);
                $nodePermissions = strtr($nodePermissions, ["0" => "---", "1" => "--x", "2" => "-w-", "3" => "-wx", "4" => "r--", "5" => "r-x", "6" => "rw-", "7" => "rwx"]);
                $nodeOwner = posix_getpwuid(fileowner($fullPath))['name'];
                $nodeGroup = posix_getgrgid(filegroup($fullPath))['name'];
                $nodeOwnerGroup = $nodeOwner . ':' . $nodeGroup;
            }
            echo "<div id='file-manager-data-row' class='$fileType row' data-path='$encodedFullPath'>";
            echo "<div class='list-icon cell icon'><img src='static/svg/$icon' class='list-icon'></img></div>";
            echo "<div class='list-text cell name'>$file</div>";
            echo "<div class='list-text cell perms'>$nodePermissions</div>";
            echo "<div class='list-text cell owner'>$nodeOwnerGroup</div>";
            echo "<div class='list-text cell size'>$nodeSize</div>";
            echo "<div class='list-text cell date'>$nodeDate</div>";
            echo "</div>";
        } else if ($viewMode == "grid") {
            echo "<div id='file-manager-icon-wrapper' class='$fileType grid-icon-wrapper' data-path='$encodedFullPath'>";
            echo "<img id='file-manager-icon' src='static/svg/$icon' class='grid-icon'></img>";
            $fileTrim = strlen($file) > 16 ? substr($file, 0, 16) . '...' : $file;
            echo "<div class='grid-text'>$fileTrim</div>";
            echo "</div>";
        }
    }
    if ($viewMode == "list") {
        echo "</div>";
    }
}
function fetchFiles($path)
{
    $files = scandir($path);
    $files = array_diff($files, array('.', '..'));
    $files = array_values($files);
    return $files;
}
