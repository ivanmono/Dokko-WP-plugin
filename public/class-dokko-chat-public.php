<?php

class Dokko_Chat_Public {
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function register_shortcode() {
        add_shortcode('dokko_app', array($this, 'dokko_app_shortcode'));
    }

    public function dokko_app_shortcode($atts) {
        $options = get_option($this->plugin_name);
        
        // Only display if visible and embedded mode is enabled
        if (!isset($options['visible']) || $options['visible'] !== 'true') {
            return '';
        }

        if ($options['embedded'] !== 'embedded') {
            return '';
        }

        if (empty($options['tenant_id'])) {
            return '';
        }

        // Return the embedded chat div
        return '<div id="dokko-embedded-chat"></div>';
    }

    public function enqueue_scripts() {
        $options = get_option($this->plugin_name);
        
        // Only enqueue if visible is set to true
        if (!isset($options['visible']) || $options['visible'] !== 'true') {
            return;
        }

        // Add the script tag with configuration
        echo '<script>
            window.dokkoChatConfig = {
                id: "' . esc_js($options['id'] ?? '') . '",
                tenantId: "' . esc_js($options['tenant_id'] ?? '') . '",
                documentSourceIds: "' . esc_js($options['document_source_ids'] ?? '') . '",
                permissionIds: "' . esc_js($options['permission_ids'] ?? '') . '",
                includeSources: ' . ($options['include_sources'] === 'true' ? 'true' : 'false') . ',
                requireLogin: ' . ($options['require_login'] === 'true' ? 'true' : 'false') . ',
                startMinimized: ' . ($options['start_minimized'] === 'true' ? 'true' : 'false') . ',
                name: "' . esc_js($options['name'] ?? '') . '",
                welcomeMessage: "' . esc_js($options['welcome_message'] ?? '') . '",
                footerRoute: "' . esc_js($options['footer_route'] ?? '') . '",
                footerText: "' . esc_js($options['footer_text'] ?? '') . '",
                chatHeaderTitle: "' . esc_js($options['chat_header_title'] ?? '') . '",
                embedded: ' . ($options['embedded'] === 'true' ? 'true' : 'false') . '
            };
        </script>';
    }

