<!-- $Id$ -->
{include file="header.tpl"}
{include file="menu.tpl"}
<table align="center" border="0" cellspacing="0" cellpadding="0" width="{$tmp_width}">
<tr>
<td valign="top" align="right">
{include file="left.tpl"}

 </td>

 <td valign="top" align="left">
  <table align="center" border="0" cellspacing="0" cellpadding="1" margin=1 width="$table_width" height="$table_height">
    <tr>
     <td class="class1">
       <table align="center" border="0" cellspacing="0" cellpadding="3" width="100%" height="$table_height">
        <tr>
         <td class="class2">
          <div class="mainheader">$main_head</div>
          <div class="maintext">

 <br>
Welcome to <em id="red">Logix Classifieds</em>!!! The ultimate classified ad &amp; matchmaking Software.<br>
<br>
{$main_page_body}
<br>
<br>
<center><a href="classified.php?choice=top">TOP ADS</a>


{if $session_username}
<a href="classified.php?choice=new&amp;sortorder=addate desc">NEW ADS</a>
{/if}

</center>
           </div>
         </td>
        </tr>
       </table>
     </td>
    </tr>
  </table>
 </td>

 <td valign="top" align="left">
{include file="right.tpl"}

 </td>
 </tr>
 </table>

{include file="footer.tpl"}