<?php
session_start();
include('connection.php');
$uname=$_SESSION['user'];
$qforid=mysql_query("select userid from user where uname='$uname'");
$res=mysql_fetch_assoc($qforid);
$uid=$res['userid'];
$pId=$_REQUEST['postId'];
$text=$_REQUEST['commText'];
//insert into comment table//
mysql_query("insert into user_comment values('','$pId','$uid','$text')");
//ends here

$pid=$pId;
//to select the user of this post//
$queForUid=mysql_query("select user_id from user_post where post_id='$pid' ");
$resId=mysql_fetch_assoc($queForUid);
$upid=$resId['user_id'];//user of this particular post

$commTextId="commText".$pid;
$commSec="commSec".$pid;//section of the comment area//
$query=mysql_query("select * from user_comment where post_id='$pid' order by comment_id");
$countComm=mysql_num_rows($query);//no of users corresponding to a particular post//

?>
<div id="<?php echo $commSec; ?>">
  Comments:<?php echo $countComm; ?>
  <br/>
  <?php
  while($res=mysql_fetch_assoc($query)){
    $commentId=$res['comment_id'];
    $userIdOfComment=$res['user_id'];
    $userDet=mysql_query("select fname,lname from user where userid='$userIdOfComment'");
    $userRes=mysql_fetch_assoc($userDet);
    echo $userRes['fname']." ".$userRes['lname'].":".$res['comm_text'];
    //attributes of the replysection//
    $replySecDivId="replySecDiv".$commentId;
    $replyTextId="replyText".$commentId;
    ?>
    <br/>
    <input type="text" id="<?php echo $replyTextId; ?>" class="relplyAttr" placeholder="Enter your reply..." /><div  class="relplyAttr" id="replyBut" onclick="postReplyToCommment(<?php echo $commentId; ?>)" >Reply</div><br/>
    <br/>
    <div id="<?php echo $replySecDivId; ?>">
    <?php
    $replyQ=mysql_query("select * from replyTocommentOfPost where commentId='$commentId'");
    $countOfReplies=mysql_num_rows($replyQ);
    echo "Replies".":".$countOfReplies;
    echo "<br/>";
    while($res=mysql_fetch_assoc($replyQ))
    {
    $replyId=$res['replyId'];
    $commentId=$res['commentId'];
    $userOfReplyId=$res['userId'];
    $replyText=$res['replyText'];
    $userOfReply=mysql_fetch_assoc(mysql_query("select fname,lname from user where userid='$userOfReplyId'"));
    echo $userOfReply['fname']." ".$userOfReply['lname'].":".$replyText;

    $delReplyOfPostButId="delReplyOfPostBut".$replyId;//this is for deleting the reply to the comment of the post//
    if($userOfReplyId==$uid||$userIdOfComment==$uid)
    {
    ?>
    <!--section fr deeting the reply on comment of the post-->
    <div class="delReplyBut" id="<?php echo $delReplyOfPostButId; ?>" onclick="deleteReplyOfPost(<?php echo $replyId; ?>,<?php echo $commentId;?>)">Delete</div>
    <?php
    }
    echo "<br/>";
    }

    ?>
    </div>
    <?php
    echo "<br/>";

    //delete option comes only if the post belongs to the user or the comment belongs to the user on different user post//
    if($upid==$uid || $userIdOfComment==$uid )
    {
    $delComButId="delComBut".$commentId;//id for ech delete button for the comment//
    ?>
    <!--delete button for deleteing the comment -->
    <div class="delComBut" id="<?php echo $delComButId; ?>"  onclick="deleteComment(<?php echo $commentId; ?>,<?php echo $pid; ?>)">Delete Comment</div>
    <!--delete portion ends here -->
    <?php
    }
    }
    ?>
</div>