    public function enqueue_styles() {
        $options = get_option($this->plugin_name);
        
        // Only enqueue if visible is set to true
        if (!isset($options['visible']) || $options['visible'] !== 'true') {
            return;
        }

        // Add custom CSS variables
        $css = ':root {';
        
        // DKE Variables
        $css .= '--dke-header-background: ' . esc_attr($options['dke_header_background'] ?? '#CC314D') . ';';
        $css .= '--dke-header-text: ' . esc_attr($options['dke_header_text'] ?? '#ffffff') . ';';
        $css .= '--dke-body-background: ' . esc_attr($options['dke_body_background'] ?? '#14181e') . ';';
        $css .= '--dke-message-outgoing-background: ' . esc_attr($options['dke_message_outgoing_background'] ?? '#CC314D') . ';';
        $css .= '--dke-message-outgoing-text: ' . esc_attr($options['dke_message_outgoing_text'] ?? '#ffffff') . ';';
        $css .= '--dke-message-incoming-background: ' . esc_attr($options['dke_message_incoming_background'] ?? '#373737') . ';';
        $css .= '--dke-message-incoming-text: ' . esc_attr($options['dke_message_incoming_text'] ?? '#ffffff') . ';';
        $css .= '--dke-message-info-text: ' . esc_attr($options['dke_message_info_text'] ?? '#f0f0f0') . ';';
        $css .= '--dke-message-avatar-background: ' . esc_attr($options['dke_message_avatar_background'] ?? '#373737') . ';';
        $css .= '--dke-message-avatar-border: ' . esc_attr($options['dke_message_avatar_border'] ?? '#373737') . ';';
        $css .= '--dke-avatar-logo: ' . esc_attr($options['dke_avatar_logo'] ?? '#CC314D') . ';';
        $css .= '--dke-chat-input-background: ' . esc_attr($options['dke_chat_input_background'] ?? '#373737') . ';';
        $css .= '--dke-chat-input-border: ' . esc_attr($options['dke_chat_input_border'] ?? '#373737') . ';';
        $css .= '--dke-chat-input-text: ' . esc_attr($options['dke_chat_input_text'] ?? '#ffffff') . ';';
        $css .= '--dke-chat-input-button: ' . esc_attr($options['dke_chat_input_button'] ?? '#CC314D') . ';';

        // DKS Variables
        $css .= '--dks-font-family: ' . esc_attr($options['dks_font_family'] ?? 'ui-sans-serif, system-ui, sans-serif, \'Apple Color Emoji\', \'Segoe UI Emoji\', \'Segoe UI Symbol\', \'Noto Color Emoji\'') . ';';
        $css .= '--dks-color-loader: ' . esc_attr($options['dks_color_loader'] ?? '#CC314D') . ';';
        $css .= '--dks-color-input-background: ' . esc_attr($options['dks_color_input_background'] ?? '#FFFFFF') . ';';
        $css .= '--dks-color-btn-background: ' . esc_attr($options['dks_color_btn_background'] ?? '#373737') . ';';
        $css .= '--dks-color-btn-text: ' . esc_attr($options['dks_color_btn_text'] ?? '#ffffff') . ';';
        $css .= '--dks-color-btn-border: ' . esc_attr($options['dks_color_btn_border'] ?? '#373737') . ';';
        $css .= '--dks-font-size: ' . esc_attr($options['dks_font_size'] ?? '16px') . ';';
        $css .= '--dks-font-weight: ' . esc_attr($options['dks_font_weight'] ?? '400') . ';';
        $css .= '--dks-color-active: ' . esc_attr($options['dks_color_active'] ?? '#373737') . ';';
        $css .= '--dks-color-inactive: ' . esc_attr($options['dks_color_inactive'] ?? '#9CA3AF') . ';';
        $css .= '--dks-icon-size: ' . esc_attr($options['dks_icon_size'] ?? '16px') . ';';

        // DKW Variables
        $css .= '--dkw-header-background: ' . esc_attr($options['dkw_header_background'] ?? '#CC314D') . ';';
        $css .= '--dkw-header-text: ' . esc_attr($options['dkw_header_text'] ?? '#ffffff') . ';';
        $css .= '--dkw-header-bg-image: url(\'' . esc_url($options['dkw_header_bg_image'] ?? '') . '\');';
        $css .= '--dkw-header-logo: url(\'' . esc_url($options['dkw_header_logo'] ?? '') . '\');';
        $css .= '--dkw-avatar-icon: url(\'' . esc_url($options['avatar_icon'] ?? '') . '\');';
        $css .= '--dkw-body-background: ' . esc_attr($options['dkw_body_background'] ?? '#14181e') . ';';
        $css .= '--dkw-message-outgoing-background: ' . esc_attr($options['dkw_message_outgoing_background'] ?? '#CC314D') . ';';
        $css .= '--dkw-message-outgoing-text: ' . esc_attr($options['dkw_message_outgoing_text'] ?? '#ffffff') . ';';
        $css .= '--dkw-message-incoming-background: ' . esc_attr($options['dkw_message_incoming_background'] ?? '#373737') . ';';
        $css .= '--dkw-message-incoming-text: ' . esc_attr($options['dkw_message_incoming_text'] ?? '#ffffff') . ';';
        $css .= '--dkw-message-info-text: ' . esc_attr($options['dkw_message_info_text'] ?? '#f0f0f0') . ';';
        $css .= '--dkw-message-avatar-background: ' . esc_attr($options['dkw_message_avatar_background'] ?? '#373737') . ';';
        $css .= '--dkw-message-avatar-border: ' . esc_attr($options['dkw_message_avatar_border'] ?? '#373737') . ';';
        $css .= '--dkw-avatar-logo: ' . esc_attr($options['dkw_avatar_logo'] ?? '#CC314D') . ';';
        $css .= '--dkw-chat-input-background: ' . esc_attr($options['dkw_chat_input_background'] ?? '#373737') . ';';
        $css .= '--dkw-chat-input-border: ' . esc_attr($options['dkw_chat_input_border'] ?? '#373737') . ';';
        $css .= '--dkw-chat-input-text: ' . esc_attr($options['dkw_chat_input_text'] ?? '#ffffff') . ';';
        $css .= '--dkw-chat-input-button: ' . esc_attr($options['dkw_chat_input_button'] ?? '#CC314D') . ';';
        
        $css .= '}';

        // Add the CSS variables to the page
        wp_add_inline_style($this->plugin_name, $css);

        // Enqueue the main stylesheet
        // wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/dokko-chat-public.css', array(), $this->version, 'all');
    }

