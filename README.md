# elon-commands
## What is it?
It is a set of commands creating a reputation system with voting in Slack.

## How to use?
To make it work you must create a `password.php` file that contains declaration of following constants:
* HOST – MySQL host
* LOGIN – MySQL login
* PASSWORD – MySQL password
* DATABASE – MySQL database to use
* TOKEN – Your Slack API Token
* COMMAND_TOKEN – Your Slack command token

Also you should create two tables in your database called `users` and `polls` and create it like this:
```
CREATE TABLE `users` (
  `id` varchar(64) NOT NULL,
  `name` varchar(64) NOT NULL,
  `reputation` int(11) NOT NULL DEFAULT '0'
)

CREATE TABLE `polls` (
  `id` int(11) NOT NULL,
  `user` varchar(64) NOT NULL,
  `infavour` int(11) NOT NULL DEFAULT '0',
  `against` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `voted` text COLLATE NOT NULL,
  `votingfor` enum(':happyelon:',':neutralelon:',':sadelon:') COLLATE utf8_unicode_ci NOT NULL
)
```
