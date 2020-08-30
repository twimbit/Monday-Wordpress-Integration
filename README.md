<img src="https://github.com/twimbit/monday-wordpress-integration/blob/master/Group%208%402x.png" object-fit="cover" width="100%">
<div align="center">
  <br>
  <h1> Monday-Wordpress Integration 👨‍💻</h1>
</div>
<br>

[![Twimbit](https://img.shields.io/badge/Powered%20by%20%7C-Twimbit-ef6d6c)](https://twimbit.com/?)

[![Open Source Love](https://badges.frapsoft.com/os/v2/open-source.svg?v=103)](https://github.com/twimbit/wordpress-monday) [![Project demo](https://img.shields.io/youtube/views/OlHK9WsZCOY?style=social)](https://www.youtube.com/watch?v=OlHK9WsZCOY) 

![Project demo](https://img.shields.io/badge/Inspiration-Monday%20and%20Wordpress%20integration-0074a2?style=for-the-badge&logo=)
<br>


## Background 

Twimbit is a technology company giving market research. We use WordPress as a content publishing platform. But how can we manage our publications and collaborate? Monday.com was the answer. So, we set-up Monday boards with automated workflows for our use case. Life was meant to be a simple handshake between content publishing and content management. 

### The Problem  
Till we discovered the inefficiencies in the system. A lot of back and forth between Monday and WordPress in which users had to manually update things. 

### The opportunity 
Integrate with Monday API V2. As we built it on top of WordPress, we realized that we could extract the core and make it an open service for anyone using WordPress.


## Introduction
This integration lets users synchronize there WordPress site with Monday to create efficient workflows and automation. Monday users can synchronize posts, pages, users, comments, and taxonomies from WordPress to Monday and vice versa. This opens a new window of automation opportunity for publishers, content creators, or simply anyone using WordPress to create custom workflows and connect various other platforms without any additional plugin installation on WordPress.

## Table of Contents

- [Introduction](#introduction)
- [Table of Contents](#table-of-contents)
- [Benefits](#benefits)
- [How does it works?](#how-does-it-works?)
    - [Getting Started](#getting-started)
    - [Installation Documentation](#steps-for-installation)
    - [Vulnerability disclosure](#vulnerability-disclosure)
    - [Contributing](#contributing)
- [Future Scope](#future-scope-🧐)
    - [Collaboration](#collaboration)
    - [What's next?](#what's-next-for-wordPress-monday-integration)
- [Core team](#core-team)


## Benefits

### Monday users would be able to do following Integrations -
1.  When a WordPress Post is created, create a new item and sync future changes.
Additionally -
a. Assign user if exists.
b. Assign Tags and categories using tags fields.
c. Load Comments as updates to the posts.
d. Synchronize post status and Monday status
e. Add the preview post link.
2.  When a WordPress Page is created, create a new item and sync future changes.

3. When a new Monday item is created, Create a Draft post in WordPress.

4. When an item status changes to something, change WordPress post status to something

5. When a new WordPress user is added, create a new item on Monday

6. When a new Comment is added create a new item.

>  ......  Many more integrations coming soon.


## How does it works?
The integration uses Monday V2 API with Authorization, custom triggers and actions, and WordPress Rest API. Since WordPress users can have any site, so to have a standard backend app, we have created a middleware application between Monday and WordPress that runs all the transaction on Standard URL’s.

### Getting Started

Users will also need to install an additional WordPress plugin on their WordPress website and enter asked details during integration.

Install the plugin

| Plugin | Download file |
| ------ | ------ |
| GitHub | [Plugin](https://github.com/twimbit/wordpress-monday) |

### Steps for Installation

1. Download this **[.zip file](https://github.com/twimbit/wordpress-monday)** .
2. Login to your WordPress Dashboard.
3. In your WordPress Admin Menu, go to Plugins > Add New.
4. Click on the Upload Plugin button found on the top left corner of the page.
5. Click on Browse, select the .zip file of your plugin in your computer, and click the Install Now button.
6. At this point, the plugin is installed. You can click on the Activate Plugin link to work with it.
Well done, you have managed to install and activate your WordPress plugin!


### Vulnerability disclosure 🧑🏼‍💻

Monday-wordpress integration is the open source project. We welcome security research on this [open source project](https://github.com/twimbit/wordpress-monday/issues) .

### Contributing
We encourage you to contribute to this [project!!](https://github.com/twimbit/monday-wordpress-integration/pulls) ❤️ 

## Future Scope 🧐

#### Collaboration 🤝
Collaborate with us on creating more integrations and future scopes for monday and wordpress.

#### What's next for WordPress - Monday Integration
1. Building more integrations 
2. Implemented Integration mapping 
3. Connecting multiple WordPress sites with multiple Monday's board
4. Building a community around it.

#### Core team 👨‍👦‍👦

| GitHub Usernames                                             |
| ------------------------------------------------------------ |
| Aman Sharma [(@amanintech)](https://github.com/amanintech)   |
| Siddhant Kumar [(@siddhantdante)](https://github.com/siddhantdante) | 
| Gaurav Kumar [(@icon-gaurav)](https://github.com/icon-gaurav) |
| Shalini Bose [(@shalinibose)](https://github.com/shalinibose) 


### License
MIT License

Copyright (c) 2020 Twimbit

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

Any questions, please refer to our license [doc](https://github.com/twimbit/monday-wordpress-integration/blob/master/LICENSE)  or email aman@twimbit.com


[![forthebadge](https://forthebadge.com/images/badges/built-by-developers.svg)](https://github.com/twimbit)

