# Monday wordpress integration

[![Twimbit](https://img.shields.io/badge/Powered%20by%20%7C-Twimbit-ef6d6c)](https://twimbit.com/?)

[![Build Status](https://travis-ci.org/joemccann/dillinger.svg?branch=master)](https://github.com/twimbit/wordpress-monday) [![Project demo](https://img.shields.io/youtube/views/OlHK9WsZCOY?style=social)](https://www.youtube.com/watch?v=OlHK9WsZCOY)

![Project demo](https://img.shields.io/badge/Inspiration-Monday%20and%20Wordpress%20integration-0074a2?style=for-the-badge&logo=appveyor)
In our organization, we maintain a content record on the monday.com board, and the content is published in WordPress. So for that, all users had to write their contents and all details in WordPress and Monday.com manually and because of that the work of content writers became redundant. 
So, to make the work automated we came up with this integration which will make content writers work easier.

### Introduction

This integration lets users synchronize there WordPress site with Monday to create efficient workflows and automation. Monday users can synchronize posts, pages, users, comments, and taxonomies from WordPress to Monday and vice versa. This opens a new window of automation opportunity for publishers, content creators, or simply anyone using WordPress to create custom workflows and connect various other platforms without any additional plugin installation on WordPress.

Monday users would be able to do following Integrations -
1.  When a WordPress Post is created create a new item and sync future changes.
Additionally -
a. Assign user if exists.
b. Assign Tags and categories using tags fields.
c. Load Comments as updates to the posts.
d. Synchronize post status and Monday status
e. Add the preview post link.
2.  When a WordPress Page is created create a new item and sync future changes.

3. When a new Monday item is created, Create a Draft post in WordPress.

4. When an item status changes to something, change WordPress post status to something

5. When a new WordPress user is added, create a new item on Monday

6. When a new Comment is added create a new item.

>  ......  Many more integrations coming soon.

### Description

The integration uses Monday V2 API with Authorization, custom triggers and actions, and WordPress Rest API. Since WordPress users can have any site, so to have a standard backend app, we have created a middleware application between Monday and WordPress that runs all the transaction on Standard URL's. Price yet to be decided.


### Tech Stack

* [Graphql] - 
* [Wordpess] -


### Installation

Users will also need to install an additional WordPress plugin on their WordPress website and enter asked details during integration.

Install the plugin

| Plugin | README |
| ------ | ------ |
| GitHub | [plugins/github/README.md][https://github.com/twimbit/wordpress-monday] |

##### Steps for Installation

1. Login to your WordPress Dashboard.
2. In your WordPress Admin Menu, go to Plugins > Add New.
3. Click on Upload Plugin button found on top left corner of page.
![wordpress plugin upload](https://d33v4339jhl8k0.cloudfront.net/docs/assets/55e7171d90336027d77078f6/images/560fbc4dc69791523683f5b9/file-8Vw5RnLJe9.png)
4. Click on Browse (1), Select the .zip file of your plugin in your computer, and click Install Now (2) button.
![wordpress plugin upload](https://d33v4339jhl8k0.cloudfront.net/docs/assets/55e7171d90336027d77078f6/images/560fbc5d9033606ab4cbf274/file-9GqUY2GXg6.png)

5. At this point, the plugin is installed. You can click on Activate Plugin link to work with it.

Well done, you have managed to install and activate your WordPress plugin !




## Contributors

[![Open Source Love](https://badges.frapsoft.com/os/v2/open-source.svg?v=103)](https://github.com/twimbit/wordpress-monday)  
| GitHub Usernames                                             | Domain     |
| ------------------------------------------------------------ | ---------- |
| Aman Sharma [(@amanintech)](https://github.com/amanintech)   | Full Stack |
| Siddhant Kumar [(@siddhantdante)](https://github.com/siddhantdante) | Full Stack |
| Gaurav Kumar [(icon-gaurav)](https://github.com/icon-gaurav) | Full Stack |


[![forthebadge](https://forthebadge.com/images/badges/built-by-developers.svg)](https://github.com/twimbit)


