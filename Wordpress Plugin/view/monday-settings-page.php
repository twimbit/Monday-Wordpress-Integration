<?php

//monday options page
class Monday_Settings_Page {

	function __construct() {

		add_action( 'admin_menu', array( $this, 'add_plugin_settings_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );

	}

	function add_plugin_settings_menu() {
		// add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function )
		add_options_page( 'Monday Integration', 'Monday Integration', 'manage_options', 'monday', array(
			$this,
			'create_plugin_settings_page'
		) );
		add_filter( 'admin_footer_text', 'remove_footer_admin' );

		function remove_footer_admin() { ?>
            <span id="footer-thankyou">Twimbit wordpress monday integration <a
                        href="https://github.com/twimbit/monday-wordpress-integration">plugin</a>.</span>
			<?php
		}
	}

	function create_plugin_settings_page() {
		$monday_data = get_option( 'authorization' );
		?>
        <style>
            #footer-upgrade {
                display: none;
            }

            /* Style the tab */
            .tab {
                overflow: hidden;
                border: 1px solid #ccc;
                background-color: #f1f1f1;
            }

            /* Style the buttons inside the tab */
            .tab button {
                background-color: inherit;
                float: left;
                border: none;
                outline: none;
                cursor: pointer;
                padding: 7px 14px;
                transition: 0.3s;
                font-size: 14px;
            }

            /* Change background color of buttons on hover */
            .tab button:hover {
                background-color: #ddd;
            }

            /* Create an active/current tablink class */
            .tab button.active {
                background-color: #ccc;
            }

            /* Style the tab content */
            .tabcontent {
                display: none;
                padding: 6px 12px;
                border: 1px solid #ccc;
                border-top: none;
            }

            .monday-container {
                padding-right: 20px;
            }

            .monday-cover img {
                width: 100%;
                position: relative;
                top: -46px;
                left: -10px;
                max-height: 390px;
                object-fit: contain;
            }

            .monday-cover {
                width: 100%;
                height: 343px;
            }

            #advance {
                display: none;
            }

        </style>
        <div class="wrap">
            <div class="monday-cover">
                <img src="<?php echo plugins_url() ?>/monday-twimbit/assets/monday-cover.png"
                     alt="">
            </div>

            <p>Set your monday integration settings</p>

            <div class="tab">
                <button class="tablinks active" onclick="openCity(event, 'general')">General</button>
                <button class="tablinks" onclick="openCity(event, 'integration')">Integration</button>
                <button class="tablinks" onclick="openCity(event, 'advance')" style="display: none">Advance</button>
            </div>

            <div id="general" class="tabcontent">
                <form method="post" action="options.php">
					<?php
					// This prints out all hidden setting fields
					// settings_fields( $option_group )
					settings_fields( 'settings-group' );
					// do_settings_sections( $page )
					do_settings_sections( 'test-plugin' );
					//submit_button( 'Save Changes' ); ?>
                </form>
            </div>

            <div id="integration" class="tabcontent">
                <table class="form-table">
                    <tbody>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            Refresh Monday Keys
                        </th>
                        <td class="forminp">
                            <button type="button" id="monday-key-refresh" class="copy-button button-secondary copy-key"
                                    data-tip="Refresh">
                                Refresh
                            </button>

                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            Monday key
                        </th>
                        <td class="forminp">
                            <input type="text" id="monday-key-input" value="<?php echo $monday_data['monday_key']; ?>"
                                   size="55" readonly="readonly">
                            <button type="button" id="monday-key" class="copy-button button-secondary copy-key"
                                    data-tip="Copied!">
                                Copy
                            </button>

                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            Monday secret
                        </th>
                        <td class="forminp">
                            <input type="text" id="monday-secret-input"
                                   value="<?php echo $monday_data['monday_secret']; ?>"
                                   size="55" readonly="readonly">
                            <button type="button" id="monday-secret" class="copy-button button-secondary copy-secret"
                                    data-tip="Copied!">
                                Copy
                            </button>

                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div id="advance" class="tabcontent">
                <h3>Advance data</h3>
            </div>
        </div>

        <script>
            function openCity(evt, cityName) {
                var i, tabcontent, tablinks;
                tabcontent = document.getElementsByClassName("tabcontent");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }
                tablinks = document.getElementsByClassName("tablinks");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
                document.getElementById(cityName).style.display = "block";
                evt.currentTarget.className += " active";
            }

