<?php
/**
 * Export User List
 */
?>
<div class="wrap">

    <h2><?php esc_html_e( 'Current Users', 'export_users' ); ?></h2>

    <!-- <form action="" method="post"> -->
        <?php $export_user_list->display(); ?>
        <button type="submit" class="button export" id="export">Export Users</button>
        <div class="results">No Action Taken</div>
    <!-- </form> -->

</div>