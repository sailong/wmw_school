<?php
?>
<form name="imgform" method="post" action="upload.php" enctype="multipart/form-data">
    <input type="text" name="userfilename" id="userfilename"> 
   <input type="file" name="userfile" id="userfile" size="64" maxlength="256" onChange="javascript:top.document.imgform.userfilename.value= top.imgform.userfile.value">
 </form> 
