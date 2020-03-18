<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<div class="modal-header">
	<button class="close" data-dismiss="modal">×</button>
	<h3><span class="action_title">{t domain="store"}查看详情{/t}</span></h3>
</div>
<div class="modal-body">
	<form class="form-horizontal" method="post" action="" name="Form">
		<fieldset>
			<div class="row-fluid priv_list">
				<div class="control-group formSep">
					<label class="control-label control-label-new">Session Keys：</label>
					<div class="controls">
						<span class="parent_name">{$session_info.keys}</span>
					</div>
				</div>
				
				<div class="control-group formSep">
					<label class="control-label control-label-new">Session Keys：</label>
					<div class="controls">
						<span class="parent_name">{$session_info.keys}</span>
					</div>
				</div>
				
			</div>
		</fieldset>
	</form>
</div>