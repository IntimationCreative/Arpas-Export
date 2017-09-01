<?php
/**
 * Export User List
 */
?>
<div class="wrap">

    <h2><?php esc_html_e( 'Current Users', 'export_users' ); ?></h2>

    <form action="options.php" method="get">
        <?php $export_user_list->display(); ?>
        <button type="submit" class="button ieu-export">Export</button>
    </form>

</div>