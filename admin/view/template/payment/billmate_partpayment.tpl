<?php echo $header; ?>
<script type="text/javascript">
    function submitBillmateForm(updatePClasses) {
        if(updatePClasses == true) {
            jQuery('#perform_pclass_update').val('1');
        }
        jQuery('#form').submit();
    }
</script>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="submitBillmateForm(true)" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <div id="htabs" class="htabs"><a href="#tab-general"><?php echo $tab_general ?></a><a href="#tab-log"><?php echo $tab_log ?></a><a href="#tab-pclasses">Pclasses</a></div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
            <input type="hidden" id="perform_pclass_update" name="perform_pclass_update" value="0" />
        <div id="tab-general"><img  style="float: right;" src="<?php echo HTTP_CATALOG; ?>billmate/images/bm_delbetalning_l.png" /></a>
          <div id="vtabs" class="vtabs">
            <?php foreach ($countries as $country) { ?>
            <a href="#tab-<?php echo $country['code']; ?>"><?php echo $country['name']; ?></a>
            <?php } ?>
          </div>
          <?php foreach ($countries as $country) { ?>
          <div id="tab-<?php echo $country['code']; ?>" class="vtabs-content">
            <table class="form">
              <tr>
                <td><?php echo $entry_merchant; ?></td>
                <td><input type="text" name="billmate_partpayment[<?php echo $country['code']; ?>][merchant]" value="<?php echo isset($billmate_partpayment[$country['code']]) ? $billmate_partpayment[$country['code']]['merchant'] : ''; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $entry_secret; ?></td>
                <td><input type="text" name="billmate_partpayment[<?php echo $country['code']; ?>][secret]" value="<?php echo isset($billmate_partpayment[$country['code']]) ? $billmate_partpayment[$country['code']]['secret'] : ''; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $entry_server; ?></td>
                <td><select name="billmate_partpayment[<?php echo $country['code']; ?>][server]">
                    <?php if (isset($billmate_partpayment[$country['code']]) && $billmate_partpayment[$country['code']]['server'] == 'live') { ?>
                    <option value="live" selected="selected"><?php echo $text_live; ?></option>
                    <?php } else { ?>
                    <option value="live"><?php echo $text_live; ?></option>
                    <?php } ?>
                    <?php if (isset($billmate_partpayment[$country['code']]) && $billmate_partpayment[$country['code']]['server'] == 'beta') { ?>
                    <option value="beta" selected="selected"><?php echo $text_beta; ?></option>
                    <?php } else { ?>
                    <option value="beta"><?php echo $text_beta; ?></option>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_pending_status; ?></td>
                <td><select name="billmate_partpayment[<?php echo $country['code']; ?>][pending_status_id]">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if (isset($billmate_partpayment[$country['code']]) && $order_status['order_status_id'] == $billmate_partpayment[$country['code']]['pending_status_id']) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_accepted_status; ?></td>
                <td><select name="billmate_partpayment[<?php echo $country['code']; ?>][accepted_status_id]">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if (isset($billmate_partpayment[$country['code']]) && $order_status['order_status_id'] == $billmate_partpayment[$country['code']]['accepted_status_id']) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_mintotal; ?></td>
                <td><input type="text" name="billmate_partpayment[<?php echo $country['code']; ?>][mintotal]" value="<?php echo isset($billmate_partpayment[$country['code']]) ? $billmate_partpayment[$country['code']]['mintotal'] : ''; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $entry_maxtotal; ?></td>
                <td><input type="text" name="billmate_partpayment[<?php echo $country['code']; ?>][maxtotal]" value="<?php echo isset($billmate_partpayment[$country['code']]) ? $billmate_partpayment[$country['code']]['maxtotal'] : ''; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $entry_geo_zone; ?></td>
                <td><select name="billmate_partpayment[<?php echo $country['code']; ?>][geo_zone_id]">
                    <option value="0"><?php echo $text_all_zones; ?></option>
                    <?php foreach ($geo_zones as $geo_zone) { ?>
                    <?php if (isset($billmate_partpayment[$country['code']]) && $geo_zone['geo_zone_id'] == $billmate_partpayment[$country['code']]['geo_zone_id']) {  ?>
                    <option value="<?php echo $geo_zone['geo_zone_id'] ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $geo_zone['geo_zone_id'] ?>"><?php echo $geo_zone['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_status; ?></td>
                <td><select name="billmate_partpayment[<?php echo $country['code']; ?>][status]">
                    <?php if (isset($billmate_partpayment[$country['code']]) && $billmate_partpayment[$country['code']]['status']) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_sort_order ?></td>
                <td><input type="text" name="billmate_partpayment[<?php echo $country['code']; ?>][sort_order]" value="<?php echo isset($billmate_partpayment[$country['code']]) ? $billmate_partpayment[$country['code']]['sort_order'] : ''; ?>" /></td>
              </tr>
            </table>
          </div>
          <?php } ?>
        </div>
        <div id="tab-log">
          <table class="form">
            <tr>
              <td><textarea wrap="off" style="width: 98%; height: 300px; padding: 5px; border: 1px solid #CCCCCC; background: #FFFFFF; overflow: scroll;"><?php echo $log ?></textarea></td>
            </tr>
            <tr>
              <td style="text-align: right;"><a href="<?php echo $clear; ?>" class="button"><?php echo $button_clear ?></a></td>
            </tr>
          </table>
        </div>
        <div id="tab-pclasses">
          <table class="form">
			<?php
			$head = array();
			if( is_array( $all_pclasses[0] )){
				$head = array_keys($all_pclasses[0]);
			}
			?>
            <tr>
				<?php foreach($head as $row) echo '<th>',ucfirst(str_replace('_',' ', $row)),'</th>'; ?>
            </tr>
		  <?php
			foreach($all_pclasses as $pclass){
				echo '<tr>';
				foreach($pclass as $key => $val){
					if( $key == 'country' ){
						echo '<td align="center">',($val==209? 'Sweden': $val),'</td>';
					}else{
						echo '<td align="center">',$val,'</td>';
					}
				}
				echo '</tr>';
			}
		  ?>
          </table>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript"><!--
$('#htabs a').tabs();
$('#vtabs a').tabs();
//--></script> 
<?php echo $footer; ?> 
