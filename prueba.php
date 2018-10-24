<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
<form target="transFrame" method="POST" class="EditName" id="reportEdit" action="testSubmitiFrame.php">
<div class="content" id="overDivContent">
    <div class="inputDivContainer">
        <fieldset class="inputOverDiv" id="tfa_Names">
        <legend><b>Add Transmittal:</b></legend>
        <div class="data">
            <div class="inputDiv">
                <span class="inputLabel">Description:</span>
                <span class="textInput"><input type="text" class="" size="40" value="" name="transDesc" id="transDesc"/></span>
            </div>
            <div class="inputDiv">
                <span class="inputLabel">Date:</span>
                <span class="textInput"><input type="text" class="" size="40" value="" name="transDate" id="date"/></span>
            </div>
            <div class="inputDiv">
                <span class="inputLabel">File:</span>
                <span class="textInput"><input type="file" class="" size="40" value="" name="transFile" id="file"/></span>
            </div>
            <input type="hidden" value="121" name="name_id"/>
            <br/>
            <div align="center" class="actions" id="overDivActions">
                <input type="submit" name="submit" value="Submit"/>
                <input type="button" value="Close" onclick="hideOverDiv()" class="secondaryAction"/>
            </div>
            <div style="display: none;" class="overDivNotice" id="overDivNotice"></div>
        </div>
        </fieldset>
    </div>
</div>
</form>
<iframe style="" name="transFrame" id="transFrame">tyh</iframe>
</body>
</html>