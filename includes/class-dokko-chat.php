<?php

class Dokko_Chat {
    protected $loader;
    protected $plugin_name;
    protected $version;

    public function __construct() {
        $this->version = DOKKO_CHAT_VERSION;
        $this->plugin_name = 'dokko-chat';
        
        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    private function load_dependencies() {
        require_once DOKKO_CHAT_PLUGIN_DIR . 'includes/class-dokko-chat-loader.php';
        require_once DOKKO_CHAT_PLUGIN_DIR . 'admin/class-dokko-chat-admin.php';
        require_once DOKKO_CHAT_PLUGIN_DIR . 'public/class-dokko-chat-public.php';

        $this->loader = new Dokko_Chat_Loader();
    }

    private function define_admin_hooks() {
        $plugin_admin = new Dokko_Chat_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_plugin_admin_menu');
        $this->loader->add_action('admin_init', $plugin_admin, 'register_settings');
    }

    private function define_public_hooks() {
        $plugin_public = new Dokko_Chat_Public($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('init', $plugin_public, 'register_shortcode');
        $this->loader->add_action('wp_footer', $plugin_public, 'add_chat_widget');
    }

    public function run() {
        $this->loader->run();
    }

    public function get_plugin_name() {
        return $this->plugin_name;
    }

    public function get_version() {
        return $this->version;
    }
} 