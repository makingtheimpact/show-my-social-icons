# Show My Social Icons

A WordPress plugin to easily display customizable social media icons anywhere on your website.

## Description

Show My Social Icons is a versatile WordPress plugin that allows you to add and manage social media icons on your website. It supports over 30 popular and alternative social media platforms, offering flexibility in display options.

### Key Features

- Display icons in the main menu, widget areas, or anywhere using shortcodes and Gutenberg blocks
- Choose between SVG and PNG icon formats
- Customize icon size, style, alignment, and colors
- Drag-and-drop ordering of icons
- Search functionality to quickly find platforms
- Individual icon display option

The social media platforms currently supported are: 
- Behance
- Bitchute
- CashApp
- CloutHub
- Digg
- Discord
- Facebook
- Fiverr
- Gab
- GitHub
- GiveSendGo
- Instagram
- LinkedIn
- Linktree
- Locals
- Mastodon
- Minds
- MySpace
- Odysee
- Parler
- Patreon
- PayPal
- Pinterest
- Public Square
- Quora
- Reddit
- Rokfin
- Rumble
- Snapchat
- Substack
- Telegram
- TikTok
- Truth Social
- Twitch
- X (formerly Twitter)
- Unite
- Venmo
- Vimeo
- VK
- WhatsApp
- YouTube
- Zelle

## Installation

1. Upload the `show-my-social-icons` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure the plugin settings under Settings -> Show My Social Icons

## Usage

You can use the widgets and blocks anywhere on your website. Simply add the widget to a sidebar or footer, and the blocks to a page.

You can also use the shortcodes to display the icons anywhere on your website. 

### Shortcodes

- `[show_my_social_icons]`: Displays all configured social media icons
- `[my_social_icon platform="facebook"]`: Displays a single social media icon

### Documentation

The plugin provides two Gutenberg blocks and two widgets:
- Social Media Icons (All) - Displays all configured social media icons
- Social Media Icons (Single) - Displays a single social media icon

SVG icons are only available in black, white, or custom color.
PNG icons are available in full color (proper logo branding color), black, or white. The logos are available in horizontal and square formats.

If you try to use incorrect settings, the icons may not display properly or use default settings.

## Troubleshooting

If you are having trouble with the icons not displaying, please check the following:

1. Ensure the plugin is activated and configured correctly.
2. Be sure that you have a valid URL for the social media platform in the platform settings.
3. Check the settings for the blocks and widgets to ensure they are set to the correct options.
4. Use the preview to ensure the icons are displaying correctly.
5. Use the shortcode to display a single icon and ensure it is displaying correctly.    

If the CSS styles are not being loaded, you can try the following:

1. Check the "Force Load Styles" option in the settings.
2. Clear the website cache and if necessary, adjust the settings. 
3. Clear the browser cache.

## Changelog

### 1.0.73
- Added shortcode preview feature.
- Fixed bugs and other issues with settings and displaying the icons.
- Removed redundant code and cleaned up the codebase.

### 1.0.72
- Added custom color to the icons.
- Fixed bug preventing SVG icons from displaying in the correct custom color.
- Fixed settings for blocks and widgets to support available icon options. 
- Removed SVG icons that were not displaying properly.
- Added margin settings to icon containers.
- Updated documentation and settings pages to reflect new features and changes.

### 1.0.71
- Added legacy widgets to display icons in the footer and sidebar sections.
- Added Gutenberg blocks to display icons in the page builder.
- Fixed alignment issue with the icons.

### 1.0.70
- Improved settings page to be easier to use.
- Added search functionality to the platforms list.
- Improved code structure, simplifying and cleaning up the code.
- Bug fixes and improvements to the code.

### 1.0.69
- Added Parler to the list of supported social media platforms.

### 1.0.0
- Initial release of the plugin.

## Upgrade Notice

### 1.0.71
- Upgrade to add widget to display icons in the footer.
- Upgrade to add Gutenberg block to display icons in the page builder.

### 1.0.70
- Improved settings page to be easier to use.
- Added search functionality to the platforms list.
- Improved code structure, simplifying and cleaning up the code.
- Bug fixes and improvements to the code.

### 1.0.69
- Upgrade to add Parler to the list of supported social media platforms.

### 1.0.0
- First release of the plugin, no upgrade notices.

## Additional Information
Any additional information like shortcodes, custom functions, or usage tips.

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
This plugin is open-sourced software licensed under the [GPLv2](https://www.gnu.org/licenses/gpl-2.0.html) license.