<?php
/**
 * Export User List
 */
?>
<div class="wrap">
    <h2><?php esc_html_e( 'Current Users', 'export_users' ); ?></h2>
    <div class="buttons">
        <button type="submit" class="button export_active" id="export-active" data-type="active">Export Active Users</button>
        <button type="submit" class="button export" id="export" data-type="all">Export All Users</button>
    </div>
    
    <div class="results">No Action Taken</div>
    <?php $export_user_list->display(); ?>
</div>