<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.admin_logs.init();
</script>
<!-- {/block} -->

<!-- {block name="main_content"} -->
	<div>
		<h3 class="heading">
			<!-- {if $ur_here}{$ur_here}{/if} -->
		</h3>
	</div>
	<div class="row-fluid">
		<div class="control-group form-horizontal choose_list span12">
			<form name="deleteForm" method="post" action="{url path='@admin_logs/batch_drop'}">
				<!-- 批量删除 -->
				<select class="w110" name="log_date">
                    <option value="0">{t}选择日期{/t}</option>
                    <!-- {foreach from=$log_date item=list} -->
                    <option value="{$list.value}">{$list.label}</option>
                    <!-- {/foreach} -->
				</select>
				<input type="hidden" name="drop_type_date" value="true" />
				<button class="btn f_l" type="submit">{t}批量删除{/t}</button>
			</form>
			<form class="f_r" name="searchForm" method="post" action="{url path='@admin_logs/init'}">
				<!-- 关键字 -->
				<input type="text" name="keyword" size="15" placeholder="{t}请输入关键字{/t}" />
				<button class="btn" type="submit">{t}搜索{/t}</button>
			</form>
		</div>
		<table class="table table-striped" id="smpl_tbl">
			<thead>
				<tr>
					<th class="w50">{t}编号{/t}</th>
					<th>{t}操作记录{/t}</th>
				</tr>
			</thead>
			<tbody>
				<!-- {foreach $logs as $key => $item} -->
				<tr>
					<td class="first-cell" >{$key}</td>
					<td align="left">{$item}</td>
				</tr>
				<!-- {foreachelse} -->
				<tr>
					<td class="no-records" colspan="10">{t}没有找到任何记录{/t}</td>
				</tr>
				<!-- {/foreach} -->
			</tbody>
		</table>
		<!-- {$logs.page} -->
	</div>
<!-- {/block} -->