## Introduction

WP Plugin Dev Mode is a WordPress plugin that prevents other wordpress plugins from loading based on one of several criteria that you can choose. 

Our issue was that there were certain plugins that we wanted to disable on our development environment. Google Analytics or other SEO plugins for example. It would really not be that hard to go in and
deactivate the plugin on our development server, but long term, if I need to get a new copy of a production site for development work,  I have to go through and remember which one I disabled.

Not only is this kind of a pain to remember, but when you add in several developers who have to do that same thing, and then add in multiple sites that you manage, this task becomes somewhat error prone.
It also makes it harder to automate such a move.



* **Server HTTP Host** - This will use the HTTP Host that is sent in as a header from the browser, If you choose this option, enter the url of your development site in the "Custom Value" field below.</li>
* **Custom PHP Constant** - This will use a PHP constant that you define. The value must evaluate to true to be considered valid. False, empty, not set, etc. will all evaluate to false. You are responsible for defining and setting this constant.</li>
* **Custom Apache Environment Variable** - Apache allows you to set environment variables in several ways. If you denote your development environment in this way, choose this option. You will need to enter not only the environment variable name but its value as well</li>
* **WP_DEBUG Constant** - This is a standard debug constant is referenced int he WordPress Codex. This can also be used to identify this installation as a development installation. </li>

We not disable the plugins. Some plugins remove settings when you disable them. This would be bad for moving from a development environment to a live/production environment.


## Installation

* **Option 1:** Copy the entire folder into your wp-content/plugins folder, then navigate in the plugins section of your WordPress install to activate the plugins
* **Option 2:** (Coming Soon) Install by going to the Plugins page of your WordPress install.

## Usage

Once the plugin is installed and activated, there should be a Plugin Settings page where you can customize how the plugin acts. On this page you will be able to choose what option the plugin
will use to determine if your site is on a development server, the custom value that should be used, and what plugins should be disabled on this install. 

Couple of things that should be noted:

* If the test of a method, name and value return ***true***, then the plugins that you have checked in the list at the bottom of the settings page will not be loaded when your page loads.
* this plugin makes use of a feature in WordPress called [Must-Use Plugins](http://codex.wordpress.org/Must_Use_Plugins). These plugins cannot be disabled, do bot show in the plugin list and are always executed. There is a checkbox at the top of the settings page that will enable the use of the MU Plugin. This is the toggle that will stop our plugin from running.


## DISCLAIMER
**Use at your own risk. This plugin intentionally stops other pieces of code from running. If you stop a necessary plugin from running, and your site fails to function properly, you should just need to uncheck that particular plugin from disabling.
This has been tested on several Wordpress installations, but it is not guaranteed to be perfect.**

## Issues and suggestions
If you find issues with the way teh plugin works, or have a suggestion as to how you think it could be improved in later release or future development, please [submit an issue](https://github.com/mcyrulik/wp-plugin-dev-mode/issues)

## Versions
* **v0.2.0** - Added saving settings to JSON for easy code migration, other minor updates.
* **v0.1.0** - First development release. Let's call this early alpha..