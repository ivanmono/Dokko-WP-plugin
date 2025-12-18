<?php
/**
 * Dokko Chat Admin Template
 * 
 * This template manually renders all form fields with a custom HTML table.
 * All HTML is fully exposed and editable for complete customization.
 */

$plugin_name = isset($this->plugin_name) ? $this->plugin_name : 'dokko-chat';
$options = get_option($plugin_name);
?>

<div class="wrap dokko-admin-wrapper">
    <!-- Page Title -->
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <!-- Main Form -->
    <form method="post" action="options.php" class="dokko-admin-form">
        
        <!-- WordPress Settings Fields (Required) -->
        <?php settings_fields('dokko_chat_settings'); ?>

        <div class="form-table dokko-general-settings no-bottom--padd">
            <!-- Visible -->
            <div class="form__field is-chk">
                <?php $this->visible_callback(); ?>
                <label class="form__label--chk" for="<?php echo $plugin_name; ?>-visible"><?php _e('Visible', 'dokko-chat'); ?></label>
            </div>
             <!-- Include Sources -->
                <div class="form__field is-chk">
                    <?php $this->include_sources_callback(); ?>
                     <label class="form__label--chk" for="<?php echo $plugin_name; ?>-include-sources"><?php _e('Include Sources', 'dokko-chat'); ?></label>
                     
                </div>
                <!-- Require Login -->
                <div class="form__field is-chk">
                    <?php $this->require_login_callback(); ?>
                    <label class="form__label--chk" for="<?php echo $plugin_name; ?>-require-login"><?php _e('Require Login', 'dokko-chat'); ?></label>          
                    
                </div>

                <!-- Start Minimized -->
                <div class="form__field is-chk">
                    <?php $this->start_minimized_callback(); ?>
                    <label class="form__label--chk" for="<?php echo $plugin_name; ?>-start-minimized"><?php _e('Start Minimized', 'dokko-chat'); ?></label>
                    
                </div>
        </div>

        <div class="form-table dokko-general-settings">

                

                <!-- ID -->
                <div class="form__field">
                        <label for="<?php echo $plugin_name; ?>-id"><?php _e('ID', 'dokko-chat'); ?></label>
                        <?php $this->id_callback(); ?>
