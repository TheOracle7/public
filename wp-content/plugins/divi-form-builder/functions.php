<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'admin_menu', 'divi_form_builder_add_menu' );

function divi_form_builder_add_menu(){
	global $menu;
	$menuExist = false;
	foreach($menu as $item) {
		if(strtolower($item[0]) == strtolower('Divi Engine')) {
			$menuExist = true;
		}
	}
	
	if(!$menuExist) {
		$icon = DE_FB_URL . '/images/dash-icon.svg';
		$page_title = 'Divi Engine';
		$menu_title = 'Divi Engine';
		$capability = 'edit_pages';
		$menu_slug  = 'divi-engine';
		$icon_url   = $icon;
		$position   = 80;
		add_menu_page(
			$page_title,
			$menu_title,
			$capability,
			$menu_slug,
			'divi_form_builder_de_page',
			$icon_url,
			$position
		);
	}
}


function divi_form_builder_de_page() {
	global $menu;
	$menuExist = false;
	
	foreach($menu as $item) {
		if(strtolower($item[0]) == strtolower('Divi Engine')) {
			$menuExist = true;
		}
	}
	
	if($menuExist) {
?>
	<div class="titan-framework-panel-wrap">
		<table class="form-table">
			<tbody>
				<tr valign="top" class="even first tf-heading">
					<th scope="row" class="first last" colspan="2">
						<h3 id="welcome-to-divi-engine">Welcome to Divi Engine</h3>
					</th>
				</tr>
				<tr valign="top" class="row-1 odd" >
					<th scope="row" class="first">
						<label for="divi-bodyshop-woo_2696610e41262487"></label>
					</th>
					<td class="second tf-note">
						<p class='description'>
							<iframe class="nitro_videos" width="560" height="315" src="https://www.youtube.com/embed/1a0widDvYjE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>						
							</p>
					</td>
				</tr>
				<tr valign="top" class="even first tf-heading">
					<th scope="row" class="first last" colspan="2">
						<h3 id="support">Support</h3>
					</th>
				</tr>
				<tr valign="top" class="row-2 even" >
					<th scope="row" class="first">
						<label for="divi-bodyshop-woo_6cec1d75697d2af1"></label>
					</th>
					<td class="second tf-note">
						<p class='description'>We know that when building a website things may not always go according to plan. If you experience issues when using our plugins do not worry we are here to help. First take a look at our documentation <a href="https://help.diviengine.com/ " target="_blank">here</a> and if you cannot find a solution, please contact us  <a href="https://diviengine.com/support/" target="_blank">here</a> and we will help you resolve any issues.
						</p>
					</td>
				</tr>
				<tr valign="top" class="even first tf-heading">
					<th scope="row" class="first last" colspan="2">
						<h3 id="feedback">Feedback</h3>
					</th>
				</tr>
				<tr valign="top" class="row-3 odd" >
					<th scope="row" class="first">
						<label for="divi-bodyshop-woo_cf59399160a5028e"></label>
					</th>
					<td class="second tf-note">
						<p class='description'>We would love to hear from you, good or bad! We would really appreciate it if you could leave a review on our product page so that it helps others!</p>
					</td>
				</tr>
				<tr valign="top" class="even first tf-heading">
					<th scope="row" class="first last" colspan="2">
						<h3 id="do-you-have-idea?">Do you have idea?</h3>
					</th>
				</tr>
				<tr valign="top" class="row-4 even" >
					<th scope="row" class="first">
						<label for="divi-bodyshop-woo_6cb3afd29b1f9cf7"></label>
					</th>
					<td class="second tf-note">
						<p class='description'>If you have an idea for how to improve our plugins, please dont hesitate to contact us <a href="https://diviengine.com/contact/" target="_blank">here</a> as we really want to make them better for everyone!</p>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
<?php
	}
}