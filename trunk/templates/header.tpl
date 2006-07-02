<!-- $Id$ -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<!-- $Id$ -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset={$charset}" />
{if $favicon}
<link rel="shortcut icon" href="{$favicon}" />
{/if}
<link rel="stylesheet" type="text/css" href="{$stylesheet}" />
<title>{$main_title}{$title_separator}{$secondary_title}</title>
<meta name="robots" content="index, follow">
<meta name="revisit-after" content="20 days">
</head>
<body>
 <div align="center">
  <table border="0" cellpadding="0" cellspacing="0" width="738" height="67">
   <tr>
    <td width="504">
      <img src="images/pb_banner01.jpg" border=1>
    </td>
    <td width="230">
      <img src="images/pb_logo01.jpg" border=1>
    </td>
   </tr>
  </table>
{if $alertmessage neq ""}
<!-- Display errors messages -->
{/if}
{if $message neq ""}
<!-- Display System Messages -->
{/if}
 </div>
