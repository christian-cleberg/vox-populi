# Vox Populi - A Tumblr Web Client
A quick and easy web client for Tumblr written in PHP.

Steps to get started:
1. To create a Tumblr application and get your consumer key/secret pair, [create an app on the Tumblr site](https://www.tumblr.com/oauth/apps).
2. Visit the [Tumblr developer console](https://api.tumblr.com/console/) and use the "Show Keys" button to find your token/token-secret.
3. Set your environment variables in an `.htaccess` file. For example,
```
# Environment Variables
SetEnv CONSUMER_KEY "<your-consumer-key>"
SetEnv CONSUMER_SECRET "<your-consumer-secret>"
SetEnv TOKEN "<your-token>"
SetEnv TOKEN_SECRET "<your-token-secret>"
```
