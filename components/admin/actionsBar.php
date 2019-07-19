<header id="view_all_actions_bar" class='card'>
	<div id="default_actions">
		<button id="select_all_btn" type='button' class='btn cta sml'>
			<?php echo file_get_contents($GLOBALS["htp_root"] . "src/icons/checkbox_checked.svg"); ?>
			<span>Select All</span>
		</button>
	</div>
	<div id='actions_for_selections' class='disabled'>
		<button id="deselect_all_btn" type='button' class='btn cta sml'>
			<?php echo file_get_contents($GLOBALS["htp_root"] . "src/icons/clear.svg"); ?>
			<span>Clear Selection</span>
		</button>
		<button id="edit_selected_btn" type='button' class='btn cta sml' data-action="edit">
			<?php echo file_get_contents($GLOBALS["htp_root"] . "src/icons/edit.svg"); ?>
			<span>Edit Selected</span>
		</button>
		<button id="delete_selected_btn" type='button' class='btn cta sml danger' data-action='delete'>
			<?php echo file_get_contents($GLOBALS["htp_root"] . "src/icons/delete.svg"); ?>
			<span>Delete Selected</span>
		</button>
	</div>
</header>