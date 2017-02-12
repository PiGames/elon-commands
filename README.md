# elon-commands
## What is it?
It is a set of commands creating a reputation system in Slack.

## How to use?
To make it work you must create a `password.php` file that contains declaration of following constants (http://php.net/manual/en/function.define.php):
* HOST – MySQL host
* LOGIN – MySQL login
* PASSWORD – MySQL password
* DATABASE – MySQL database to use
* TOKEN – Your Slack API Token
* COMMAND_TOKEN – Your Slack command token

Also you should create a table in your database called `users` and create it like this:
```
CREATE TABLE `users` (
  `id` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `reputation` int(11) NOT NULL DEFAULT '0'
)
```
