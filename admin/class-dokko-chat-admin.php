<?php

class Dokko_Chat_Admin {
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_action('wp_ajax_insert_chat_script', array($this, 'insert_chat_script'));
        add_action('wp_ajax_remove_chat_script', array($this, 'remove_chat_script'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_media_uploader'));
        
        // Add SVG support
        add_filter('upload_mimes', array($this, 'allow_svg_uploads'));
        add_filter('wp_check_filetype_and_ext', array($this, 'check_svg_filetype'), 10, 4);
    }

    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/dokko-chat-admin.css', array(), $this->version, 'all');
    }

    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/dokko-chat-admin.js', array('jquery'), $this->version, false);
    }

    public function enqueue_media_uploader($hook) {
        // Only load on plugin settings pages
        if (strpos($hook, $this->plugin_name) === false) {
            return;
        }
        
        wp_enqueue_media();
        wp_enqueue_script(
            'dokko-media-upload',
            plugin_dir_url(__FILE__) . 'js/dokko-media-upload.js',
            array('jquery', 'media-upload', 'media-views'),
            $this->version,
            true
        );
    }

    public function add_plugin_admin_menu() {
        add_menu_page(
            'Dokko Chat Settings',
            'Dokko Chat',
            'manage_options',
            $this->plugin_name,
            array($this, 'display_plugin_setup_page'),
            'dashicons-format-chat',
            30
        );
    }

    public function register_settings() {
        // Register a setting
        register_setting(
            'dokko_chat_settings',
            $this->plugin_name,
            array($this, 'validate_settings')
        );

        // Add settings section
        add_settings_section(
            'dokko_chat_general',
            __('General Settings', 'dokko-chat'),
            array($this, 'general_section_callback'),
            $this->plugin_name
        );

        add_settings_section(
            'dokko_chat_css',
            __('CSS Variables', 'dokko-chat'),
            array($this, 'css_section_callback'),
            $this->plugin_name
        );

        // Add settings fields
        $this->add_settings_fields();
    }

    public function general_section_callback() {
        echo '<p>' . __('Configure the general settings for the Dokko Chat.', 'dokko-chat') . '</p>';
    }

    public function css_section_callback() {
        echo '<p>' . __('Customize the appearance of the Dokko Chat using CSS variables.', 'dokko-chat') . '</p>';
    }

    private function add_settings_fields() {
        // General Settings
        add_settings_field(
            'visible',
            'Visible',
            array($this, 'visible_callback'),
            $this->plugin_name,
            'dokko_chat_general'
        );

        add_settings_field(
            'id',
            'ID',
            array($this, 'id_callback'),
            $this->plugin_name,
            'dokko_chat_general'
        );

        add_settings_field(
            'tenant_id',
            'Tenant ID',
            array($this, 'tenant_id_callback'),
            $this->plugin_name,
            'dokko_chat_general'
        );

        add_settings_field(
            'document_source_ids',
            'Document Source IDs',
            array($this, 'document_source_ids_callback'),
            $this->plugin_name,
            'dokko_chat_general'
        );

        add_settings_field(
            'permission_ids',
            'Permission IDs',
            array($this, 'permission_ids_callback'),
            $this->plugin_name,
            'dokko_chat_general'
        );

        add_settings_field(
            'include_sources',
            'Include Sources',
            array($this, 'include_sources_callback'),
            $this->plugin_name,
            'dokko_chat_general'
        );

        add_settings_field(
            'require_login',
            'Require Login',
            array($this, 'require_login_callback'),
            $this->plugin_name,
            'dokko_chat_general'
        );

        add_settings_field(
            'start_minimized',
            'Start Minimized',
            array($this, 'start_minimized_callback'),
            $this->plugin_name,
            'dokko_chat_general'
        );

        add_settings_field(
            'name',
            'Name',
            array($this, 'name_callback'),
            $this->plugin_name,
            'dokko_chat_general'
        );

        add_settings_field(
            'welcome_message',
            'Welcome Message',
            array($this, 'welcome_message_callback'),
            $this->plugin_name,
            'dokko_chat_general'
        );

        add_settings_field(
            'footer_route',
            'Footer Route',
            array($this, 'footer_route_callback'),
            $this->plugin_name,
            'dokko_chat_general'
        );

        add_settings_field(
            'footer_text',
            'Footer Text',
            array($this, 'footer_text_callback'),
            $this->plugin_name,
            'dokko_chat_general'
        );

        add_settings_field(
            'chat_header_title',
            'Chat Header Title',
            array($this, 'chat_header_title_callback'),
            $this->plugin_name,
            'dokko_chat_general'
        );

        add_settings_field(
            'embedded',
            'Display Mode',
            array($this, 'embedded_callback'),
            $this->plugin_name,
            'dokko_chat_general'
        );

        add_settings_field(
            'avatar_icon',
            'Avatar Icon',
            array($this, 'avatar_icon_callback'),
            $this->plugin_name,
            'dokko_chat_general'
        );

        // CSS Variables - DKE Group (Embedded Mode)
        add_settings_field(
            'dke_group_title',
            '<h3>Embedded Mode Variables</h3>',
            array($this, 'section_title_callback'),
            $this->plugin_name,
            'dokko_chat_css',
            array('class' => 'dke-settings')
        );

        // Add all DKE fields with a class for conditional display
        $dke_fields = array(
            'dke_header_background' => 'Header Background',
            'dke_header_text' => 'Header Text',
            'dke_body_background' => 'Body Background',
            'dke_message_outgoing_background' => 'Message Outgoing Background',
            'dke_message_outgoing_text' => 'Message Outgoing Text',
            'dke_message_incoming_background' => 'Message Incoming Background',
            'dke_message_incoming_text' => 'Message Incoming Text',
            'dke_message_info_text' => 'Message Info Text',
            'dke_message_avatar_background' => 'Message Avatar Background',
            'dke_message_avatar_border' => 'Message Avatar Border',
            'dke_avatar_logo' => 'Avatar Logo',
            'dke_chat_input_background' => 'Chat Input Background',
            'dke_chat_input_border' => 'Chat Input Border',
            'dke_chat_input_text' => 'Chat Input Text',
            'dke_chat_input_button' => 'Chat Input Button'
        );

        foreach ($dke_fields as $field_id => $field_label) {
            add_settings_field(
                $field_id,
                $field_label,
                array($this, $field_id . '_callback'),
                $this->plugin_name,
                'dokko_chat_css',
                array('class' => 'dke-settings')
            );
        }

        // CSS Variables - DKW Group (Widget Mode)
        add_settings_field(
            'dkw_group_title',
            '<h3>Widget Mode Variables</h3>',
            array($this, 'section_title_callback'),
            $this->plugin_name,
            'dokko_chat_css',
            array('class' => 'dkw-settings')
        );

        // Add all DKW fields with a class for conditional display
        $dkw_fields = array(
            'dkw_header_background' => 'Header Background',
            'dkw_header_text' => 'Header Text',
            'dkw_header_bg_image' => 'Header Background Image',
            'dkw_header_logo' => 'Header Logo',
            'dkw_body_background' => 'Body Background',
            'dkw_message_outgoing_background' => 'Message Outgoing Background',
            'dkw_message_outgoing_text' => 'Message Outgoing Text',
            'dkw_message_incoming_background' => 'Message Incoming Background',
            'dkw_message_incoming_text' => 'Message Incoming Text',
            'dkw_message_info_text' => 'Message Info Text',
            'dkw_message_avatar_background' => 'Message Avatar Background',
            'dkw_message_avatar_border' => 'Message Avatar Border',
            'dkw_avatar_logo' => 'Avatar Logo',
            'dkw_chat_input_background' => 'Chat Input Background',
            'dkw_chat_input_border' => 'Chat Input Border',
            'dkw_chat_input_text' => 'Chat Input Text',
            'dkw_chat_input_button' => 'Chat Input Button'
        );

        foreach ($dkw_fields as $field_id => $field_label) {
            add_settings_field(
                $field_id,
                $field_label,
                array($this, $field_id . '_callback'),
                $this->plugin_name,
                'dokko_chat_css',
                array('class' => 'dkw-settings')
            );
        }

        // Shortcode Field (moved to general section, right after Display Mode)
        add_settings_field(
            'dokko_app_shortcode',
            __('Shortcode', 'dokko-chat'),
            array($this, 'dokko_app_shortcode_callback'),
            $this->plugin_name,
            'dokko_chat_general'
        );

        // Add JavaScript to handle the display logic
        add_action('admin_footer', array($this, 'add_display_logic_script'));
    }

    public function section_title_callback() {
        // This is just a title field, no input needed
    }

    public function render_field($args) {
        $options = get_option('dokko_chat_settings');
        $value = isset($options[$args['field_id']]) ? $options[$args['field_id']] : $args['default'];
        
        switch ($args['type']) {
            case 'checkbox':
                printf(
                    '<input type="checkbox" id="%s" name="dokko_chat_settings[%s]" value="true" %s />',
                    $args['field_id'],
                    $args['field_id'],
                    checked('true', $value, false)
                );
                break;
            case 'color':
                printf(
                    '<input type="color" id="%s" name="dokko_chat_settings[%s]" value="%s" />',
                    $args['field_id'],
                    $args['field_id'],
                    esc_attr($value)
                );
                break;
            default:
                printf(
                    '<input type="text" id="%s" name="dokko_chat_settings[%s]" value="%s" class="regular-text" />',
                    $args['field_id'],
                    $args['field_id'],
                    esc_attr($value)
                );
        }
    }

    public function display_plugin_setup_page() {
        include_once('partials/dokko-chat-admin-display.php');
    }

    public function insert_chat_script() {
        check_ajax_referer('insert_chat_script_nonce', 'nonce');
        $options = get_option('dokko_chat_settings');
        if (empty($options['tenant_id'])) {
            wp_send_json_error('Tenant ID is not set.');
            return;
        }
        // Logic to insert the script into the footer
        add_action('wp_footer', array($this, 'add_chat_widget'));
        wp_send_json_success();
    }

    public function remove_chat_script() {
        check_ajax_referer('remove_chat_script_nonce', 'nonce');
        // Logic to remove the script from the footer
        remove_action('wp_footer', array($this, 'add_chat_widget'));
        wp_send_json_success();
    }

    public function add_chat_widget() {
        $options = get_option('dokko_chat_settings');
        echo '<style>
            :root {
                --dke-header-background: ' . esc_attr($options['header_background']) . ';
                --dke-header-text: ' . esc_attr($options['header_text']) . ';
            }
        </style>';
        echo '<script src="https://dokko-widget-bucket.s3.amazonaws.com/dokko-chat-widget-script.js" 
            id="dokko-chat-mono"
            data-tenant-id="' . esc_attr($options['tenant_id']) . '"
            data-document-source-ids="' . esc_attr($options['document_source_ids']) . '"
            data-permission-ids="' . esc_attr($options['permission_ids']) . '"
            data-include-sources="' . esc_attr($options['include_sources']) . '"
            data-require-login="' . esc_attr($options['require_login']) . '"
            data-start-minimized="' . esc_attr($options['start_minimized']) . '"
            data-name="' . esc_attr($options['name']) . '"
            data-welcome-message="' . esc_attr($options['welcome_message']) . '"
            data-footer-route="' . esc_attr($options['footer_route']) . '"
            data-footer-text="' . esc_attr($options['footer_text']) . '"
            data-chat-header-title="' . esc_attr($options['chat_header_title']) . '"
            data-embedded="' . esc_attr($options['embedded']) . '"
            onload="window.Dokko.init()">
        </script>';
    }

    public function validate_settings($input) {
        $valid = array();

        // Validate and sanitize each field
        $valid['id'] = sanitize_text_field($input['id'] ?? '');
        $valid['tenant_id'] = sanitize_text_field($input['tenant_id'] ?? '');
        $valid['document_source_ids'] = sanitize_text_field($input['document_source_ids'] ?? '');
        $valid['permission_ids'] = sanitize_text_field($input['permission_ids'] ?? '');
        $valid['include_sources'] = isset($input['include_sources']) ? 'true' : 'false';
        $valid['require_login'] = isset($input['require_login']) ? 'true' : 'false';
        $valid['start_minimized'] = isset($input['start_minimized']) ? 'true' : 'false';
        $valid['name'] = sanitize_text_field($input['name'] ?? '');
        $valid['welcome_message'] = sanitize_textarea_field($input['welcome_message'] ?? '');
        $valid['footer_route'] = sanitize_text_field($input['footer_route'] ?? '');
        $valid['footer_text'] = sanitize_text_field($input['footer_text'] ?? '');
        $valid['chat_header_title'] = sanitize_text_field($input['chat_header_title'] ?? '');
        $valid['embedded'] = isset($input['embedded']) && in_array($input['embedded'], ['widget', 'embedded']) ? $input['embedded'] : 'widget';
        $valid['visible'] = isset($input['visible']) ? 'true' : 'false';
        $valid['avatar_icon'] = sanitize_url($input['avatar_icon'] ?? '');

        // DKE CSS Variables
        $valid['dke_header_background'] = sanitize_hex_color($input['dke_header_background'] ?? '#CC314D');
        $valid['dke_header_text'] = sanitize_hex_color($input['dke_header_text'] ?? '#ffffff');
        $valid['dke_body_background'] = sanitize_hex_color($input['dke_body_background'] ?? '#14181e');
        $valid['dke_message_outgoing_background'] = sanitize_hex_color($input['dke_message_outgoing_background'] ?? '#CC314D');
        $valid['dke_message_outgoing_text'] = sanitize_hex_color($input['dke_message_outgoing_text'] ?? '#ffffff');
        $valid['dke_message_incoming_background'] = sanitize_hex_color($input['dke_message_incoming_background'] ?? '#373737');
        $valid['dke_message_incoming_text'] = sanitize_hex_color($input['dke_message_incoming_text'] ?? '#ffffff');
        $valid['dke_message_info_text'] = sanitize_hex_color($input['dke_message_info_text'] ?? '#f0f0f0');
        $valid['dke_message_avatar_background'] = sanitize_hex_color($input['dke_message_avatar_background'] ?? '#373737');
        $valid['dke_message_avatar_border'] = sanitize_hex_color($input['dke_message_avatar_border'] ?? '#373737');
        $valid['dke_avatar_logo'] = sanitize_hex_color($input['dke_avatar_logo'] ?? '#CC314D');
        $valid['dke_chat_input_background'] = sanitize_hex_color($input['dke_chat_input_background'] ?? '#373737');
        $valid['dke_chat_input_border'] = sanitize_hex_color($input['dke_chat_input_border'] ?? '#373737');
        $valid['dke_chat_input_text'] = sanitize_hex_color($input['dke_chat_input_text'] ?? '#ffffff');
        $valid['dke_chat_input_button'] = sanitize_hex_color($input['dke_chat_input_button'] ?? '#CC314D');

        // Dokko Search Colors
        $valid['dks_font_family'] = sanitize_text_field($input['dks_font_family'] ?? 'ui-sans-serif, system-ui, sans-serif, \'Apple Color Emoji\', \'Segoe UI Emoji\', \'Segoe UI Symbol\', \'Noto Color Emoji\'');
        $valid['dks_color_loader'] = sanitize_hex_color($input['dks_color_loader'] ?? '#CC314D');
        $valid['dks_color_input_background'] = sanitize_hex_color($input['dks_color_input_background'] ?? '#FFFFFF');
        $valid['dks_color_btn_background'] = sanitize_hex_color($input['dks_color_btn_background'] ?? '#373737');
        $valid['dks_color_btn_text'] = sanitize_hex_color($input['dks_color_btn_text'] ?? '#ffffff');
        $valid['dks_color_btn_border'] = sanitize_hex_color($input['dks_color_btn_border'] ?? '#373737');
        $valid['dks_font_size'] = sanitize_text_field($input['dks_font_size'] ?? '16px');
        $valid['dks_font_weight'] = sanitize_text_field($input['dks_font_weight'] ?? '400');
        $valid['dks_color_active'] = sanitize_hex_color($input['dks_color_active'] ?? '#373737');
        $valid['dks_color_inactive'] = sanitize_hex_color($input['dks_color_inactive'] ?? '#9CA3AF');
        $valid['dks_icon_size'] = sanitize_text_field($input['dks_icon_size'] ?? '16px');

        // DKW Widget Colors
        $valid['dkw_header_background'] = sanitize_hex_color($input['dkw_header_background'] ?? '#CC314D');
        $valid['dkw_header_text'] = sanitize_hex_color($input['dkw_header_text'] ?? '#ffffff');
        $valid['dkw_header_bg_image'] = sanitize_text_field($input['dkw_header_bg_image'] ?? '');
        $valid['dkw_header_logo'] = sanitize_text_field($input['dkw_header_logo'] ?? '');
        $valid['dkw_body_background'] = sanitize_hex_color($input['dkw_body_background'] ?? '#14181e');
        $valid['dkw_message_outgoing_background'] = sanitize_hex_color($input['dkw_message_outgoing_background'] ?? '#CC314D');
        $valid['dkw_message_outgoing_text'] = sanitize_hex_color($input['dkw_message_outgoing_text'] ?? '#ffffff');
        $valid['dkw_message_incoming_background'] = sanitize_hex_color($input['dkw_message_incoming_background'] ?? '#373737');
        $valid['dkw_message_incoming_text'] = sanitize_hex_color($input['dkw_message_incoming_text'] ?? '#ffffff');
        $valid['dkw_message_info_text'] = sanitize_hex_color($input['dkw_message_info_text'] ?? '#f0f0f0');
        $valid['dkw_message_avatar_background'] = sanitize_hex_color($input['dkw_message_avatar_background'] ?? '#373737');
        $valid['dkw_message_avatar_border'] = sanitize_hex_color($input['dkw_message_avatar_border'] ?? '#373737');
        $valid['dkw_avatar_logo'] = sanitize_hex_color($input['dkw_avatar_logo'] ?? '#CC314D');
        $valid['dkw_chat_input_background'] = sanitize_hex_color($input['dkw_chat_input_background'] ?? '#373737');
        $valid['dkw_chat_input_border'] = sanitize_hex_color($input['dkw_chat_input_border'] ?? '#373737');
        $valid['dkw_chat_input_text'] = sanitize_hex_color($input['dkw_chat_input_text'] ?? '#ffffff');
        $valid['dkw_chat_input_button'] = sanitize_hex_color($input['dkw_chat_input_button'] ?? '#CC314D');

        return $valid;
    }

    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        if (isset($_GET['settings-updated'])) {
            add_settings_error('dokko_chat_messages', 'dokko_chat_message', __('Settings Saved', 'dokko-chat'), 'updated');
        }

        settings_errors('dokko_chat_messages');
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields($this->plugin_name);
                do_settings_sections($this->plugin_name);
                submit_button('Save Settings');
                ?>
            </form>
        </div>
        <?php
    }

    public function visible_callback() {
        $options = get_option($this->plugin_name);
        $visible = isset($options['visible']) ? $options['visible'] : 'false';
        echo '<input type="checkbox" id="' . $this->plugin_name . '-visible" name="' . $this->plugin_name . '[visible]" value="true" ' . checked('true', $visible, false) . '>';
    }

    public function id_callback() {
        $options = get_option($this->plugin_name);
        $id = isset($options['id']) ? $options['id'] : '';
        echo '<input type="text" id="' . $this->plugin_name . '-id" name="' . $this->plugin_name . '[id]" value="' . esc_attr($id) . '" class="regular-text">';
    }

    public function tenant_id_callback() {
        $options = get_option($this->plugin_name);
        $tenant_id = isset($options['tenant_id']) ? $options['tenant_id'] : '';
        echo '<input type="text" id="' . $this->plugin_name . '-tenant-id" name="' . $this->plugin_name . '[tenant_id]" value="' . esc_attr($tenant_id) . '" class="regular-text">';
    }

    public function document_source_ids_callback() {
        $options = get_option($this->plugin_name);
        $document_source_ids = isset($options['document_source_ids']) ? $options['document_source_ids'] : '';
        echo '<input type="text" id="' . $this->plugin_name . '-document-source-ids" name="' . $this->plugin_name . '[document_source_ids]" value="' . esc_attr($document_source_ids) . '" class="regular-text">';
    }

    public function permission_ids_callback() {
        $options = get_option($this->plugin_name);
        $permission_ids = isset($options['permission_ids']) ? $options['permission_ids'] : '';
        echo '<input type="text" id="' . $this->plugin_name . '-permission-ids" name="' . $this->plugin_name . '[permission_ids]" value="' . esc_attr($permission_ids) . '" class="regular-text">';
    }

    public function include_sources_callback() {
        $options = get_option($this->plugin_name);
        $include_sources = isset($options['include_sources']) ? $options['include_sources'] : 'false';
        echo '<input type="checkbox" id="' . $this->plugin_name . '-include-sources" name="' . $this->plugin_name . '[include_sources]" value="true" ' . checked('true', $include_sources, false) . '>';
    }

    public function require_login_callback() {
        $options = get_option($this->plugin_name);
        $require_login = isset($options['require_login']) ? $options['require_login'] : 'false';
        echo '<input type="checkbox" id="' . $this->plugin_name . '-require-login" name="' . $this->plugin_name . '[require_login]" value="true" ' . checked('true', $require_login, false) . '>';
    }

    public function start_minimized_callback() {
        $options = get_option($this->plugin_name);
        $start_minimized = isset($options['start_minimized']) ? $options['start_minimized'] : 'false';
        echo '<input type="checkbox" id="' . $this->plugin_name . '-start-minimized" name="' . $this->plugin_name . '[start_minimized]" value="true" ' . checked('true', $start_minimized, false) . '>';
    }

    public function name_callback() {
        $options = get_option($this->plugin_name);
        $name = isset($options['name']) ? $options['name'] : '';
        echo '<input type="text" id="' . $this->plugin_name . '-name" name="' . $this->plugin_name . '[name]" value="' . esc_attr($name) . '" class="regular-text">';
    }

    public function welcome_message_callback() {
        $options = get_option($this->plugin_name);
        $welcome_message = isset($options['welcome_message']) ? $options['welcome_message'] : '';
        echo '<textarea id="' . $this->plugin_name . '-welcome-message" name="' . $this->plugin_name . '[welcome_message]" rows="5" cols="50">' . esc_textarea($welcome_message) . '</textarea>';
    }

    public function footer_route_callback() {
        $options = get_option($this->plugin_name);
        $footer_route = isset($options['footer_route']) ? $options['footer_route'] : '';
        echo '<input type="text" id="' . $this->plugin_name . '-footer-route" name="' . $this->plugin_name . '[footer_route]" value="' . esc_attr($footer_route) . '" class="regular-text">';
    }

    public function footer_text_callback() {
        $options = get_option($this->plugin_name);
        $footer_text = isset($options['footer_text']) ? $options['footer_text'] : '';
        echo '<input type="text" id="' . $this->plugin_name . '-footer-text" name="' . $this->plugin_name . '[footer_text]" value="' . esc_attr($footer_text) . '" class="regular-text">';
    }

    public function chat_header_title_callback() {
        $options = get_option($this->plugin_name);
        $chat_header_title = isset($options['chat_header_title']) ? $options['chat_header_title'] : '';
        echo '<input type="text" id="' . $this->plugin_name . '-chat-header-title" name="' . $this->plugin_name . '[chat_header_title]" value="' . esc_attr($chat_header_title) . '" class="regular-text">';
    }

    public function embedded_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['embedded']) && !empty($options['embedded']) ? $options['embedded'] : 'widget';
        ?>
        <div class="dokko-segmented-control">
            <input type="radio" id="embedded-widget" name="<?php echo $this->plugin_name; ?>[embedded]" value="widget" <?php checked($value, 'widget'); ?> checked>
            <label for="embedded-widget">Widget</label>
            
            <input type="radio" id="embedded-embedded" name="<?php echo $this->plugin_name; ?>[embedded]" value="embedded" <?php checked($value, 'embedded'); ?>>
            <label for="embedded-embedded">Embedded</label>
        </div>
        <style>
            .dokko-segmented-control {
                display: inline-flex;
                background: #f0f0f1;
                border-radius: 4px;
                padding: 2px;
                margin: 5px 0;
            }
            .dokko-segmented-control input[type="radio"] {
                display: none;
            }
            .dokko-segmented-control label {
                padding: 5px 15px;
                cursor: pointer;
                border-radius: 3px;
                transition: all 0.2s;
            }
            .dokko-segmented-control input[type="radio"]:checked + label {
                background: #2271b1;
                color: white;
            }
        </style>
        <?php
    }

    public function avatar_icon_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['avatar_icon']) ? $options['avatar_icon'] : '';
        
        echo '<div class="dokko-image-upload-wrapper">';
        echo '<input type="hidden" id="' . $this->plugin_name . '-avatar-icon" name="' . $this->plugin_name . '[avatar_icon]" value="' . esc_url($value) . '" class="dokko-image-field">';
        echo '<button type="button" class="button dokko-upload-button" data-field="avatar_icon" data-title="Select Avatar Icon Image">Upload Image</button>';
        
        if (!empty($value)) {
            echo '<div class="dokko-image-preview" >';
            echo '<img src="' . esc_url($value) . '"/>';
            echo '<button type="button" class="dokko-remove-button" data-field="avatar_icon" title="Remove image">✕</button>';
            echo '</div>';
        }
        echo '</div>';
    }

    public function dokko_app_shortcode_callback() {
        $options = get_option($this->plugin_name);
        $is_embedded = isset($options['embedded']) && $options['embedded'] === 'embedded';
        
        // Create a wrapper to be targeted by JavaScript
        echo '<div class="dokko-shortcode-wrapper">';
        
        if (!$is_embedded) {
            echo '<p style="color: #666;" class="dokko-shortcode-message">' . __('Shortcode is only available in Embedded mode. Please select "Embedded" above.', 'dokko-chat') . '</p>';
        }
        ?>
        <div class="dokko-shortcode-display" style="<?php echo $is_embedded ? '' : 'display: none;'; ?>">
        <p><strong><?php _e('Copy this shortcode and paste it into your page or post editor:', 'dokko-chat'); ?></strong></p>
        <div style="background: #f5f5f5; padding: 10px; border-left: 4px solid #2271b1; margin: 10px 0; border-radius: 4px;">
            <code id="dokko-shortcode-code" style="font-family: monospace; font-size: 14px; user-select: all;">[dokko_app]</code>
        </div>
        <p style="color: #666; font-size: 13px;"><?php _e('This shortcode will display the embedded chat on the page where you place it.', 'dokko-chat'); ?></p>
        </div>
        <?php
        ?>
        <script>
            jQuery(document).ready(function($) {
                function toggleShortcodeVisibility() {
                    var mode = $('input[name="<?php echo $this->plugin_name; ?>[embedded]"]:checked').val();
                    var wrapper = $('.dokko-shortcode-wrapper');
                    
                    if (mode === 'embedded') {
                        wrapper.find('.dokko-shortcode-message').hide();
                        wrapper.find('.dokko-shortcode-display').show();
                    } else {
                        wrapper.find('.dokko-shortcode-message').show();
                        wrapper.find('.dokko-shortcode-display').hide();
                    }
                }
                
                // Initial state
                toggleShortcodeVisibility();
                
                // Update on change
                $('input[name="<?php echo $this->plugin_name; ?>[embedded]"]').on('change', function() {
                    toggleShortcodeVisibility();
                });
            });
        </script>
        <?php
        echo '</div>';
    }

    // CSS Variables Callbacks
    public function dke_header_background_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dke_header_background']) ? $options['dke_header_background'] : '#CC314D';
        echo '<input type="color" id="' . $this->plugin_name . '-dke-header-background" name="' . $this->plugin_name . '[dke_header_background]" value="' . esc_attr($value) . '">';
    }

    public function dke_header_text_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dke_header_text']) ? $options['dke_header_text'] : '#ffffff';
        echo '<input type="color" id="' . $this->plugin_name . '-dke-header-text" name="' . $this->plugin_name . '[dke_header_text]" value="' . esc_attr($value) . '">';
    }

    public function dke_body_background_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dke_body_background']) ? $options['dke_body_background'] : '#14181e';
        echo '<input type="color" id="' . $this->plugin_name . '-dke-body-background" name="' . $this->plugin_name . '[dke_body_background]" value="' . esc_attr($value) . '">';
    }

    public function dke_message_outgoing_background_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dke_message_outgoing_background']) ? $options['dke_message_outgoing_background'] : '#CC314D';
        echo '<input type="color" id="' . $this->plugin_name . '-dke-message-outgoing-background" name="' . $this->plugin_name . '[dke_message_outgoing_background]" value="' . esc_attr($value) . '">';
    }

    public function dke_message_outgoing_text_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dke_message_outgoing_text']) ? $options['dke_message_outgoing_text'] : '#ffffff';
        echo '<input type="color" id="' . $this->plugin_name . '-dke-message-outgoing-text" name="' . $this->plugin_name . '[dke_message_outgoing_text]" value="' . esc_attr($value) . '">';
    }

    public function dke_message_incoming_background_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dke_message_incoming_background']) ? $options['dke_message_incoming_background'] : '#373737';
        echo '<input type="color" id="' . $this->plugin_name . '-dke-message-incoming-background" name="' . $this->plugin_name . '[dke_message_incoming_background]" value="' . esc_attr($value) . '">';
    }

    public function dke_message_incoming_text_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dke_message_incoming_text']) ? $options['dke_message_incoming_text'] : '#ffffff';
        echo '<input type="color" id="' . $this->plugin_name . '-dke-message-incoming-text" name="' . $this->plugin_name . '[dke_message_incoming_text]" value="' . esc_attr($value) . '">';
    }

    public function dke_message_info_text_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dke_message_info_text']) ? $options['dke_message_info_text'] : '#f0f0f0';
        echo '<input type="color" id="' . $this->plugin_name . '-dke-message-info-text" name="' . $this->plugin_name . '[dke_message_info_text]" value="' . esc_attr($value) . '">';
    }

    public function dke_message_avatar_background_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dke_message_avatar_background']) ? $options['dke_message_avatar_background'] : '#373737';
        echo '<input type="color" id="' . $this->plugin_name . '-dke-message-avatar-background" name="' . $this->plugin_name . '[dke_message_avatar_background]" value="' . esc_attr($value) . '">';
    }

    public function dke_message_avatar_border_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dke_message_avatar_border']) ? $options['dke_message_avatar_border'] : '#373737';
        echo '<input type="color" id="' . $this->plugin_name . '-dke-message-avatar-border" name="' . $this->plugin_name . '[dke_message_avatar_border]" value="' . esc_attr($value) . '">';
    }

    public function dke_avatar_logo_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dke_avatar_logo']) ? $options['dke_avatar_logo'] : '#CC314D';
        echo '<input type="color" id="' . $this->plugin_name . '-dke-avatar-logo" name="' . $this->plugin_name . '[dke_avatar_logo]" value="' . esc_attr($value) . '">';
    }

    public function dke_chat_input_background_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dke_chat_input_background']) ? $options['dke_chat_input_background'] : '#373737';
        echo '<input type="color" id="' . $this->plugin_name . '-dke-chat-input-background" name="' . $this->plugin_name . '[dke_chat_input_background]" value="' . esc_attr($value) . '">';
    }

    public function dke_chat_input_border_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dke_chat_input_border']) ? $options['dke_chat_input_border'] : '#373737';
        echo '<input type="color" id="' . $this->plugin_name . '-dke-chat-input-border" name="' . $this->plugin_name . '[dke_chat_input_border]" value="' . esc_attr($value) . '">';
    }

    public function dke_chat_input_text_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dke_chat_input_text']) ? $options['dke_chat_input_text'] : '#ffffff';
        echo '<input type="color" id="' . $this->plugin_name . '-dke-chat-input-text" name="' . $this->plugin_name . '[dke_chat_input_text]" value="' . esc_attr($value) . '">';
    }

    public function dke_chat_input_button_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dke_chat_input_button']) ? $options['dke_chat_input_button'] : '#CC314D';
        echo '<input type="color" id="' . $this->plugin_name . '-dke-chat-input-button" name="' . $this->plugin_name . '[dke_chat_input_button]" value="' . esc_attr($value) . '">';
    }

    // Widget Colors Callbacks
    public function dkw_header_background_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dkw_header_background']) ? $options['dkw_header_background'] : '#CC314D';
        echo '<input type="color" id="' . $this->plugin_name . '-dkw-header-background" name="' . $this->plugin_name . '[dkw_header_background]" value="' . esc_attr($value) . '">';
    }

    public function dkw_header_text_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dkw_header_text']) ? $options['dkw_header_text'] : '#ffffff';
        echo '<input type="color" id="' . $this->plugin_name . '-dkw-header-text" name="' . $this->plugin_name . '[dkw_header_text]" value="' . esc_attr($value) . '">';
    }

    public function dkw_header_bg_image_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dkw_header_bg_image']) ? $options['dkw_header_bg_image'] : '';
        
        echo '<div class="dokko-image-upload-wrapper">';
        echo '<input type="hidden" id="' . $this->plugin_name . '-dkw-header-bg-image" name="' . $this->plugin_name . '[dkw_header_bg_image]" value="' . esc_url($value) . '" class="dokko-image-field">';
        echo '<button type="button" class="button dokko-upload-button" data-field="dkw_header_bg_image" data-title="Select Header Background Image">Upload Image</button>';
        
        if (!empty($value)) {
            echo '<div class="dokko-image-preview">';
            echo '<img src="' . esc_url($value) . '">';
            echo '<button type="button" class="dokko-remove-button" data-field="dkw_header_bg_image" title="Remove image">✕</button>';
            echo '</div>';
        }
        echo '</div>';
    }

    public function dkw_header_logo_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dkw_header_logo']) ? $options['dkw_header_logo'] : '';
        
        echo '<div class="dokko-image-upload-wrapper">';
        echo '<input type="hidden" id="' . $this->plugin_name . '-dkw-header-logo" name="' . $this->plugin_name . '[dkw_header_logo]" value="' . esc_url($value) . '" class="dokko-image-field">';
        echo '<button type="button" class="button dokko-upload-button" data-field="dkw_header_logo" data-title="Select Header Logo Image">Upload Image</button>';
        
        if (!empty($value)) {
            echo '<div class="dokko-image-preview">';
            echo '<img src="' . esc_url($value) . '">';
            echo '<button type="button" class="dokko-remove-button" data-field="dkw_header_logo" title="Remove image" >✕</button>';
            echo '</div>';
        }
        echo '</div>';
    }

    public function dkw_body_background_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dkw_body_background']) ? $options['dkw_body_background'] : '#14181e';
        echo '<input type="color" id="' . $this->plugin_name . '-dkw-body-background" name="' . $this->plugin_name . '[dkw_body_background]" value="' . esc_attr($value) . '">';
    }

    public function dkw_message_outgoing_background_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dkw_message_outgoing_background']) ? $options['dkw_message_outgoing_background'] : '#CC314D';
        echo '<input type="color" id="' . $this->plugin_name . '-dkw-message-outgoing-background" name="' . $this->plugin_name . '[dkw_message_outgoing_background]" value="' . esc_attr($value) . '">';
    }

    public function dkw_message_outgoing_text_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dkw_message_outgoing_text']) ? $options['dkw_message_outgoing_text'] : '#ffffff';
        echo '<input type="color" id="' . $this->plugin_name . '-dkw-message-outgoing-text" name="' . $this->plugin_name . '[dkw_message_outgoing_text]" value="' . esc_attr($value) . '">';
    }

    public function dkw_message_incoming_background_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dkw_message_incoming_background']) ? $options['dkw_message_incoming_background'] : '#373737';
        echo '<input type="color" id="' . $this->plugin_name . '-dkw-message-incoming-background" name="' . $this->plugin_name . '[dkw_message_incoming_background]" value="' . esc_attr($value) . '">';
    }

    public function dkw_message_incoming_text_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dkw_message_incoming_text']) ? $options['dkw_message_incoming_text'] : '#ffffff';
        echo '<input type="color" id="' . $this->plugin_name . '-dkw-message-incoming-text" name="' . $this->plugin_name . '[dkw_message_incoming_text]" value="' . esc_attr($value) . '">';
    }

    public function dkw_message_info_text_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dkw_message_info_text']) ? $options['dkw_message_info_text'] : '#f0f0f0';
        echo '<input type="color" id="' . $this->plugin_name . '-dkw-message-info-text" name="' . $this->plugin_name . '[dkw_message_info_text]" value="' . esc_attr($value) . '">';
    }

    public function dkw_message_avatar_background_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dkw_message_avatar_background']) ? $options['dkw_message_avatar_background'] : '#373737';
        echo '<input type="color" id="' . $this->plugin_name . '-dkw-message-avatar-background" name="' . $this->plugin_name . '[dkw_message_avatar_background]" value="' . esc_attr($value) . '">';
    }

    public function dkw_message_avatar_border_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dkw_message_avatar_border']) ? $options['dkw_message_avatar_border'] : '#373737';
        echo '<input type="color" id="' . $this->plugin_name . '-dkw-message-avatar-border" name="' . $this->plugin_name . '[dkw_message_avatar_border]" value="' . esc_attr($value) . '">';
    }

    public function dkw_avatar_logo_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dkw_avatar_logo']) ? $options['dkw_avatar_logo'] : '#CC314D';
        echo '<input type="color" id="' . $this->plugin_name . '-dkw-avatar-logo" name="' . $this->plugin_name . '[dkw_avatar_logo]" value="' . esc_attr($value) . '">';
    }

    public function dkw_chat_input_background_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dkw_chat_input_background']) ? $options['dkw_chat_input_background'] : '#373737';
        echo '<input type="color" id="' . $this->plugin_name . '-dkw-chat-input-background" name="' . $this->plugin_name . '[dkw_chat_input_background]" value="' . esc_attr($value) . '">';
    }

    public function dkw_chat_input_border_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dkw_chat_input_border']) ? $options['dkw_chat_input_border'] : '#373737';
        echo '<input type="color" id="' . $this->plugin_name . '-dkw-chat-input-border" name="' . $this->plugin_name . '[dkw_chat_input_border]" value="' . esc_attr($value) . '">';
    }

    public function dkw_chat_input_text_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dkw_chat_input_text']) ? $options['dkw_chat_input_text'] : '#ffffff';
        echo '<input type="color" id="' . $this->plugin_name . '-dkw-chat-input-text" name="' . $this->plugin_name . '[dkw_chat_input_text]" value="' . esc_attr($value) . '">';
    }

    public function dkw_chat_input_button_callback() {
        $options = get_option($this->plugin_name);
        $value = isset($options['dkw_chat_input_button']) ? $options['dkw_chat_input_button'] : '#CC314D';
        echo '<input type="color" id="' . $this->plugin_name . '-dkw-chat-input-button" name="' . $this->plugin_name . '[dkw_chat_input_button]" value="' . esc_attr($value) . '">';
    }

    public function add_display_logic_script() {
        ?>
        <script>
        jQuery(document).ready(function($) {
            function updateSettingsVisibility() {
                var mode = $('input[name="<?php echo $this->plugin_name; ?>[embedded]"]:checked').val();
                if (mode === 'embedded') {
                    $('.dke-settings').show();
                    $('.dkw-settings').hide();
                } else {
                    $('.dke-settings').hide();
                    $('.dkw-settings').show();
                }
            }

            // Initial state
            updateSettingsVisibility();

            // Update on change
            $('input[name="<?php echo $this->plugin_name; ?>[embedded]"]').on('change', updateSettingsVisibility);
        });
        </script>
        <?php
    }

    /**
     * Allow SVG uploads in WordPress media library
     */
    public function allow_svg_uploads($mimes) {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }

    /**
     * Check and validate SVG file type
     */
    public function check_svg_filetype($checked, $file, $filename, $mimes) {
        if (!$checked['type'] && strpos($filename, '.svg') !== false) {
            $checked['type'] = 'image/svg+xml';
            $checked['ext'] = 'svg';
        }
        return $checked;
    }
} 