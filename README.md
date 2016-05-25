Evidence Module
========
[in development]

instruction about how to restart the module if migrations were updated
0) disable module
1) Delete all tables and records created in migration, example  http://i.imgur.com/hzEdifg.png -> http://i.imgur.com/rxFYho1.png(chose delete) and  http://i.imgur.com/mlm8b8R.png. And delete the record itself in migration table http://i.imgur.com/u6chz2B.png -> http://i.imgur.com/3PMuLil.png
2) Make module pull
3) Run module
4) Check the all records and tables created in migration.

Humhub project installation instructions.
========
PHASE 1. Step-by-step project installation.

1. Clone https://github.com/HuntedHive/humhub.git to project folder
2. Go to humhub/protected/modules and clone there the following modules from https://github.com/HuntedHive
 - humhub-modules-evidence
 - humhub-modules-logicenter
 - humhub-modules-chat
 - humhub-modules-questionanswer
 - humhub-modules-registration
 - humhub-modules-secondaryemail
 - humhub-modules-karma
 - humhub-modules-extend_search
 - humhub-modules-mail
After cloning is done, delete `humhub-modules-` in folder names in order to be able to activate modules.

3. Go to humhub/themes and clone https://github.com/HuntedHive/humhub-themes-tq.git
4) Go to humhub/protected/config/local and create _settings.php file
5) Go to humhub/protected/config/_defaults.php and create corresponding urlManager http://i.imgur.com/hIbTbn3.png
6) Go to root project and clone https://github.com/HuntedHive/humhub-ratchet.git
7) Go to humhub-ratchet folder and run commands in console: 
- composer install
- php init
8) Go to humhub-ratchet/common/main-local.php folder and add your configs for DB http://i.imgur.com/0aOsb4M.png
9) Run project in browser and go through all installation stages if they are
10) Go to http://i.imgur.com/aknE2wm.png and activate all modules
11) Go to http://i.imgur.com/XkfFFiE.png and activate the theme

PHASE. Files and folders
1) Main modules for the project
 - humhub-modules-evidence
 - humhub-modules-logicenter
 - humhub-modules-chat
 - humhub-modules-questionanswer
 - humhub-modules-registration
 - humhub-modules-secondaryemail
 - humhub-modules-karma
 - humhub-modules-extend_search
 - humhub-modules-mail

2) Main theme in the project
 -  humhub-themes-tq

3) Main configs in the project 
 - http://i.imgur.com/5gN7nOu.png
