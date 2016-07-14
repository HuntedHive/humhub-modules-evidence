Evidence Module
========
[in development]

Humhub project installation instructions.
========
PHASE 1. Step-by-step project installation.

1. Clone https://github.com/HuntedHive/humhub.git {branch version-1.0.1} to project folder
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
4) Go to humhub/protected/config/common.php and create corresponding urlManager http://i.prntscr.com/8e0198622e314cfc86fe90000b995288.png
5) Go to root project and clone https://github.com/HuntedHive/humhub-ratchet.git
6) Go to humhub-ratchet folder and run commands in console:
- composer install
- php init
7) Go to humhub-ratchet/common/main-local.php folder and add your configs for DB http://i.imgur.com/0aOsb4M.png
8) Run project in browser and go through all installation stages if they are
9) Go to http://i.imgur.com/aknE2wm.png and activate all modules
10) Go to http://i.imgur.com/XkfFFiE.png and activate the theme

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
 - http://i.prntscr.com/8e0198622e314cfc86fe90000b995288.png
