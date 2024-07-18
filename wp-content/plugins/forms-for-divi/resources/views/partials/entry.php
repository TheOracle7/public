<?php
    $allowed_tags = wp_kses_allowed_html('post');
?>
<div class="wpt-form-entry-panel">
	<div class="wpt-form-entry-panel-content">

		<h3><?php echo esc_html($entry['form_name']) ?></h3>
		<p><b>Created On</b> : <span><?php echo esc_html($entry['form_date']); ?></span></p>
		<p><b>Referring URL</b> : <span><a href="<?php echo esc_url(home_url($entry['form_post_url'])) ?>"><?php echo esc_url(home_url($entry['form_post_url'])); ?></a></span></p>

		<hr>

		<?php foreach ($form_data as $key => $value): ?>
			<p>
				<b><?php echo esc_html(ucwords(str_replace(['_', '-'], ' ', $key))); ?></b> : <span><?php echo wp_kses($this->container['entry']->get_formatted_value($value), $allowed_tags); ?></span>
			</p>
		<?php endforeach?>

	</div>
</div>

<div class='wpt-entry-actions'>
	<a href="<?php echo esc_url($back_url); ?>" class='wpt-entry-action'><span class="dashicons dashicons-arrow-left-alt"></span> Back</a>
	<span>  |  </span>
	<form method='POST' action='<?php echo esc_url($back_url); ?>' class='thrash' onsubmit='return window.confirm("Are you sure?")'>
		<input type="hidden" name="__action" value='delete'>
		<input type="hidden" name="return" value='<?php echo esc_url($back_url); ?>'>
		<input type="hidden" name="id" value='<?php echo esc_attr($entry['ID']); ?>'>
		<button type="submit" class="wpt-entry-action"><span class="dashicons dashicons-no"></span><span>Trash</span></button>
	</form>
</div>
