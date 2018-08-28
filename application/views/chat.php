<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>Чатик</title>
	<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="/assets/css/common.css" />
	<script type="text/javascript" src="/assets/js/jquery-2.2.4.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery.knob.js"></script>
	<script type="text/javascript" src="/assets/js/common.js"></script>
	<script src="http://chat.svcontact.ru:8008/socket.io/socket.io.js"></script>
	
</head>
<body id="body">
<a href="/login/logout" id="Logout">Выйти</a>
<div class="box box-warning direct-chat direct-chat-warning">
<?php
$d=date("d"); $m=date("m");	$y=date("Y"); $arrdates='';
if (date("Y", $user['online'])==$y&&date("m", $user['online'])==$m&&date("d", $user['online'])==$d) {
	$arrdates = 'сегодня';
} elseif (date("Y", $user['online'])==($y)&&date("m", $user['online'])==$m&&date("d", $user['online'])==$d-1) {
	$arrdates = 'вчера';
} elseif (date("Y", $user['online'])==($y)&&date("m", $user['online'])==$m&&date("d", $user['online'])==$d-2) {
	$arrdates = 'позавчера';
} else {
	$arrdates = date("j.m.Y", $user['online']);
}
$loged = ($user['id']==1)?' заходил ':' заходила ';

?>
<div id="online"><div class="onl <?=($online)?'yes':'no'?>"><?=$user['name']?> <?=($online)?'в чате':$loged.$arrdates.date(" в H:i", $user['online'])?></div></div>
<div class="im_typing_wrap"><div class="im_typing"> пишет</div></div>
  <div class="box-body">
  	<div class="load"><a href="#" rel="1">Чё было раньше</a><div class="loader"><img src="/assets/img/loader.gif" alt="Loading" /></div></div>
    <div class="direct-chat-messages" id="messages">
	<?php if ($messages) { $date=0; $from=0; $newday=1; ?>
	<?php foreach ($messages as $k => $m) {
		//if ($m['mess']) {
		$same = $this->Chat_model->samemessage($m['id']); // ((strtotime($m['timesend'])-strtotime($date))<600&&$from==$m['user'])?1:0;
		$newday = (date('d', strtotime($m['timesend']))!==date('d', strtotime($date)))?1:0;
		if ($newday) { ?>
	<div class="new_day"><b><?=$this->Chat_model->dating($m['timesend'], true)?></b></div>
	<?php } ?>
	
      <div class="direct-chat-msg<?=($same==1)?' same':''?><?=($m['user']==$this->session->userdata('user'))?' right':''?>">
        <div class="direct-chat-info clearfix">
          <?php if ($same!=1) { ?>
          <span class="direct-chat-name <?=($m['user']==$this->session->userdata('user'))?'pull-right':'pull-left'?>"><?=$m['name']?></span>
          <?php } ?>
        </div>
        <img class="direct-chat-img" src="<?=$m['avatar']?>" alt="message user image">
        <div class="direct-chat-text">
        	<?php if ($same!=1) { ?>
    	<div class="direct-chat-timestamp"><?=$this->Chat_model->dating($m['timesend'])?></div>
    	<?php } else { ?>
    	<div class="direct-chat-timestamp <?=($this->Chat_model->dating($m['timesend'])==$this->Chat_model->dating($date))?'same_date':''?>"><?=$this->Chat_model->dating($m['timesend'], false, true)?></div>
    	<?php } ?>
        	<p><?=$this->Chat_model->urlsreplace($this->Chat_model->smiles($m['mess']))?></p>
        	<?php if ($m['attach']) { ?>
        	<div class="attach">
        		<a href="<?=UPL.$m['attach']?>"><img src="<?=UPL.$m['attach']?>" alt="<?=$m['name']?>" /></a>
        		<div class="atach_open" rel="<?=UPL.$m['attach']?>">Открыть</div>
        	</div>
        	<?php } ?>
        	
        	<div style="clear: both"></div>
        </div>
      </div>
	<?php $date=$m['timesend']; $from=$m['user'];  } ?>
	
	<?php } else { ?>
	<div class="nomess">Здесь будут сообщеньки</div>
	<?php } ?>

    </div>
    
  </div>
  <div class="box-footer">
    <form action="#" method="post">
    	<div class="smilelist">
	    	<ul>
	    		<?php foreach ($smiles as $k => $s) { ?>
				<li><a href="<?=$s['smile']?> "><?=$this->Chat_model->smiling($s['smile'])?></a></li>
				<?php } ?>
	    		
	    	</ul>
	    	<div style="clear:both"></div>
	    </div>
	    <div class="closesmiles">×</div>
    	<div class="smiles">
	    
    	<div class="smileswrap">
	    	
	      		<div class="smilebutton"><img src="/assets/img/smiles.png"></div>
	      	</div>
      	</div>
      <div class="input-group form-group-sm">
      	<div class="attach_wrap">
      		<div class="add"></div>
      		<div class="remove"></div>
      		<div class="loading"></div>
      		<div class="attach"></div>
      		<input type="file" id="attach" accept="image/*" />
      	</div>
      	<div class="im_editable" id="messtext" tabindex="0" contenteditable="true" placeholder="Сообщенька ..."></div>
        <input type="hidden" name="message" placeholder="Сообщенька ..." class="form-control" id="message_text">
        <input type="hidden" name="attach" id="file" value="">
        <input type="hidden" name="name" id="name" value="<?=$this->session->userdata('name')?>">
        <input type="hidden" name="userid" id="userid" value="<?=($this->session->userdata('user')==1)?2:1?>">
        <input type="hidden" name="myid" id="myid" value="<?=$this->session->userdata('user')?>">
        <span class="input-group-btn">
          <button type="button" id="message_btn" class="btn btn-success btn-sm btn-flat">Отпр.</button>
        </span>
      </div>
    </form>
  </div>
</div>

</body>
</html>