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
<div id="myModal1" class="modal hide fade view-session-detail" style="height:430px;width:650px;"></div>



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
					<th class="w50">{t}Session Keys{/t}</th>
					<th>{t}用户ID{/t}</th>
					<th>{t}用户类型{/t}</th>
					<th>{t}有效期{/t}</th>
					<th>{t}操作{/t}</th>
				</tr>
			</thead>
			<tbody>
				<!-- {foreach $logs as $key => $item} -->
				<tr>
					<td class="first-cell" >{$key}</td>
					<td align="left">{$item.session_user_id}</td>
					<td align="left">{$item.session_user_type}</td>
					<td align="left">{$item.ttl_formatted}</td>
					<td align="center">
                        <a class="no-underline" href='{url path="@admin_logs/init" args="user_id={$list.user_id}"}' title="{t}查看日志{/t}"><i class="fontello-icon-doc-text"></i></a>&nbsp;&nbsp;
                        <a class="no-underline view-detail-modal" data-toggle="modal" data-backdrop="static" href="#myModal1" view-url='{url path="admincp/admin_session/detail" args="user_id={$list.user_id}"}'  title='查看详情'><i class="fontello-icon-eye"></i></a>
                        <a {if $list.action_list != 'all'}class="ajaxremove no-underline" data-toggle="ajaxremove" data-msg="{t}您确定要删除用户该用户吗？{/t}" href='{url path="@admin_user/remove" args="id={$list.user_id}"}'{else}class="nodel stop_color no-underline" href="javascript:;"{/if} title="{t}移除{/t}"><i class="fontello-icon-trash"></i></a>
                    </td>
				</tr>
				<!-- {foreachelse} -->
				<tr>
					<td class="no-records" colspan="4">{t}没有找到任何记录{/t}</td>
				</tr>
				<!-- {/foreach} -->
			</tbody>
		</table>
		<!-- {$logs.page} -->
	</div>
<!-- {/block} -->