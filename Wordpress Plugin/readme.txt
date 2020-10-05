=== monday.com integration ===
Contributors: twimbit, siddhantd, amanintech
Tags: monday.com, automation, integration
Requires at least: 3.0.0
Tested up to: 5.5.1
Requires PHP: 7.0.0
License: MIT
License URI: https://github.com/twimbit/Monday-Wordpress-Integration/blob/master/LICENSE

Integrate and sync WordPress with monday.com

== Description ==
This integration lets users synchronize there WordPress site with Monday to create efficient workflows and automation. Monday users can synchronize posts, pages, users, comments, and taxonomies from WordPress to Monday and vice versa. This opens a new window of automation opportunity for publishers, content creators, or simply anyone using WordPress to create custom workflows and connect various other platforms without any additional plugin installation on WordPress.

How does it works?
The integration uses Monday V2 API with Authorization, custom triggers and actions, and WordPress Rest API. Since WordPress users can have any site, so to have a standard backend app, we have created a middleware application between Monday and WordPress that runs all the transaction on Standard URL’s.

Note- For now whenever you add an integration, we automatically create required columns and sync them with wordpress fields ( This is going to be replaced with integration mapping).

Data we store
To ensure consistent authorization we store a copy of Monday Account Id, user ID along with the board id on which integration is added. We also store the subscirption id which is used for removing integrations. To direct the access to Users wordpress and authenticate it, we store copy of monday wordpress integration plugin API key and API secret. We dont share any information with any third party. We don\'t store your content or transaction as they directly performed between wordpress and monday.

Vulnerability disclosure 🧑🏼‍💻
Monday-wordpress integration is the open source project. We welcome security research on this open source project .

== Installation ==
Steps for Installation

1. Download the latest release Wordpress-Plugin.zip .
2. Install the plugin in wordpress by uploading the downloaded folder
3. Go to monday.com and choose the desired integration from our app.
4. Go to wordpress settings > Monday Integration > Integration
5. Copy the API Key and API secret and paste it on the monday integration page and enter site URL without / . eg https://example.com
6. Authorize the app

== Screenshots ==
1. https://github.com/twimbit/Monday-Wordpress-Integration/blob/master/assets/Annotation2020-08-30114306.png?raw=true
2. https://github.com/twimbit/Monday-Wordpress-Integration/blob/master/assets/Annotation2020-08-31191308.png?raw=true
3. https://github.com/twimbit/Monday-Wordpress-Integration/blob/master/assets/Annotation2020-08-31190919.png?raw=true