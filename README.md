# Vox Populi - A Tumblr Web Client
A quick and easy web client for Tumblr written in PHP.

## Installation
1. To create a Tumblr application and get your consumer key/secret pair, [create an app on the Tumblr site](https://www.tumblr.com/oauth/apps).
2. Set your environment variables in an `.htaccess` file. For example,
```
# Environment Variables
SetEnv CONSUMER_KEY "<your-consumer-key>"
SetEnv CONSUMER_SECRET "<your-consumer-secret>"
```
3. Git clone this repository into the web directory where you want Voc Populi to be served.
4. Done! You (and your users) can now login. The site will automatically redirect you to the Tumblr login page if you are not already authenticated.

### Login Page
![](https://img.cleberg.io/vox-populi/vox_populi_login.png)

### Authentication via Tumblr
![](https://img.cleberg.io/vox-populi/vox_populi_auth.png)

### Dashboard
![](https://img.cleberg.io/vox-populi/vox_populi_dashboard.png)
