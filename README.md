<img src="https://github.com/twimbit/monday-wordpress-integration/blob/master/Group%208%402x.png" object-fit="cover" width="100%">
<div align="center">
  <br>
  <h1> Monday-Wordpress Integration 👨‍💻</h1>
</div>
<br>

[![Twimbit](https://img.shields.io/badge/Powered%20by%20%7C-Twimbit-ef6d6c)](https://twimbit.com)

[![Open Source Love](https://badges.frapsoft.com/os/v2/open-source.svg?v=103)](https://github.com/twimbit/wordpress-monday) [![Project demo](https://img.shields.io/youtube/views/OlHK9WsZCOY?style=social)](https://www.youtube.com/watch?v=OlHK9WsZCOY) 

## Getting Started
<a href="https://auth.monday.com/oauth2/authorize?client_id=e8ca407267da543d1a1496123679ea4f&response_type=install"> <img alt="Add to monday.com" height="42" src="https://dapulse-res.cloudinary.com/image/upload/f_auto,q_auto/remote_mondaycom_static/uploads/Tal/4b5d9548-0598-436e-a5b6-9bc5f29ee1d9_Group12441.png" /> </a>

| Plugin | Download file |
| ------ | ------ |
| Monday Wordpress Integration | [Plugin](https://github.com/twimbit/monday-wordpress-integration/releases/download/0.1-beta/Wordpress-Plugin.zip) |

## Introduction
This integration lets users synchronize there WordPress site with Monday to create efficient workflows and automation. Monday users can synchronize posts, pages, users, comments, and taxonomies from WordPress to Monday and vice versa. This opens a new window of automation opportunity for publishers, content creators, or simply anyone using WordPress to create custom workflows and connect various other platforms without any additional plugin installation on WordPress.

### Steps for Installation

1. Download this **[.zip file](https://github.com/twimbit/monday-wordpress-integration/releases/download/0.1-beta/Wordpress-Plugin.zip)** .
2. Install the plugin in wordpress by uploading the downloaded folder 
3. Go to monday.com and choose the desired integration from our app.
4. Go to `wordpress settings > Monday Integration > Integration`
5. Copy the `API Key` and `API secret` and paste it on the monday integration page and enter site URL without / . eg https://example.com
6. Authorize the app

## Table of Contents

- [Introduction](#introduction)
- [Table of Contents](#table-of-contents)
- [Benefits](#Features)
- [How does it works?](#how-does-it-works?)
    - [Date we store](#data-we-store)
    - [Vulnerability disclosure](#vulnerability-disclosure-)
    - [Contributing](#contributing)
- [Future Scope](#future-scope-)
    - [Collaboration](#collaboration-)
    - [What's next?](#what's-next-for-wordPress-monday-integration)
- [Credits](#Credits-)


## Features
### Available Integrations
<img src="https://github.com/twimbit/monday-wordpress-integration/blob/master/features.png" object-fit="cover" width="100%">

## How does it works?
The integration uses Monday V2 API with Authorization, custom triggers and actions, and WordPress Rest API. Since WordPress users can have any site, so to have a standard backend app, we have created a middleware application between Monday and WordPress that runs all the transaction on Standard URL’s.

Note- For now whenever you add an integration, we automatically create required columns and sync them with wordpress fields ( This is going to be replaced with integration mapping).


### Data we store
To ensure consistent authorization we store a copy of Monday Account Id, user ID along with the board id on which integration is added. We also store the subscirption id which is used for removing integrations. To direct the access to Users wordpress and authenticate it, we store copy of monday wordpress integration plugin API key and API secret. We dont share any information with any third party. We don't store your content or transaction as they directly performed between wordpress and monday.

### Vulnerability disclosure 🧑🏼‍💻

Monday-wordpress integration is the open source project. We welcome security research on this [open source project](https://github.com/twimbit/wordpress-monday/issues) .

### Contributing
- Raise a problem or bug [Here](https://github.com/twimbit/wordpress-monday/issues)
- Request a feature [Here](https://github.com/twimbit/wordpress-monday/issues)
- We encourage you to contribute to this [project!!](https://github.com/twimbit/monday-wordpress-integration/pulls) ❤️ 

## Background 

Twimbit is a technology company giving market research. We use WordPress as a content publishing platform. But how can we manage our publications and collaborate? Monday.com was the answer. So, we set-up Monday boards with automated workflows for our use case. Life was meant to be a simple handshake between content publishing and content management. 

### The Problem  
Till we discovered the inefficiencies in the system. A lot of back and forth between Monday and WordPress in which users had to manually update things. 

### The opportunity 
Integrate with Monday API V2. As we built it on top of WordPress, we realized that we could extract the core and make it an open service for anyone using WordPress & Monday.


## Future Scope 🧐

#### Collaboration 🤝
Collaborate with us on creating more integrations and future scopes for monday and wordpress.

#### What's next for WordPress - Monday Integration
1. Building more integrations 
2. Implemented Integration mapping 
3. Connecting multiple WordPress sites with multiple Monday's board
4. Building a community around it.

#### Credits 👨‍👦‍👦

| Contributors                                           |
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


