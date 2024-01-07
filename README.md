# Photogram
Photogram is an easy-to-use web app for sharing photos with a clean and uncluttered interface.

It is designed to facilitate photo-sharing among users. Its sleek and intuitive interface, combined with powerful back-end functionality, makes it the ideal platform for sharing photos with friends, family, and colleagues. This document will provide detailed instructions on how to install and run the project, so that you can start sharing your favorite photos today.

> **NOTE:** *This project is currently under development and some things might not work as expected.*

## Table of Contents
- Getting Started
  - Prerequisites
  - Installation
- Usage
- Contributing

## Getting Started

### Prerequisites

Before starting the project, you will need to have the following packages installed on your machine:

- PHP (v8.0 above)
- NPM
- Curl
- Composer
- Apache (Web Server)
- Grunt (Task Runner)

### Installation

To setup photogram, follow these steps:

Install composer dependencies:
```bash
composer update
```

Install NPM dependencies:
```bash
npm install
```

Install Grunt and Sass:
```bash
npm install -g grunt-cli sass
```

Install php-gd extension:
- This extension is used for image processing

```bash
sudo apt-get install php-gd php-mongodb
```

Uncomment the gd extension in php.ini config:
```php
;extension=gd
extension=gd
```

Uncomment the mongodb extension in php.ini config:
```php
;extension=mongodb
extension=mongodb
```

Enabling apache modules:
```bash
sudo a2enmod headers
sudo a2enmod rewrite
sudo a2enmod actions
```

Restart apache to apply changes
```bash
sudo service apache2 restart
```

To find out which user as apache web server running? Run this command.

```bash
ps aux | egrep '(apache|httpd)' | awk '{print $1}' | uniq -d
``` 

Create and change folder permissions:
- This is to allow apache to move uploaded files to this folder.
- If the user is not the same as below, you can replace www:data user with the user that is currently running Apache.
```bash
mkdir -p storage/posts/ storage/avatars/

sudo chown -R www-data:www-data storage/
sudo chmod -R 755 storage/
```

Create config using make_config PHP script:
- Fill in the prompts to make the Photogram work.
- If have doubts, check the reference config at `config/example.photogram.json`
```bash
cd config/
php make_config
```

Apply migrations to database:
- This will create the database with necessary tables and fields for photogram.
- NOTE: Before executing migrations, check that you are properly connected to the database.
```bash
php database/migrations.php
```

## Usage

The Photogram application has an intuitive and user-friendly interface that makes it easy to share your photos with others. Once you've logged in to your account, simply click on the upload button and select the images you wish to share. It will guide you through the process of uploading your photos and adding relevant descriptions.

Once your photos are uploaded, they will be visible to other users on the platform. You can browse and search through existing content, and interact with other users by liking and commenting on their photos.

Overall, Photogram is a powerful yet simple-to-use platform that makes it easy to share your visual creations. Whether you're a professional photographer or just someone who loves to capture and share moments with others, Photogram is the perfect platform for you.

## Contributing

We welcome contributions to the Photogram project! Here are some ways you can get involved:

- Report bugs and suggest new features by creating an issue on our [git repository](https://git.selfmade.ninja/Henry/photogram/-/issues).
- Contribute code by forking the repository, making changes, and submitting a pull request.
- Help improve the documentation by submitting a pull request with your changes.

Thank you for your interest in contributing to Photogram!
