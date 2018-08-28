<?php $date=0; $from=0; $newday=1;
foreach ($messages as $k => $m) {
	//if ($m['mess']) {
	$same = $this->Chat_model->samemessage($m['id']); // ((strtotime($m['timesend'])-strtotime($date))<600&&$from==$m['user'])?1:0;
	$newday = (date('d', strtotime($m['timesend']))!==date('d', strtotime($date)))?1:0;
	if ($newday&&$k>0) {
?>
<div class="new_day"><b><?=$this->Chat_model->dating($m['timesend'], true)?></b></div>
<?php } ?>
  <div class="direct-chat-msg new<?=($same==1)?' same':''?><?=($m['user']==$this->session->userdata('user'))?' right':''?>">
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