</div>

                <!-- Tenant ID -->
                <div class="form__field">
                        <label for="<?php echo $plugin_name; ?>-tenant-id"><?php _e('Tenant ID', 'dokko-chat'); ?></label>
                        <?php $this->tenant_id_callback(); ?>
                </div>
                <!-- Document Source IDs -->
                <div class="form__field">
                    <label for="<?php echo $plugin_name; ?>-document-source-ids"><?php _e('Document Source IDs', 'dokko-chat'); ?></label>
                    <?php $this->document_source_ids_callback(); ?>
                </div>          
                <!-- Permission IDs -->
                <div class="form__field">
                    <label for="<?php echo $plugin_name; ?>-permission-ids"><?php _e('Permission IDs', 'dokko-chat'); ?></label>
                    <?php $this->permission_ids_callback(); ?>
                </div>

               
               

                

                <!-- Name -->
                <div class="form__field">
                    <label for="<?php echo $plugin_name; ?>-name"><?php _e('Name', 'dokko-chat'); ?></label>
                    <?php $this->name_callback(); ?>
                </div>

                

                <!-- Footer Route -->
                <div class="form__field">
                        <label for="<?php echo $plugin_name; ?>-footer-route"><?php _e('Footer Route', 'dokko-chat'); ?></label>
                    <?php $this->footer_route_callback(); ?>
                </div>

                <!-- Footer Text -->
                <div class="form__field">
                    <label for="<?php echo $plugin_name; ?>-footer-text"><?php _e('Footer Text', 'dokko-chat'); ?></label>
                    <?php $this->footer_text_callback(); ?>
                </div>

                <!-- Chat Header Title -->
                <div class="form__field">
                    <label for="<?php echo $plugin_name; ?>-chat-header-title"><?php _e('Chat Header Title', 'dokko-chat'); ?></label>  
                    <?php $this->chat_header_title_callback(); ?>
                </div>
                <!-- Display Mode -->
                <div class="form__field">
                    <label><?php _e('Display Mode', 'dokko-chat'); ?></label>
                    <?php $this->embedded_callback(); ?>
                </div>
                <!-- Shortcode -->
                <div class="form__field">
                    <label><?php _e('Shortcode', 'dokko-chat'); ?></label>
                    <?php $this->dokko_app_shortcode_callback(); ?>
                </div>
                <!-- Welcome Message -->
                <div class="form__field is-full">
                    <label for="<?php echo $plugin_name; ?>-welcome-message"><?php _e('Welcome Message', 'dokko-chat'); ?></label>
                    <?php $this->welcome_message_callback(); ?>
                </div>
                

                <!-- Avatar Icon -->
                <div class="form__field is-full">
                    <label><?php _e('Avatar Icon', 'dokko-chat'); ?></label>
                    <?php $this->avatar_icon_callback(); ?>
                </div>

                
        </div>

        <!-- CSS VARIABLES SECTION -->
        <h2><?php _e('CSS Variables', 'dokko-chat'); ?></h2>
        <p><?php _e('Customize the appearance of the Dokko Chat using CSS variables.', 'dokko-chat'); ?></p>

        <!-- Embedded Mode Variables -->
        
        <div class="dokko-css-settings dke-settings">
            <h2><?php _e('Embedded Mode Variables', 'dokko-chat'); ?></h2>
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dke-header-background"><?php _e('Header Background', 'dokko-chat'); ?></label>
            <?php $this->dke_header_background_callback(); ?>
            </div>
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dke-header-text"><?php _e('Header Text', 'dokko-chat'); ?></label>
            <?php $this->dke_header_text_callback(); ?>
            </div>
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dke-body-background"><?php _e('Body Background', 'dokko-chat'); ?></label>
            <?php $this->dke_body_background_callback(); ?>
            </div>
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dke-message-outgoing-background"><?php _e('Message Outgoing Background', 'dokko-chat'); ?></label>
            <?php $this->dke_message_outgoing_background_callback(); ?>
            </div>
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dke-message-outgoing-text"><?php _e('Message Outgoing Text', 'dokko-chat'); ?></label>
            <?php $this->dke_message_outgoing_text_callback(); ?>
            </div>
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dke-message-incoming-background"><?php _e('Message Incoming Background', 'dokko-chat'); ?></label>
            <?php $this->dke_message_incoming_background_callback(); ?>
            </div>
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dke-message-incoming-text"><?php _e('Message Incoming Text', 'dokko-chat'); ?></label>
            <?php $this->dke_message_incoming_text_callback(); ?>
            </div>
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dke-message-info-text"><?php _e('Message Info Text', 'dokko-chat'); ?></label>
            <?php $this->dke_message_info_text_callback(); ?>
            </div>
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dke-message-avatar-background"><?php _e('Message Avatar Background', 'dokko-chat'); ?></label>
            <?php $this->dke_message_avatar_background_callback(); ?>
            </div>
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dke-message-avatar-border"><?php _e('Message Avatar Border', 'dokko-chat'); ?></label>
            <?php $this->dke_message_avatar_border_callback(); ?>
            </div>
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dke-avatar-logo"><?php _e('Avatar Logo', 'dokko-chat'); ?></label>
            <?php $this->dke_avatar_logo_callback(); ?>
            </div>
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dke-chat-input-background"><?php _e('Chat Input Background', 'dokko-chat'); ?></label>
            <?php $this->dke_chat_input_background_callback(); ?>
            </div>
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dke-chat-input-border"><?php _e('Chat Input Border', 'dokko-chat'); ?></label>
            <?php $this->dke_chat_input_border_callback(); ?>
            </div>
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dke-chat-input-text"><?php _e('Chat Input Text', 'dokko-chat'); ?></label>
            <?php $this->dke_chat_input_text_callback(); ?>
            </div>
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dke-chat-input-button"><?php _e('Chat Input Button', 'dokko-chat'); ?></label>
            <?php $this->dke_chat_input_button_callback(); ?>
            </div>
        </div>

        <!-- Widget Mode Variables -->
        
        <div class="dokko-css-settings dkw-settings">
            <h3><?php _e('Widget Mode Variables', 'dokko-chat'); ?></h3>
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dkw-header-background"><?php _e('Header Background', 'dokko-chat'); ?></label>
            <?php $this->dkw_header_background_callback(); ?>
            </div>
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dkw-header-text"><?php _e('Header Text', 'dokko-chat'); ?></label>
            <?php $this->dkw_header_text_callback(); ?>
            </div>
          
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dkw-body-background"><?php _e('Body Background', 'dokko-chat'); ?></label>
            <?php $this->dkw_body_background_callback(); ?>
            </div>
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dkw-message-outgoing-background"><?php _e('Message Outgoing Background', 'dokko-chat'); ?></label>
            <?php $this->dkw_message_outgoing_background_callback(); ?>
            </div>
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dkw-message-outgoing-text"><?php _e('Message Outgoing Text', 'dokko-chat'); ?></label>
            <?php $this->dkw_message_outgoing_text_callback(); ?>
            </div>
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dkw-message-incoming-background"><?php _e('Message Incoming Background', 'dokko-chat'); ?></label>
            <?php $this->dkw_message_incoming_background_callback(); ?>
            </div>
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dkw-message-incoming-text"><?php _e('Message Incoming Text', 'dokko-chat'); ?></label>
            <?php $this->dkw_message_incoming_text_callback(); ?>
            </div>
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dkw-message-info-text"><?php _e('Message Info Text', 'dokko-chat'); ?></label>
            <?php $this->dkw_message_info_text_callback(); ?>
            </div>
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dkw-message-avatar-background"><?php _e('Message Avatar Background', 'dokko-chat'); ?></label>
            <?php $this->dkw_message_avatar_background_callback(); ?>
            </div>
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dkw-message-avatar-border"><?php _e('Message Avatar Border', 'dokko-chat'); ?></label>
            <?php $this->dkw_message_avatar_border_callback(); ?>
            </div>
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dkw-avatar-logo"><?php _e('Avatar Logo', 'dokko-chat'); ?></label>
            <?php $this->dkw_avatar_logo_callback(); ?>
            </div>
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dkw-chat-input-background"><?php _e('Chat Input Background', 'dokko-chat'); ?></label>
            <?php $this->dkw_chat_input_background_callback(); ?>
            </div>
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dkw-chat-input-border"><?php _e('Chat Input Border', 'dokko-chat'); ?></label>
            <?php $this->dkw_chat_input_border_callback(); ?>
            </div>
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dkw-chat-input-text"><?php _e('Chat Input Text', 'dokko-chat'); ?></label>
            <?php $this->dkw_chat_input_text_callback(); ?>
            </div>
            <div class="form__field">
            <label for="<?php echo $plugin_name; ?>-dkw-chat-input-button"><?php _e('Chat Input Button', 'dokko-chat'); ?></label>
            <?php $this->dkw_chat_input_button_callback(); ?>
            </div>

            <div class="form__field is-full is-revert">
            <label for="<?php echo $plugin_name; ?>-dkw-header-bg-image"><?php _e('Header Background Image', 'dokko-chat'); ?></label>
            <?php $this->dkw_header_bg_image_callback(); ?>
            </div>
            <div class="form__field is-full is-revert">
            <label for="<?php echo $plugin_name; ?>-dkw-header-logo"><?php _e('Header Logo', 'dokko-chat'); ?></label>
            <?php $this->dkw_header_logo_callback(); ?>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="dokko-submit-section">
            <?php submit_button(); ?>
        </div>

    </form>

</div>
