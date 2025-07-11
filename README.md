# Adtribes Product Feed Country Updater

A WordPress plugin to update the `adt_country` meta_key value for product feeds with WooCommerce country codes.

## Description

This plugin provides a simple admin interface to update country information for product feeds. It integrates with WooCommerce to provide a comprehensive list of country codes and allows administrators to easily assign countries to their product feeds.

## Features

- ✅ **Simple Interface**: Clean, user-friendly admin page
- ✅ **WooCommerce Integration**: Uses WooCommerce country codes and names
- ✅ **All Post Statuses**: Works with published, draft, and private feeds
- ✅ **Security**: Includes nonce verification and input sanitization
- ✅ **Error Handling**: Proper validation and user feedback
- ✅ **Optimized Code**: Clean, efficient, and well-documented

## Requirements

- **WordPress**: 5.0 or higher
- **PHP**: 7.4 or higher
- **WooCommerce**: Required for country data
- **User Capability**: `manage_options` (Administrator level)

## Installation

1. **Upload the plugin files** to the `/wp-content/plugins/adt-country-updater/` directory
2. **Activate the plugin** through the 'Plugins' screen in WordPress
3. **Access the tool** via `Tools > Feed Country Updater` in your WordPress admin

## Usage

### Step-by-Step Guide

1. **Navigate to the plugin page**:
   - Go to WordPress Admin → Tools → Feed Country Updater

2. **Select a feed**:
   - Choose from the dropdown list of available product feeds
   - Feeds show their current status (Published, Draft, Private)

3. **Choose a country**:
   - Select from the comprehensive WooCommerce countries list
   - Countries display as "Country Name (CODE)"

4. **Update**:
   - Click "Update Country" to save the changes
   - Success/error messages will appear at the top of the page

### What it does

- Updates the `adt_country` meta_key value on the selected `adt_product_feed` post
- Uses WooCommerce's built-in country codes (e.g., US, CA, GB, AU)
- Works with all post statuses (publish, draft, private)

## Technical Details

### Database Operations

The plugin performs the following database operations:

1. **Retrieves feeds** from `wp_posts` table where `post_type = 'adt_product_feed'`
2. **Updates meta field** using WordPress `update_post_meta()` function
3. **Uses prepared statements** for security

### Code Structure

```
adt-country-updater/
├── adt-country-updater.php    # Main plugin file
└── README.md                  # This documentation
```

### Main Class Methods

- `__construct()` - Initialize hooks
- `check_woocommerce()` - Verify WooCommerce is active
- `add_admin_menu()` - Add admin menu page
- `pfp_feed_country_updater()` - Main admin page handler
- `handle_form_submission()` - Process form data
- `render_admin_page()` - Display admin interface
- `get_feeds()` - Retrieve product feeds
- `get_countries()` - Get WooCommerce countries
- `update_feed_country()` - Update country meta field

## Security Features

- **Nonce Verification**: Prevents CSRF attacks
- **Input Sanitization**: All user inputs are sanitized
- **Capability Checks**: Requires administrator privileges
- **Direct Access Prevention**: Blocks direct file access

## Error Handling

The plugin includes comprehensive error handling:

- **Missing WooCommerce**: Warning notice if WooCommerce is not active
- **Invalid Selections**: Error messages for empty form fields
- **Database Errors**: Graceful handling of database operation failures
- **Feed Not Found**: Clear error message if selected feed doesn't exist

## Changelog

### Version 1.0.0
- Initial release
- Basic country update functionality
- WooCommerce integration
- Security features implementation
- Admin interface creation

## Support

For support, feature requests, or bug reports, please contact the development team.

## License

This plugin is licensed under the GPL v2 or later.

```
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

## Developer Notes

### Extending the Plugin

To extend this plugin, you can:

1. **Add new meta fields** by modifying the `update_feed_country()` method
2. **Change the admin menu location** by updating the `add_admin_menu()` method
3. **Add custom validation** in the `handle_form_submission()` method
4. **Modify the interface** by updating the `render_admin_page()` method

### Hooks Available

The plugin uses standard WordPress hooks:
- `admin_menu` - For adding the admin page
- `admin_notices` - For displaying notices

### Database Tables Used

- `wp_posts` - For retrieving product feeds
- `wp_postmeta` - For storing/updating country meta data

---

**Version**: 1.0.0  
**Author**: Tanjir Al Mamun  
**Last Updated**: July 11, 2025 