    public function add_chat_widget() {
        $options = get_option($this->plugin_name);
        
        // Only add widget if visible is set to true
        if (!isset($options['visible']) || $options['visible'] !== 'true') {
            return;
        }

        if (empty($options['tenant_id'])) {
            return;
        }

        // Add CSS variables
        echo '<style>
            :root {
                /* Dokko Chat Engine Variables (dke_) */
                --dke-header-background: ' . esc_attr($options['dke_header_background'] ?? '#CC314D') . ';
                --dke-header-text: ' . esc_attr($options['dke_header_text'] ?? '#ffffff') . ';
                --dke-body-background: ' . esc_attr($options['dke_body_background'] ?? '#14181e') . ';
                --dke-message-outgoing-background: ' . esc_attr($options['dke_message_outgoing_background'] ?? '#CC314D') . ';
                --dke-message-outgoing-text: ' . esc_attr($options['dke_message_outgoing_text'] ?? '#ffffff') . ';
                --dke-message-incoming-background: ' . esc_attr($options['dke_message_incoming_background'] ?? '#373737') . ';
                --dke-message-incoming-text: ' . esc_attr($options['dke_message_incoming_text'] ?? '#ffffff') . ';
                --dke-message-info-text: ' . esc_attr($options['dke_message_info_text'] ?? '#f0f0f0') . ';
                --dke-message-avatar-background: ' . esc_attr($options['dke_message_avatar_background'] ?? '#373737') . ';
                --dke-message-avatar-border: ' . esc_attr($options['dke_message_avatar_border'] ?? '#373737') . ';
                --dke-avatar-logo: ' . esc_attr($options['dke_avatar_logo'] ?? '#CC314D') . ';
                --dke-chat-input-background: ' . esc_attr($options['dke_chat_input_background'] ?? '#373737') . ';
                --dke-chat-input-border: ' . esc_attr($options['dke_chat_input_border'] ?? '#373737') . ';
                --dke-chat-input-text: ' . esc_attr($options['dke_chat_input_text'] ?? '#ffffff') . ';
                --dke-chat-input-button: ' . esc_attr($options['dke_chat_input_button'] ?? '#CC314D') . ';

                /* Dokko Chat Widget Variables (dkw_) */
                --dkw-header-background: ' . esc_attr($options['dkw_header_background'] ?? '#CC314D') . ';
                --dkw-header-text: ' . esc_attr($options['dkw_header_text'] ?? '#ffffff') . ';
                --dkw-header-bg-image: url(\'' . esc_url($options['dkw_header_bg_image'] ?? '') . '\');
                --dkw-header-logo: url(\'' . esc_url($options['dkw_header_logo'] ?? '') . '\');
                --dkw-avatar-icon: url(\'' . esc_url($options['avatar_icon'] ?? '') . '\');
                --dkw-body-background: ' . esc_attr($options['dkw_body_background'] ?? '#14181e') . ';
                --dkw-message-outgoing-background: ' . esc_attr($options['dkw_message_outgoing_background'] ?? '#CC314D') . ';
                --dkw-message-outgoing-text: ' . esc_attr($options['dkw_message_outgoing_text'] ?? '#ffffff') . ';
                --dkw-message-incoming-background: ' . esc_attr($options['dkw_message_incoming_background'] ?? '#373737') . ';
                --dkw-message-incoming-text: ' . esc_attr($options['dkw_message_incoming_text'] ?? '#ffffff') . ';
                --dkw-message-info-text: ' . esc_attr($options['dkw_message_info_text'] ?? '#f0f0f0') . ';
                --dkw-message-avatar-background: ' . esc_attr($options['dkw_message_avatar_background'] ?? '#373737') . ';
                --dkw-message-avatar-border: ' . esc_attr($options['dkw_message_avatar_border'] ?? '#373737') . ';
                --dkw-avatar-logo: ' . esc_attr($options['dkw_avatar_logo'] ?? '#CC314D') . ';
                --dkw-chat-input-background: ' . esc_attr($options['dkw_chat_input_background'] ?? '#373737') . ';
                --dkw-chat-input-border: ' . esc_attr($options['dkw_chat_input_border'] ?? '#373737') . ';
                --dkw-chat-input-text: ' . esc_attr($options['dkw_chat_input_text'] ?? '#ffffff') . ';
                --dkw-chat-input-button: ' . esc_attr($options['dkw_chat_input_button'] ?? '#CC314D') . ';
            }

            /* Override widget styles with higher specificity */
            body .dokko-chat-widget,
            body .dokko-chat-widget * {
                /* Force our variables to take precedence */
                --dke-header-background: var(--dke-header-background) !important;
                --dke-header-text: var(--dke-header-text) !important;
                --dke-body-background: var(--dke-body-background) !important;
                --dke-message-outgoing-background: var(--dke-message-outgoing-background) !important;
                --dke-message-outgoing-text: var(--dke-message-outgoing-text) !important;
                --dke-message-incoming-background: var(--dke-message-incoming-background) !important;
                --dke-message-incoming-text: var(--dke-message-incoming-text) !important;
                --dke-message-info-text: var(--dke-message-info-text) !important;
                --dke-message-avatar-background: var(--dke-message-avatar-background) !important;
                --dke-message-avatar-border: var(--dke-message-avatar-border) !important;
                --dke-avatar-logo: var(--dke-avatar-logo) !important;
                --dke-chat-input-background: var(--dke-chat-input-background) !important;
                --dke-chat-input-border: var(--dke-chat-input-border) !important;
                --dke-chat-input-text: var(--dke-chat-input-text) !important;
                --dke-chat-input-button: var(--dke-chat-input-button) !important;

                --dkw-header-background: var(--dkw-header-background) !important;
                --dkw-header-bg-image: var(--dkw-header-bg-image) !important;
                --dkw-header-logo: var(--dkw-header-logo) !important;
                --dkw-avatar-icon: var(--dkw-avatar-icon) !important;
                --dkw-header-text: var(--dkw-header-text) !important;
                --dkw-body-background: var(--dkw-body-background) !important;
                --dkw-message-outgoing-background: var(--dkw-message-outgoing-background) !important;
                --dkw-message-outgoing-text: var(--dkw-message-outgoing-text) !important;
                --dkw-message-incoming-background: var(--dkw-message-incoming-background) !important;
                --dkw-message-incoming-text: var(--dkw-message-incoming-text) !important;
                --dkw-message-info-text: var(--dkw-message-info-text) !important;
                --dkw-message-avatar-background: var(--dkw-message-avatar-background) !important;
                --dkw-message-avatar-border: var(--dkw-message-avatar-border) !important;
                --dkw-avatar-logo: var(--dkw-avatar-logo) !important;
                --dkw-chat-input-background: var(--dkw-chat-input-background) !important;
                --dkw-chat-input-border: var(--dkw-chat-input-border) !important;
                --dkw-chat-input-text: var(--dkw-chat-input-text) !important;
                --dkw-chat-input-button: var(--dkw-chat-input-button) !important;
            }
        </style>';

        // Output the chat widget script
        echo '<script src="https://dokko-widget-bucket.s3.amazonaws.com/dokko-chat-widget-script.js" 
            id="' . esc_attr($options['id'] ?? 'dokko-chat-mono') . '"
            data-tenant-id="' . esc_attr($options['tenant_id']) . '"
            data-document-source-ids="' . esc_attr($options['document_source_ids'] ?? '') . '"
            data-permission-ids="' . esc_attr($options['permission_ids'] ?? '') . '"
            data-include-sources="' . esc_attr($options['include_sources'] ?? 'false') . '"
            data-require-login="' . esc_attr($options['require_login'] ?? 'false') . '"
            data-start-minimized="' . esc_attr($options['start_minimized'] ?? 'false') . '"
            data-name="' . esc_attr($options['name'] ?? '') . '"
            data-welcome-message="' . esc_attr($options['welcome_message'] ?? '') . '"
            data-footer-route="' . esc_attr($options['footer_route'] ?? '') . '"
            data-footer-text="' . esc_attr($options['footer_text'] ?? '') . '"
            data-chat-header-title="' . esc_attr($options['chat_header_title'] ?? '') . '"
            data-avatar-icon="' . esc_attr($options['avatar_icon'] ?? '') . '"
            data-embedded="' . esc_attr($options['embedded'] === 'embedded' ? 'true' : 'false') . '"
            onload="window.Dokko.init()">
        </script>';
    }
} 