            document.getElementById('general').style.display = "block";

            /* copy logic */
            /* Get the text field */
            document.querySelector('#monday-key').addEventListener('click', function (evt) {

                var copyText = document.querySelector('#monday-key-input');

                /* Select the text field */
                copyText.select();
                copyText.setSelectionRange(0, 99999); /*For mobile devices*/

                /* Copy the text inside the text field */
                document.execCommand("copy");
            });

            document.querySelector('#monday-secret').addEventListener('click', function (evt) {

                var copyText = document.querySelector('#monday-secret-input');

                /* Select the text field */
                copyText.select();
                copyText.setSelectionRange(0, 99999); /*For mobile devices*/

                /* Copy the text inside the text field */
                document.execCommand("copy");
            });

            jQuery(document).ready(function ($) {
                $('#monday-key-refresh').click(function (evt) {

                    $.ajax({
                        url: '<?php echo home_url(); ?>' + '/wp-admin/admin-ajax.php',
                        type: "POST",
                        data: "action=keys_refresh",
                        success: function (data) {
                            let monday_data = JSON.parse(data);
                            let monday_key = monday_data.monday_key;
                            let monday_secret = monday_data.monday_secret;

                            $('#monday-secret-input').val(monday_secret);
                            $('#monday-key-input').val(monday_key);

                        }
                    });
                });

            });


        </script>
		<?php
	}

	function register_settings() {

		// add_settings_section( $id, $title, $callback, $page )
		add_settings_section(
			'post-sync',
			'sync your wordpress posts',
			array( $this, 'print_post_sync_info' ),
			'test-plugin'
		);

		// add_settings_field( $id, $title, $callback, $page, $section, $args )
		add_settings_field(
			'post-sync-button',
			'Press to sync',
			array( $this, 'post_sync_button' ),
			'test-plugin',
			'post-sync'
		);

		// add_settings_section( $id, $title, $callback, $page )
//		add_settings_section(
//			'additional-settings-section',
//			'Select Monday Board',
//			array( $this, 'print_additional_settings_section_info' ),
//			'test-plugin'
//		);

		// add_settings_field( $id, $title, $callback, $page, $section, $args )
//		add_settings_field(
//			'monday-board',
//			'Monday Board',
//			array( $this, 'create_input_another_setting' ),
//			'test-plugin',
//			'additional-settings-section'
//		);

		// register_setting( $option_group, $option_name, $sanitize_callback )
//		register_setting( 'settings-group', 'selected_monday_boards', array(
//			$this,
//			'plugin_additional_settings_validate'
//		) );


	}

	function post_sync_button() {
		?>
        <a id="post-sync" class="button button-primary">sync Posts</a>
        <script>
            jQuery(document).ready(function ($) {
                $('#post-sync').click(function (evt) {

                    $.ajax({
                        url: '<?php echo home_url(); ?>' + '/wp-admin/admin-ajax.php',
                        type: "POST",
                        data: "action=sync_post",
                        success: function (data) {
                            $('#post-sych-notice').removeClass('hidden');
                            console.log(data);
                        }
                    });
                });

            })
        </script>
		<?php
	}

	function print_post_sync_info() {
		echo '<div id="post-sych-notice" class="hidden notice notice-success settings-error is-dismissible"> 
<p><strong>Your posts have synced.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
	}

	function print_additional_settings_section_info() {
		echo '<p>You can select one monday board at a time for posts to sync.</p>';
	}

	function create_input_another_setting() {
		$options        = get_option( 'monday_boards' );
		$selected_board = get_option( 'selected_monday_boards' )['monday-board'];
		?> <select name="selected_monday_boards[monday-board]">
		<?php foreach ( $options as $key => $option ) { ?>
            <option value="<?php echo $option; ?>" <?php if ( $option == $selected_board ) {
				echo 'selected';
			} ?>><?php echo $option; ?></option>

		<?php } ?>

        </select><?php
	}

	function plugin_additional_settings_validate( $arr_input ) {
		$options                 = get_option( 'selected_monday_boards' );
		$options['monday-board'] = trim( $arr_input['monday-board'] );

		return $options;
	}

}

new Monday_Settings_Page();

