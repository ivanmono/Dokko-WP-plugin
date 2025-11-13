# Dokko Chat WordPress Plugin

A powerful WordPress plugin that integrates the Dokko Chat service into your WordPress website. Easily add an AI-powered chatbot to your site with flexible display modes and extensive customization options.

## Features

### 🎯 Display Modes
- **Widget Mode**: Floating chat widget that appears on all pages
- **Embedded Mode**: Integrate chat directly into specific pages or posts using shortcodes

### 🎨 Extensive Customization
Customize the appearance of your chat with CSS variables for:
- **Dokko Chat Engine (DKE)**: Full embedded chat styling
- **Dokko Chat Widget (DKW)**: Floating widget appearance  
- **Dokko Search (DKS)**: Search interface styling

### ⚙️ Configuration Options
- **Tenant & Document Settings**: Configure Dokko tenant ID and document sources
- **Permissions**: Control user access with permission IDs
- **Login Requirements**: Optionally require user login before chatting
- **Minimized State**: Start chat minimized or expanded
- **Custom Messages**: Add welcome messages and custom titles
- **Footer Customization**: Configure footer routes and text

### 🔤 Shortcode Support
```
[dokko_app]
```
Easy-to-use shortcode for embedding chat in specific pages or posts (Embedded Mode only).

## Requirements

- **WordPress** 5.0 or higher
- **PHP** 7.2 or higher
- Active Dokko Chat subscription with valid:
  - Tenant ID
  - API credentials

## Installation

1. Download the plugin files to your WordPress plugins directory:
   ```
   wp-content/plugins/Dokko-WP-plugin/
   ```

2. Activate the plugin through the WordPress admin panel:
   - Go to **Plugins** > **Installed Plugins**
   - Find "Dokko Chat"
   - Click **Activate**

3. Configure the plugin:
   - Go to **Dokko Chat** in the admin menu
   - Enter your Dokko credentials and settings
   - Click **Save Settings**

## Quick Start

### Step 1: Enable the Plugin
1. Navigate to **Dokko Chat** settings in WordPress admin
2. Check the **Visible** checkbox to enable the chat

### Step 2: Add Your Dokko Credentials
1. Enter your **Tenant ID** (required)
2. Enter your **ID** (Widget ID)
3. Configure other credentials as needed

### Step 3: Choose Display Mode

**For Widget Mode (Floating Chat):**
- Select **Widget** in the Display Mode section
- Customize Widget Mode (DKW) colors and styles
- Chat appears on all pages automatically

**For Embedded Mode (Pages/Posts):**
- Select **Embedded** in the Display Mode section
- Customize Embedded Mode (DKE) colors and styles
- Copy the `[dokko_app]` shortcode from the Shortcode section
- Paste the shortcode into any page or post content

### Step 4: Customize Appearance
1. Go to the **CSS Variables** section
2. Choose your display mode's color scheme
3. Customize header, body, messages, and input styling
4. Save your settings

## Configuration Guide

### General Settings

| Setting | Description |
|---------|-------------|
| **Visible** | Enable/disable the chat on your website |
| **ID** | Unique identifier for your chat widget |
| **Tenant ID** | Your Dokko tenant identifier (required) |
| **Document Source IDs** | IDs of documents for the AI to reference |
| **Permission IDs** | Restrict chat access to specific user groups |
| **Include Sources** | Show citation sources in chat responses |
| **Require Login** | Force users to login before accessing chat |
| **Start Minimized** | Widget starts in minimized state |
| **Name** | Display name for your chat |
| **Welcome Message** | Custom greeting message for users |
| **Footer Route** | API route for footer functionality |
| **Footer Text** | Custom footer text |
| **Chat Header Title** | Title shown at top of chat |
| **Display Mode** | Choose between Widget or Embedded mode |

### CSS Variables

#### Embedded Mode (DKE)
Customize the appearance when chat is embedded in pages:
- Header background & text color
- Body background color
- Message styling (outgoing & incoming)
- Avatar styling
- Chat input styling

#### Widget Mode (DKW)
Customize the floating widget appearance:
- Header background & text color
- Body background color
- Message styling
- Avatar styling
- Chat input styling

