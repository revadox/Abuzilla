<?php
session_start();
include('connection.php');
$uname=$_SESSION['user'];
$quesId=$_REQUEST['quesId'];
$ansText=$_REQUEST['ansText'];
$qforid=mysql_query("select userid from user where uname='$uname'");
$res=mysql_fetch_assoc($qforid);
$uid=$res['userid'];

mysql_query("insert into ans_ques values('','$quesId','$uid','$ansText')");


//for answer section//
$ques_id=$quesId;
$ansSecId="ansSecId".$ques_id;//id of each section containg entire answer section//
$ansTextId="ansText".$ques_id;//id for each text area for the answer//
//ends here



?>
<div id="<?php echo $ansSecId; ?> ">
<?php
$query=mysql_query("select * from ans_ques where ques_id='$ques_id'");
$ans_count=mysql_num_rows($query);
echo "<h1>Answers:$ans_count</h1>";
while($rts=mysql_fetch_assoc($query)){
  //user of particular ans//
  echo "<br/>";
$ansId=$rts['ans_id'];//id of particular ans//
$puid=$rts['user_id'];
$pname=mysql_fetch_assoc(mysql_query("select fname,lname from user where userid='$puid'"));
echo $pname['fname']." ".$pname['lname'].":";
  //ends here//
echo $rts['ans_text'];
echo "<br/>";
$idOfDeleteButton="deleteBut".$ansId;
//delete particular answer sction here//
if($puid==$uid ||$user_id==$uid){
  ?>
<div class="deleteButton" id="<?php echo $idOfDeleteButton; ?>" onclick="deleteAnswer(<?php echo $ansId; ?>,<?php echo $ques_id; ?>)" >Delete</div>
<?php
}
$upVoteId="upVote".$ansId;//id of the upvote button//
$downVoteId="downVote".$ansId;//id of the down vote button//

$upDownVoteStatId="voteStatPor".$ansId;//id of the upvote and downvote stat portion id//


$checkVote=mysql_query("select * from ans_status where ansId='$ansId' and userId='$uid'");
$act=mysql_fetch_assoc($checkVote);
$voteAction=$act['status'];
$totalVoteCount = mysql_query('SELECT COUNT(*) FROM ans_status WHERE ansId = "'.$ansId.'"');
$totalVoteCount = mysql_result($totalVoteCount, 0);
 $upVoteCount=mysql_num_rows(mysql_query("select * from ans_status where ansId='$ansId' and status='upVote'"));
$upVotePercent =($upVoteCount/$totalVoteCount)*100;
$downVoteCount = mysql_num_rows(mysql_query("select * from ans_status where ansId='$ansId' and status='downVote'"));
$downVotePercent = ($downVoteCount/$totalVoteCount)*100;

?>

<div class="tab-tr">
<div id="<?php echo $upVoteId; ?>"   class="but like-btn <?php if($voteAction=="upVote"){echo 'upvote_click'; } ?>"  onClick="vote(<?php echo $ansId; ?>,'upVote')">UpVote</div>
<div id="<?php echo $downVoteId; ?>"  class=" but dislike-btn <?php if($voteAction=="downVote"){echo 'downvote_click'; } ?>"   onClick="vote(<?php echo $ansId; ?>,'downVote')">DownVote</div>
<br/>
<br/>
<br/>

<div class="stat-cnt">
  <div id="<?php echo $upDownVoteStatId; ?>" >
 <div class="rate-count"><?php echo $totalVoteCount; ?></div>
 <div class="stat-bar">
     <div class="bg-green" style="width:<?php echo $upVotePercent; ?>%;"></div>
     <div class="bg-red" style="width:<?php echo $downVotePercent; ?>%"></div>
 </div>
 <div class="dislike-count"><?php echo $downVoteCount; ?></div>
 <div class="like-count"><?php echo $upVoteCount; ?></div>
 </div>
</div>
</div>

<?php
}
?>
</div>
