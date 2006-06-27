<?
#################################################################################################
#
#  project              : Logix Classifieds
#  filename             : library_webmail.php
#  last modified by     :
#  e-mail               : support@phplogix.com
#  purpose              : WebMail Library
#
#################################################################################################
if(!strpos($_SERVER['PHP_SELF'],'library_webmail.php') === false)
{
  die("YOU MAY NOT ACCESS THIS FILE DIRECTLY");
}
class phpmailer
{
    var $From               = "root@localhost";
    var $FromName          = "Root User";
    var $Sender            = "";
    var $Subject           = "";
    var $Body               = "";
    var $WordWrap          = 0;
    var $UseMSMailHeaders = false;
    var $to              = "";
    var $cc              = "";
    var $bcc             = "";
    var $ReplyTo         = "";
    var $attachment      = array();

    function AddCustomHeader($name = "") {
        return true;
    }

    function AddAddress($address, $name = "") {
        $this->to = trim($address);
        $this->toname = trim($name);

    }

    function AddCC($address, $name = "") {
        $this->cc = trim($address);
    }

    function AddBCC($address, $name = "") {
        $this->bcc = trim($address);
    }

    function Send() {
    global $prefix,$timestamp,$ip;

        if(count($this->to) < 1)
        {
            $this->error_handler("You must provide at least one recipient email address");
            return false;
        }

    $att1=$this->attachment[0];
    $att2=$this->attachment[1];
    $att3=$this->attachment[2];

    $fromquery=mysql_query("SELECT id FROM ".$prefix."userdata WHERE email='$this->From'");
    $from=mysql_fetch_array($fromquery);
    $toquery=mysql_query("SELECT id FROM ".$prefix."userdata WHERE email='$this->to'");
    $to=mysql_fetch_array($toquery);

    $result=mysql_query("INSERT INTO ".$prefix."webmail (fromid,fromname,fromemail,toid,toname,toemail,timestamp,ip,subject,text,attachment1,attachment2,attachment3)
                    VALUES ('$from[id]','$this->FromName','$this->From','$to[id]','$this->toname','$this->to','$timestamp','$ip','$this->Subject','$this->Body','$att1','$att2','$att3')");
    if(!result)
    {
            return false;
        }

        return true;
    }


    /////////////////////////////////////////////////
    // ATTACHMENT METHODS
    /////////////////////////////////////////////////

    function AddAttachment($path, $name = "", $encoding = "", $type = "") {
    global $timestamp,$bazar_dir,$webmail_path;
        if(!@is_file($path))
        {
            $this->error_handler(sprintf("Could not access [%s] file", $path));
            return false;
        }

        $filename = basename($path);
        if($name == "")
            $name = $filename;

        // Append to $attachment array
    $temp=explode(".","$name");
    $ext=".".$temp[(count($temp)-1)];

    if ($ext!=".txt" && $ext!=".doc" && $ext!=".pdf" &&
        $ext!=".gif" && $ext!=".jpg" && $ext!=".png" &&
        $ext!=".zip" && $ext!=".gz") $ext=".txt";

        $cur = count($this->attachment);
    $picturename=$timestamp+$cur.$ext;
        $this->attachment[$cur] = $picturename;
        copy("$path","$bazar_dir/$webmail_path/$picturename");
        if (!is_file("$bazar_dir/$webmail_path/$picturename")) {
       died ("$bazar_dir/$webmail_path is not a dir or not writeable!");
    }

        return true;
    }

}
// End of class
?>