#### Search Mode (DKS)
Customize search interface styling:
- Font family and sizes
- Loader color
- Button and input colors
- Active/inactive state colors
- Icon sizing

## Shortcode Usage

### Basic Usage
```
[dokko_app]
```

Embeds the Dokko Chat in the current page or post.

### Requirements for Shortcode
- The plugin must be **Visible** (enabled)
- **Embedded** display mode must be selected
- **Tenant ID** must be configured
- Shortcode must be used in post/page content

### Example
```html
<h2>Get Help from Our AI Assistant</h2>
<p>Have a question? Chat with our AI-powered support bot below:</p>
[dokko_app]
```

## Display Modes Explained

### Widget Mode
- ✅ Appears on every page automatically
- ✅ Floating button/widget appearance
- ✅ Always accessible to visitors
- ❌ Cannot target specific pages
- **Best for**: Site-wide customer support, general availability

### Embedded Mode
- ✅ Appears only where you place the shortcode
- ✅ Can be added to specific pages
- ✅ Full-page or section integration
- ❌ Requires adding shortcode to each page
- **Best for**: Product pages, support pages, targeted engagement

## Troubleshooting

### Chat Not Appearing
**Check:**
1. Is the plugin **Visible** enabled in settings?
2. Is your **Tenant ID** configured and valid?
3. Are you using the correct display mode?
4. In Embedded mode, did you add the `[dokko_app]` shortcode?

### Styling Issues
1. Clear your browser cache
2. Check CSS variable colors are valid hex codes (e.g., `#CC314D`)
3. Verify no conflicting CSS from your theme
4. Use browser DevTools to inspect the chat element

### Shortcode Not Working
1. Verify you're in **Embedded** display mode
2. Ensure the plugin is **Visible** (enabled)
3. Confirm **Tenant ID** is set
4. Check the shortcode is `[dokko_app]` (no extra parameters)
5. Verify the page/post has been published

### Script Loading Issues
1. Check your Dokko credentials are correct
2. Verify your Dokko account is active
3. Ensure the AWS S3 bucket is accessible
4. Check browser console for error messages

## File Structure

```
Dokko-WP-plugin/
├── dokko-chat.php                 # Main plugin file
├── README.md                       # This file
├── admin/                          # Admin panel files
│   ├── class-dokko-chat-admin.php # Admin settings class
│   ├── css/                       # Admin styles
│   ├── js/                        # Admin scripts
│   └── partials/                  # Admin templates
├── includes/                      # Plugin core files
│   ├── class-dokko-chat.php      # Main plugin class
│   └── class-dokko-chat-loader.php# Hook loader
└── public/                        # Frontend files
    ├── class-dokko-chat-public.php# Frontend class
    ├── css/                       # Frontend styles
    └── js/                        # Frontend scripts
```

## Security

### Data Protection
- All settings are stored securely in WordPress options
- Configuration values are properly escaped before output
- Admin access requires `manage_options` capability

### Best Practices
- Only admin users can modify settings
- Sensitive data (Tenant IDs, API keys) are properly escaped
- User input is sanitized using WordPress sanitization functions

## Support & Documentation

### For Issues
1. Check the troubleshooting section above
2. Review WordPress admin error logs
3. Check browser console for JavaScript errors
4. Contact Dokko Chat support with your Tenant ID

### Learn More
- [Dokko Chat Documentation](https://dokko.ai)
- [WordPress Plugin Development](https://developer.wordpress.org/plugins/)

## Changelog

### Version 1.0.0 (November 2025)
- ✨ Initial release
- ✅ Widget and Embedded display modes
- ✅ Comprehensive CSS customization
- ✅ Shortcode support for embedded mode
- ✅ Full WordPress settings integration

## License

This plugin is licensed under the [GPL v2 or later](https://www.gnu.org/licenses/gpl-2.0.html).

## Contributing

We welcome contributions! To contribute:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Credits

**Dokko Chat WordPress Plugin** - Seamlessly integrate AI-powered chat into your WordPress site.

---

**Need help?** Visit our [support page](https://dokko.ai) or contact our team.
