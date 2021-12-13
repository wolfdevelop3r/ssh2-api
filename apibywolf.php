<?php
//API Script Created by DevilsExploits

// http://localhost/SSH2.php?key=testkey&cmd=ping

function error_handler($prefix, $msg){
    return json_encode(["Error"=> $prefix, "Info"=> $msg, "Credits"=> "API By WOLF"]);
}

function url_exist($url){
    $c=curl_init();
    curl_setopt($c,CURLOPT_URL,$url);
    curl_setopt($c,CURLOPT_HEADER,1);//get the header
    curl_setopt($c,CURLOPT_NOBODY,1);//and *only* get the header
    curl_setopt($c,CURLOPT_RETURNTRANSFER,1);//get the response as a string from curl_exec(), rather than echoing it
    curl_setopt($c,CURLOPT_FRESH_CONNECT,1);//don't use a cached version of the url
    if(!curl_exec($c)){
        return false;
    }else{
        return true;
    }

    //$httpcode=curl_getinfo($c,CURLINFO_HTTP_CODE);
    //return ($httpcode<400);
}

header('Content-Type: application/json');
ignore_user_abort(true);
set_time_limit(0);

if(array_keys($_GET) !== ["key", "cmd"])
{
    die(error_handler("Yes", "Missing Parameters!"));
}

// Edit Here
$server_ip = "8.8.8.8";
$server_user = "root";
$server_pass = "";
// Stop Editing

$key = $_GET['key'];
$host = $_GET['host'];
$port = intval($_GET['port']);
$time = intval($_GET['time']);
$cmd = $_GET['cmd'];

$cmds = array("ping"); // Edit cmds Here
$keys = array("fuckingawesome");

if (!in_array($key, $keys))
{
    die(error_handler("Yes", 'Wrong Key!'));
}

if(in_array($cmd, $cmds))
{
    if ($cmd == "http") { $command = "screen -dm perl /root/action.pl"; } 
}
else
{
    die(error_handler("Yes", "Command is unavailable"));
}

if (!function_exists("ssh2_connect")) die(error_handler("Yes" ,"SSH2 does not exist on you're server"));
if(!($con = ssh2_connect($server_ip, 22))){
  die(error_handler("Yes", " Connection Issue"));
} else {
 
    if(!ssh2_auth_password($con, $server_user, $server_pass)) {
        die(error_handler("Yes", " Login failed, one or more of you're server credentials are incorect."));
    } else { 
        if (!($stream = ssh2_exec($con, $command ))) {
            die(error_handler("Yes", " Your server is missing some dependencies\nPossible dependencies missing: SSH2"));
        } else {   
            stream_set_blocking($stream, false);
            $data = "";
            while ($buf = fread($stream,4096)) {
                $data .= $buf;
            }

            echo error_handler("No", "Action sent");

            fclose($stream);
        }
    }
}