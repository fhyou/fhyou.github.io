<html>
<head>
<?php include("menuhead.php"); ?>

<script language="JavaScript">
var i = 0; 
// used to cycle thru messages 
var TextNumber = -1;
// array of messages 
var TextInput = new Object();
// used to load manipulate message 
var HelpText=""; 
// used to load message
var Text = ""; 
// length of timeout (smaller is faster) 
var Speed=50;
// used to display message number
var message=0;
// used to position text in ver 2.0
var addPadding="\r\n";

// Each element of TextInput represents a single message.
TextInput[0] = "通知1"; 
TextInput[1] = "通知2";
TextInput[2] = "通知3";
TextInput[3] = "通知4";
TextInput[4] = "通知5";
TextInput[5] = "通知6";
TextInput[6] = "通知7";
TextInput[7] = "通知8";

TotalTextInput = 7; // (0, 1, 2, 3, 4, 5, 6, 7)

// Positioning and speed vary between versions.
var Version = navigator.appVersion; 
if (Version.substring(0, 1)==3)
{
Speed=200;
addPadding="";
}

for (var addPause = 0; addPause <= TotalTextInput; addPause++) 
{TextInput[addPause]=addPadding+TextInput[addPause];}
var TimerId
var TimerSet=false;

// Called by >>> button (display next message) .
function nextMessage() 
{
if (!TimerSet)
{
TimerSet=true;
clearTimeout (TimerId);
if (TextNumber>=TotalTextInput)
{ 
alert("这是最后一条公告，您已看完所有公告！");
TimerSet=false;
}
else
{
TextNumber+=1;
message=TextNumber+1;
document.forms[0].elements[2].value= message;
Text = TextInput[TextNumber];
HelpText = Text;
}
teletype();
}
}

// Gets and displays character from rollMessage() . 
// Variable Speed controls length of timeout and thus the speed of typing.
function teletype() 
{
if (TimerSet)
{
Text=rollMessage();
TimerId = setTimeout("teletype()", Speed);
document.forms[0].elements[0].value=Text;
}
}

// Pulls one character at a time from string and returns (as Text) to function teletype() for displaying.
function rollMessage () 
{
i++;
var CheckSpace = HelpText.substring(i-1, i);
CheckSpace = "" + CheckSpace;
if (CheckSpace == " ") 
{i++;}
if (i >= HelpText.length+1) 
{
TimerSet=false;
Text = HelpText.substring(0, i);
i=0; 
return (Text);
}
Text = HelpText.substring(0, i);
return (Text);
}
// Initially called by onLoad in BODY tag to load title. 
function initTeleType() 
{
Text="\r\n Manual Tele-Type Display";
document.forms[0].elements[0].value=Text;
}
// Called by <<< button (get previous message).
function lastMessage() 
{
if (!TimerSet  &&  TextNumber!=-1)
{
TimerSet=true;
clearTimeout (TimerId);
if (TextNumber<=0)
{ 
alert("这是第一条公告，想看更多公告请点击下一条！");
TimerSet=false;
}
else 
{
TextNumber-=1;
message=TextNumber+1;
document.forms[0].elements[2].value= message;
Text = TextInput[TextNumber];
HelpText = Text;
} 
teletype(); 
}
}
</script>
</head>

<body>

<?php include("menubody.php"); ?>
	<div class="rig_lm01">
		<div class="title">
			<img src="images/listicon.jpg" class="icon" style="padding-top: 3px;">
			<h2>公告板——信息发布</h2>
		</div>
	</div>
	<form>
		<table CELLSPACING="0" CELLPADDING="0" WIDTH="17%">
			<tr>
				<td width="100%" colspan="3" valign="top">
					<div align="center">
						<textarea NAME="teletype" ROWS="20" COLS="91" wrap="soft"></textarea>
					</div>
				</td>
			</tr>
			<tr align="center">

				<td width="30%" valign="top" bgcolor="white"><input TYPE="button"
					VALUE="上一条" onClick="lastMessage()"></td>
				<td width="30%" bgcolor="white" valign="middle"><input TYPE="text"
					value="单击上下页查看" SIZE="11" name="1"></td>
				<td width="30%" bgcolor="white" valign="top"><input TYPE="button"
					VALUE="下一条" onClick="nextMessage()"></td>
			</tr>
		</table>
	</form>
	</div>
	</div>


</body>
</html>
