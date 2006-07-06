<!-- $Id$ -->
 <table align="center" border="0" cellspacing="0"  cellpadding="1"  width="{$table_width_side}"  height="40">
   <tr>
    <td class="class1">
      <table align="center" border="0" cellspacing="0"  cellpadding="3"  width="100%"  height="40">
       <tr>
        <td class="class2">
        <div class="sideheader">{$status_header}</div>
        <div class="sideleft">{$status_msg.status}</div>
        </td>
       </tr>
      </table>
    </td>
   </tr>
 </table>
{include file="spacer.tpl"}
<!-- # Login Window
#################################################################################################   -->
 <table align="center" border="0" cellspacing="0" cellpadding="1" width="{$table_width_side}" height="175">
   <tr>
    <td class="class1">
      <table align="center" border="0" cellspacing="0" cellpadding="3" width="100%" height="175">
       <tr>
        <td class="class2">
{if $logged_in eq "false"}
    {if $login eq "lostpass"}
              <form method="post"  action="lostpass.php"  name="" >
              <div class="sideheader">{$lostpw_header}</div>
              <table width="100%">
               <tr><td>
                <div class="sideleft">
                {$lostpw_email}<br><input type="text" name="email" size="{$field_size}" maxlength="50" ><br><br>
                </div>
               </td></tr>
               <tr><td>
                <input type="submit" value="{$lostpw_button}" name="submit">
               </td></tr>
              </table>
              </form>
    {else}
              <form method="post" action="login.php" name="">
              <div class="sideheader">{$login_header}</div>
              <table width="100%">
               <tr><td colspan="2">
                <div class="sideleft">
                {$login_username}<br><input type="text" name="username" size="{$field_size}" maxlength="22"><br>
                </div>
               </td></tr>
               <tr><td colspan="2">
                <div class="sideleft">
                {$login_password}<br><input type="password" name="password" size="{$field_size}" maxlength="22"><br><br>
                </div>
               </td></tr>
               <tr><td valign="top">
                <input type="hidden" name="loginlink" value="">
                <input type="submit" value="Login"  name="submit">
               </td>
                {$langstr}
              </tr>
              </table>
              </form>

              <div class="sideleft">
              <a href="main.php?login=lostpass" onmouseover="window.status='{$logi_link1desc}'; return true;"  onmouseout="window.status=''; return true;">{$logi_link1}</a><br>
              <a href="register.php"   target="{$target}"  onmouseover="window.status='{$logi_link2desc}'; return true;" onmouseout="window.status=''; return true;">{$logi_link2}</a>
              </div>
    {/if}

{else}
              <form method="post" action="logout.php" name="">
              <div class="sideheader">{$login_header}</div>
              <table width="100%">
               <tr><td colspan="2">
                <div class="sideleft">
                {$login_username}<br><input type="text"  name="username" size="{$field_size}" maxlength="22" value="{$session_username}" readonly><br>
                </div>
               </td></tr>
               <tr><td colspan="2">
                <div class="sideleft">
                <br><input type="hidden" name="memberid" value="{$membernumber}"><br><br>
                </div>
               </td></tr>
               <tr><td valign="top">
                <input type="submit" value="Logout" name="submit">
               </td>
               {$langstr}
              </tr>
              </table>
              </form>

{if $show_useronline_detail}
               <div class="smallright"><a href="useronline.php">{$user} {$uostr}</a></div>
{else}
               <div class="smallright">{$user} {$uostr}</div>
{/if}
{/if}
{if $is_moderator}
<div class="smallright"><a href="admin/admin.php">Admin-Panel</a></div>
{else}
    {if $mail_new}
        echo "<div class="smallright"><a href="webmail.php"><img src="images/icons/new.gif"  hspace="4" border="0" alt="{$mail_new}" onmouseover="window.status='{$mail_new}'; return true;"  onmouseout="window.status=''; return true;">{$webmail_head}</a></div>
    {else}
        <br>
    {/if}
 {/if}
        </td>
       </tr>
      </table>
    </td>
   </tr>
 </table>
{if $show_advert1}
{include file="spacer.tpl"}

 <table align="center" border="0" cellspacing="0" cellpadding="1" width="{$table_width_side}">
   <tr>
    <td class="class1">
      <table align="center" border="0" cellspacing="0" cellpadding="3" width="100%">
       <tr>
        <td class="class2">


<div class="sideheader">{$advert1.title}</div>
<div class="sidetext">
{$advert1.body}
</div>
        </td>
       </tr>
      </table>
    </td>
   </tr>
 </table>

{/if}
{if $advert2}
{include file="spacer.tpl"}
  <table align="center" border="0" cellspacing="0" cellpadding="1" width="{$table_width_side}">
    <tr>
     <td class="class1">
       <table align="center" border="0" cellspacing="0" cellpadding="3" width="100%">
        <tr>
         <td class="class2">
  {if $potd}
    <div class="sideheader">Ad of the Day</div>
{if $_picture1}
<div class="smallcenter"><a href="classified.php?catid={$category}&subcatid={$subcategory}&adid={$adid}" onmouseover="window.status='{$img_header}'; return true;" onmouseout="window.status=''; return true;">
<img src="{$potd_path}/{$_picture1}" border="0" vspace="2" hspace="2"></a></div>
{/if} <!-- end thumbnail exists -->
{if $picture1}
echo" <div class="smallcenter"><a href="classified.php?catid={$category}&subcatid={$subcategory}&adid={$adid}"onmouseover="window.status='{$img_header}'; return true;" onmouseout="window.status=''; return true;">
        <img src="{$potd_path}/{$picture1}" {$sizestr} border="0" vspace="2" hspace="2"></a></div>
{/if} <!-- end resize image if no thumbnail -->
    {else}
  <a href="contact.php">Advertising at Logix Classifieds">
<img src="images/pb_button3.gif" border="0" hspace="1" width="120" height="121" align="center"></a>
  {/if}<!--end POTD -->
          </td>
        </tr>
       </table>
     </td>
    </tr>
   </table>
{/if}












