<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title( ) ); ?></h1>

    <?php 
        $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET['tab'] : 'settings' ;
    ?>

    <h2 class="nav-tab-wrapper">
        <a href="?page=wpfy_pif_admin&tab=settings" class="nav-tab <?php echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>">Settings</a>
        <a href="?page=wpfy_pif_admin&tab=inquiry_list" class="nav-tab <?php echo $active_tab == 'inquiry_list' ? 'nav-tab-active' : ''; ?>">Inquiry List</a>
    </h2>

    <form action="options.php" method="post">
        <?php 
            

            switch ($active_tab) {

                case 'inquiry_list':
                    settings_fields( 'wpfy_pi_group' );
                    do_settings_sections( 'wpfy_pi_page2' );
                    break;
                
                default:
                    settings_fields( 'wpfy_pi_group' );
                    do_settings_sections( 'wpfy_pi_page1' );
                    break;
            }
            
            submit_button( 'Save Settings');
        ?>
    </form>
</div>