<?php

defined('ABSPATH') or die('This page may not be accessed directly.');

?>
<div class="wrap">
    <h1 class="sp_options_heading">SmartPush - Free Web Push Notifications</h1>
    <section class="sp_options_setup">
        <div class="guide_header">
            <h3>Welcome!</h3>
            <p class="text-secondary">Retarget and engage your website visitors with web push notifications.</p>
        </div>

        <button class="sp_collapsable_guide sp_accordion <?php echo get_option('sp_app') ? "" : "sp_active" ?>">SmartPush Setup Guid</button>
        <div class="guide_content sp_panel">
            <h4>Step #1: Register or Log into SmartPush</h4>
            <p class="text-secondary">For using this plugin, you need to be a registered SmartPush user.</p>

            <div class="sp_options_reg_buttons">
                <div>
                    <a href="https://app.smartpush.ai/register" target="_blank" class="btn_register">Register</a>
                </div>
                <div>
                    or
                </div>
                <div>
                    <a href="https://app.smartpush.ai/login" target="_blank" class="btn_login">Login</a>
                </div>
            </div>

            <h4>Step #2: Select an App Set the App ID in Wordpress</h4>

            <div class="guide_app_setup">
                <ul>
                    <li>
                        <p>In SmartPush, select the app you want to use from the Apps Menu</p>
                        <img src="<?php echo plugin_dir_url(__FILE__) . 'images/select_app.png'; ?>"
                             alt="Select SmartPush App">
                    </li>
                    <li>
                        <p>Go to App Settings > General Settings and copy your App UUID</p>
                        <img src="<?php echo plugin_dir_url(__FILE__) . 'images/copy_app_uuid.png'; ?>"
                             alt="Select SmartPush App">
                    </li>
                    <li>
                        <p>Paste the App UUID in the field below and click Save</p>
                    </li>
                </ul>
            </div>
        </div>

        <div class="guide_footer">

            <?php if (empty(get_option('sp_app'))) {
                echo '<div class="sp_alert"><p>Please set the SmartPush App UUID in the field above to access your settings!</p></div>';
            } ?>

            <form method="post" action="options.php">
                <?php
                settings_fields('smart_push_options'); ?>
                <?php
                do_settings_sections('smart_push_options'); ?>

                <div class="sp_form_app_uuid">
                    <div>
                        <label for="sp_app_uuid">SmartPush App UUID: </label>
                    </div>
                    <div>
                        <input type="text"
                               id="sp_app_uuid"
                               name="sp_app"
                               value="<?php echo get_option('sp_app') ?>"
                               maxlength="36"
                               size="36"/>
                    </div>

                    <div>
                        <?php submit_button('Save', null); ?>
                    </div>
                </div>
            </form>

            <div class="sp_useful_link">
                <ul>
                    <li>
                        <a href="<?php echo get_option('sp_app') ? "https://app.smartpush.ai/" . get_option('sp_app') . "/app-settings" : "#" ?>"
                           class="<?php echo get_option('sp_app') ? '': 'sp_link_disabled' ?>"
                           target="_blank">Change Your App Settings</a>
                    </li>
                    <li>
                        <a href="<?php echo get_option('sp_app') ? "https://app.smartpush.ai/" . get_option('sp_app') . "/app-settings/subscriptions-and-prompt" : "#" ?>"
                           class="<?php echo get_option('sp_app') ? '': 'sp_link_disabled' ?>"
                           target="_blank">Change Opt-in Prompt Settings</a>
                    </li>
                    <li>
                        <a href="<?php echo get_option('sp_app') ? "https://app.smartpush.ai/" . get_option('sp_app') . "/app-settings/welcome-message" : "#" ?>"
                           class="<?php echo get_option('sp_app') ? '': 'sp_link_disabled' ?>"
                           target="_blank">Set or Modify Your Welcome Message</a>
                    </li>
                </ul>
            </div>

            <div class="sp_contact_us">
                <p>Have a question? <a href="https://www.smartpush.ai/contact-us/">Contact Us</a></p>
            </div>
        </div>
    </section>
</div>
