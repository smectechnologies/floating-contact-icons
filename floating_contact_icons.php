<?php
/*
Plugin Name: Floating Contact Icons
Description: Adds floating WhatsApp, Email, or Phone icons on all pages and posts.
Version: 1.2.4
Author: Abhijith PS
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Add Admin Menu
function floating_contact_icons_menu() {
    add_menu_page('Floating Icons', 'Floating Icons', 'manage_options', 'floating-icons-settings', 'floating_contact_icons_settings_page');
}
add_action('admin_menu', 'floating_contact_icons_menu');

// Register Plugin Settings
function floating_contact_icons_register_settings() {
    register_setting('floating_icons_group', 'whatsapp_link');
    register_setting('floating_icons_group', 'email_address');
    register_setting('floating_icons_group', 'phone_number');
    register_setting('floating_icons_group', 'icon_position');
}
add_action('admin_init', 'floating_contact_icons_register_settings');

// Settings Page
function floating_contact_icons_settings_page() {
    ?>
    <div class="wrap">
        <h1>Floating Contact Icons Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('floating_icons_group'); ?>
            <?php do_settings_sections('floating_icons_group'); ?>
            <table class="form-table">
                <tr>
                    <th><label for="whatsapp_link">WhatsApp Link</label></th>
                    <td><input type="text" id="whatsapp_link" name="whatsapp_link" value="<?php echo esc_attr(get_option('whatsapp_link')); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="email_address">Email Address</label></th>
                    <td><input type="text" id="email_address" name="email_address" value="<?php echo esc_attr(get_option('email_address')); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="phone_number">Phone Number</label></th>
                    <td><input type="text" id="phone_number" name="phone_number" value="<?php echo esc_attr(get_option('phone_number')); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="icon_position">Icon Position</label></th>
                    <td>
                        <select id="icon_position" name="icon_position">
                            <option value="right" <?php selected(get_option('icon_position'), 'right'); ?>>Right</option>
                            <option value="left" <?php selected(get_option('icon_position'), 'left'); ?>>Left</option>
                        </select>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Add Floating Icons to Frontend
function floating_contact_icons_display() {
    $whatsapp = get_option('whatsapp_link');
    $email = get_option('email_address');
    $phone = get_option('phone_number');
    $position = get_option('icon_position', 'right');
    
    if (!$whatsapp && !$email && !$phone) return;
    
    echo '<style>
        .floating-icons {
            position: fixed;
            bottom: 90px;
            '. ($position == 'left' ? 'left' : 'right') .': 60px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .floating-icons a {
            background: #25D366;
            color: white;
            padding: 8px;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 18px;
            transition: transform 0.3s ease-in-out;
            position: relative;
        }
        .floating-icons a:hover {
            transform: scale(1.2);
        }
        .floating-icons a::after {
            content: attr(data-message);
            position: absolute;
            bottom: 50px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            display: none;
            white-space: nowrap;
        }
        .floating-icons a:hover::after {
            display: block;
        }
        .floating-icons a.email {
            background: #EA4335;
        }
        .floating-icons a.phone {
            background: #007BFF;
        }
    </style>';
    
    echo '<div class="floating-icons">';
    if ($whatsapp) echo '<a href="' . esc_url($whatsapp) . '" target="_blank" class="whatsapp" data-message="Chat on WhatsApp"><img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" width="24"></a>';
    if ($email) echo '<a href="mailto:' . esc_attr($email) . '" class="email" data-message="Send an Email">&#9993;</a>';
    if ($phone) echo '<a href="tel:' . esc_attr($phone) . '" class="phone" data-message="Call Now">&#9743;</a>';
    echo '</div>';
}
add_action('wp_footer', 'floating_contact_icons_display');
