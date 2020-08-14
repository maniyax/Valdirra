<?php
##############
# 24.12.2014 #
##############
function compress_output_gzip($output)
	{
	return gzencode($output, 6);
	}
 
function compress_output_deflate($output)
	{
	return gzdeflate($output, 6);
	}
$PREFER_DEFLATE = false;
$FORCE_COMPRESSION = false;
if (isset($_SERVER['HTTP_ACCEPT_ENCODING']))
{
    $AE = $_SERVER['HTTP_ACCEPT_ENCODING'];
}
elseif(isset($_SERVER['HTTP_ACCEPT_ENCODING']))
{
    $AE = $_SERVER['HTTP_TE'];
}
else $AE = '';
$support_gzip = mb_strpos($AE, "gzip") !== false or $FORCE_COMPRESSION;
$support_deflate = mb_strpos($AE, "deflate") !== false or $FORCE_COMPRESSION;
do
{
    if ($support_gzip)
    {
        if (!$support_deflate)
        {
            break;
        }
        else
        {
            $support_deflate = $PREFER_DEFLATE;
        }
    }
    if ($support_deflate)
    {
        header("Content-Encoding: deflate");
        ob_start("compress_output_deflate");
    }
} while (0);
if ($support_gzip)
{
    header("Content-Encoding: gzip");
    ob_start("compress_output_gzip");
}
else
{
    ob_start();
}
?>
