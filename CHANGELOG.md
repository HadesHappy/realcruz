4.0.24 / 2021-09-19
==================

 * Added: upgrade to Laravel 8
 * Added: upgrade to Carbon 2
 * Added: more base templates available
 * Added: improved loading performance statistics dashboard
 * Added: better verification and sending speed throttling
 * Added: new layout and color themes options
 * Added: better compliance with Laravel 8, ready to upgrade
 * Added: refined automation UI
 * Added: updated GeoIP dataset
 * Added: multiple payment options supported
 * Added: mass assign values to custom fields
 * Added: more PRO builder widgets
 * Added: more campaign setup options
 * Added: improved sending domain verification process
 * Added: plugin mechanism now allows hooking into the core application
 * Added: better PHP CLI detection
 * Added: file manager is now supported in PRO builder
 * Added: user's space for storing assets
 * Added: more plan setup options
 * Added: easier form generation options
 * Added: more unsubscription options
 * Changed: PHP versions older than 7.3 is no longer supported
 * Changed: default editor (classic) for uploaded templates
 * Changed: allow admins to modify base templates

4.0.23 / 2021-03-17
==================

 * Added: plugin mechanism 1.0 is now available, embracing plugin development
 * Added: more widgets for PRO builder
 * Added: one-time login token generation (via API)
 * Fixed: better SSL detection for shared hosting environment
 * Fixed: better multi-process support & detection
 * Fixed: faster authentication process
 * Fixed: fixed Chrome browser glithes
 * Fixed: better compatibility with PHP 7.4
 * Fixed: better compatibility with different types of HTML email templates
 * Fixed: email builder not working with legacy versions of Internet Explorer
 * Fixed: better AWS bounce/feedback handling
 * Fixed: sending domain verification not working properly with certain DNS providers
 * Fixed: faster web page load in general
 * Fixed: upgrade issue from version 3x to 4x
 * Fixed: performance issue on servers with limited memory
 * Fixed: performance issue with certain types of PCNTL setup
 * Fixed: email builder not working well with SVG
 * Fixed: better compatibility with older versions of Thunderbird

4.0.22 / 2021-02-11
==================

 * Added: tracking domain support
 * Added: tracking domain with HTTPS support
 * Added: purify users' HTML content
 * Added: strict password reset policy
 * Added: spam scoring improved
 * Added: trailing comma clean up, better compatibility with PHP 7.3 or older
 * Added: new Amazon Web Service regions support
 * Added: more automation delay options
 * Added: more timezone converting options
 * Added: RSS feed support with advanced template engine
 * Fixed: DNS record for tracking domain may not correctly show up
 * Fixed: email attachement improved
 * Fixed: InnoDB compatibility improved
 * Fixed: list subscription performance improved
 * Fixed: PRO builder translation improved
 * Fixed: better RFC 1341 compliance
 * Fixed: ENV file quoting
 * Fixed: better load performance for JS/CSS
 * Fixed: delivery attempt tracking improved

4.0.21 / 2020-11-11
==================

 * Fixed: default template missing
 * Fixed: users cannot add custom sending identities
 * Fixed: translation missing
 * Fixed: SendGrid subaccount issue
 * Fixed: PayPal integration error while checking out
 * Fixed: click tracking error for Automation
 * Fixed: Pro builder loading performance
 * Fixed: SPF cannot be verified
 * Fixed: translation cache issue
 * Added: upgrade to Laravel 5.8 (current 5.5)
 * Added: minimum PHP version required is now 7.1.3
 * Added: send test email for system email
 * Added: autofill support for tags
 * Added: clean up bare linefeed characters in email
 * Added: assign plan to customer support
 * Added: reorganization of users assets storage

4.0.19 / 2020-09-10
==================

 + Fixed: automation elements are not well organized
 + Fixed: PayPal integration: sync not working correctly
 + Fixed: Elasicemail API no longer works correctly
 + Fixed: cannot create admin of reseller group
 + Fixed: templates compatibility issues with Edge and Firefox
 + Fixed: default sending speed is too low
 + Fixed: quota tracking does not work correctly
 + Fixed: plan status shown correctly
 + Added: language package for German
 + Added: plat visibility setting support 
 + Added: thumbnail update support
 + Added: classic email builder support
 + Added: automation for list's segments
 + Added: subscription notification for admin
 + Added: notification management dashboard

4.0.18 / 2020-06-08
==================

 * Fixed: sending identity not working in certain cases
 * Fixed: better SMTP error handling
 * Fixed: automation visual designer not loading correctly
 * Fixed: no longer require write permission on public/ folder
 * Fixed: KEY event issue with builder pro
 * Fixed: irrelevant content injected into outgoing email
 * Fixed: unexpected input fields injected into email content
 * Added: allowing switching to basic email builder
 * Added: allowing viewing of available identities list
 * Added: file manager for basic email builder

4.0.5 / 2020-01-14
==================

 * Fixed: subscription issue for free plan
 * Fixed: auto-detect HTTP scheme (https or http)
 * Fixed: plan not visiable in certain cases
 * Updated: smaller builder.js file
 * Updated: faster loading of builder components
 * Updated: drop "use sending server's default FROM email address"

4.0.1 / 2020-01-08
==================

 * Changed: support PHP 7.0.0 or higher (PHP 5.6 is no longer supported)
 * Changed: now on Laravel 5.5 framework
 * Added: new drag & drop email builder
 * Added: new automation workflow visual designer
 * Added: support auto/recurring billing with Stripe
 * Added: improved SaaS workflow

3.0.21 / 2019-03-21
==================

 * Added: support Zapier integration
 * Added: retry failed deliveries
 * Added: system error notification
 * Fixed: open/click tracking not working correctly
 * Fixed: incorrect user IP detection

3.0.20 / 2019-02-12
==================

 * Added: support debounce.io email verification service
 * Added: compatibility with open_basedir
 * Added: support localmail.io verification service
 * Added: support verifyre.co verification service
 * Fixed: memory outage issue with GeoIP
 * Fixed: orphan administrator accounts not loading
 * Fixed: no longer use socket_create function
 * Fixed: verify sender identity against AWS
 * Fixed: SSL issue with email verification

3.0.19 / 2018-11-01
==================

 * Added: list subscription notification
 * Added: verify subscriber email
 * Added: sender verification
 * Added: built-in GeoIP service
 * Changed: require PHP 5.6.4 or higher
 * Fixed: cannot update system SMTP settings in certain cases
 * Fixed: automation not triggered for list subscription
 * Fixed: redirect issue for subscription form
 * Fixed: favicon not showing up correctly on IE
 * Fixed: verify-email.org integration issue
 * Fixed: wrong stats showing up in user dashboard
 * Fixed: PayPal integration issue with JPY
 * Fixed: alignment issue with email builder

3.0.18 / 2018-09-05
==================
 
 * Added: upgrade to Laravel 5.4 (support PHP >=5.6)
 * Added: blacklist recorded for failed subscription
 * Added: switch to MySQL utf8mb4
 * Added: better 404 handling
 * Added: re-send confirmation email
 * Added: CronJob notification
 * Added: support RTL email editor
 * Fixed: contacts' status not translated in export
 * Fixed: security for template webroot
 * Fixed: error with GeoIP tracking
 * Fixed: XSS vulnerability
 * Fixed: better MySQL UIID
 * Fixed: invalid PHP CLI detected
 * Fixed: create mail list API
 * Fixed: automation performance

3.0.17 / 2018-07-29
==================

 * Added: support changing DKIM selector
 * Added: choose sending server's default FROM header
 * Fixed: embedded form broken
 * Fixed: date/time field issue
 * Fixed: import cancel issue
 * Fixed: export UID and STATUS values
 * Fixed: XSS thread with campaign email

3.0.16 / 2018-07-01
==================
 
 * Added: domain verification
 * Added: DKIM verification
 * Added: SPF verification
 * Added: support tracking domain
 * Fixed: campaign does not send to all contacts
 * Fixed: upload manager fails to check file size

3.0.15 / 2018-05-02
==================

 * Fixed: everifier.org compatibility issue
 * Fixed: AWS SNS rate limit issue
 * Fixed: import issue with uppercase email address
 * Fixed: automation issue with unsubscribed contacts
 * Fixed: feedback log not showing up correctly
 * Fixed: Reply-To header is now required for SendGrid
 * Fixed: moving subscribers issue
 * Fixed: subscribers filtering issue
 * Fixed: Return-Path header not set correctly
 * Changed: remove plain text online viewer

3.0.14 / 2018-01-09
==================

 * Fixed: PHP CLI checker does not work correctly
 * Fixed: verification result is not correct
 * Fixed: blacklisting may not work correctly
 * Fixed: "flat array" issue
 * Fixed: SendGrid feedback handling issue
 * Added: support cloning plan
 * Added: support file attachment for campaign email
 * Added: export segment
 * Added: integated with Proofy.io verification service
 * Added: support everifier.org verification service
 * Added: support changing font-size for email editor
 * Added: 

3.0.13 / 2017-10-15
==================

 * Fixed: JS issue with embedded form
 * Fixed: subscriber listing API does not work correctly
 * Fixed: SSL verification issue for system email
 * Fixed: double subscription
 * Fixed: quota tracker issue resulting in invalid credit deduction
 * Fixed: do not send automation emails to unsubscribers
 * Fixed: permission error while importing subscribers
 * Added: support for PayUMoney
 * Added: support uploading subscriber image
 * Added: auto convert ISO-8859-1 to UTF-8 while importing subscribers

3.0.12 / 2017-09-12
==================

 * Fixed: some payment methods are not supported in older systems
 * Fixed: verification with thechecker.co sometimes does not work
 * Fixed: certain custom tags are not translated
 * Changed: do not show up raw bounce message

3.0.11 / 2017-8-31
==================

 * Fixed: suppress PHP warning messages from the UI
 * Fixed: intermittent issue with automation trigger 
 * Fixed: subscriber tags not translated
 * Added: support Paddle payment gateway

3.0.10 / 2017-08-22
==================

 * Fixed: payment issue with Stripe/Braintree
 * Fixed: tax billing information is configurable, no longer compulsory
 * Fixed: license verification does not work in certain cases
 * Fixed: speed up campaigns listing
 * Fixed: speed up subscribers counting
 * Fixed: automation trigger for date in the past
 * Fixed: reduce the number of SQL queries
 * Fixed: retain custom translation when upgrading
 * Added: allow resending a campaign
 * Added: support SendGrid subuser
 * Added: support sending a campaign again
 * Added: support verify-email.org service
 * Added: support importing subscribers via CLI

3.0.9 / 2017-07-13
==================

 * Fixed: bounced emails not added to blacklist
 * Fixed: Reply-To header not correctly set for SendGrid and ElasticEmail
 * Fixed: support Elastic {unsubscribe} tag
 * Added: include Portuguese translation
 * Added: speed up list loading
 * Added: support testing bounce/feedback handlers
 * Added: support sending a test email for sending server
 * Added: display the generated DKIM DNS record for sending domain
 * Added: reduce the tracking log size for faster delivery
 * Added: allow accessing the webapp from root directory
 * Added: support copying templates

3.0.8 / 2017-06-29
==================

 * Fixed: bounced email not added to blacklist
 * Fixed: make sure confirmation email be triggered
 * Added: reduce memory usage while sending
 * Added: speed up page load in general
 * Added: font selection support for email template
 * Added: speed up automation page load
 * Added: speed up subscribers listing

3.0.7 / 2017-06-19
==================

 * Fixed: message-id cannot be retrieved in bounced message
 * Fixed: JS error showing up in the edit template page
 * Fixed: lagging image/video in the email editor
 * Fixed: thumbnail not showing up correctly
 * Fixed: user cannot subscribe to certain plans
 * Fixed: speed up the customers list view

3.0.6 / 2017-06-08
==================

 * Fixed: issue with automation for subscriber events
 * Fixed: automation follow-up email not triggered in certain cases
 * Fixed: incorrect quota counting in certain cases
 * Fixed: cannot subscribe customer to a plan as administrator
 * Fixed: prevent double subscription
 * Fixed: check to make sure public/ folder is writable while installing
 * Changed: queued campaign is now of QUEUED status (READY is no longer used)
 * Changed: SMTP encryption is no longer a required field
 * Changed: remove GMP extension validation as it is no longer required

3.0.4 / 2017-06-01
==================

 * Added: subscribers count by unique email
 * Added: more test scripts
 * Fixed: fix potential security issues related to CSRF
 * Fixed: upgrade will no longer overwrite custom translation
 * Fixed: campaign does not pause in certain cases 
 * Fixed: CSV export may not work correctly

3.0.3 / 2017-05-25
==================

 * Added: support billing information page
 * Added: configure sending servers that can be added by customer
 * Added: look up GEO information from local database
 * Fixed: intermittent unsubscribe issue
 * Fixed: plain text glitch with ElasticEmail
 * Fixed: intermittent issue when saving payment methods
 * Fixed: update-profile URL does not work with automation
 * Fixed: editor inserts additional tags to the email content

3.0.2 / 2017-05-20
==================

 * Added: support working with Braintree's merchant accounts
 * Added: support updating site logo
 * Fixed: delivery handler issue with SSL
 * Fixed: eliminate duplicate background jobs
 * Fixed: automation issue with list subscription
 * Fixed: prevent duplicate unsubscription
 * Fixed: better mobile compatibility
 * Fixed: upgrade manager issue with old PHP versions

3.0.1 / 2017-05-11
==================

 * Added: integration with email verification service Kickbox.io
 * Added: integration with email verification service TheChecker.co
 * Added: upgrade manager - allow upgrading directly from the admin dashboard
 * Added: email web viewer (through the WEB_VIEW_URL tag)
 * Added: table features for email builder
 * Fixed: plain text campaign issue with ElasticEmail

3.0.0-p3 / 2017-04-20
==================

 * Added: payment integration with PayPal
 * Added: payment integration with Stripe
 * Added: preview campaign email before sending
 * Changed: change the API response format for CREATE USER, including api_token
 * Fixed: licence page error on certain systems
 * Fixed: duplicate queries generated for campaign statistics view

3.0.0-p1 / 2017-04-11
==================

 * Added: service plan management
 * Added: registration for visitor
 * Added: payment integration with Braintree (for Paypal and credit card)
 * Added: sending throttling setting
 * Added: subscription management
 * Added: customer management
 * Added: more flexible role based access control
 * Added: license verification
 * Added: clean up sending server's send() function
 * Added: more intuitive description on the UI
 * Fixed: automation sending duplicate follow-up email

2.2.0-p14 / 2017-03-30
==================

 * Added: support sending email through SparkPost API
 * Added: reduce memory usage by 40% for sending to 1M subscribers
 * Added: support file update via API
 * Added: better built-in  Spanish translation
 * Changed: suppress error messages in laravel.log
 * Fixed: import issue with non-break spaces
 * Fixed: check/uncheck sending server in Group Edit page
 * Fixed: feedback handling with ElasticEmail
 * Fixed: reduce subscribers list request size
 * Fixed: bounce handling issue: cannot retrieve message ID in certain cases

2.2.0-p13 / 2017-03-15
==================

 * Added: support more caching to speed up page loading
 * Added: better compatibility check
 * Added: better Message-Id generation, to avoid conflict
 * Added: improve list importing performance
 * Added: more API support, allow retrieving list's fields
 * Added: follow up campaign email when it is not opened/clicked
 * Added: new quota tracking system that supports multi-process
 * Fixed: inconsistent Open map
 * Fixed: inconsistent SendGrid bounce report
 * Fixed: inconsistent sending statistics
 * Fixed: email header Return-Path not properly setup
 * Fixed: campaign status not correctly set

2.2.0-p12 / 2017-02-27
===================

 * Added: speed up page loading using cache
 * Added: support additional user group API
 * Fixed: file manager's thumbnail image 
 * Fixed: intermittent glitch with code editor
 * Fixed: template upload issues with old HTML/CSS styles
 * Fixed: translating missing in system email

2.2.0-p11 / 2017-02-22
===================

 * Added: fully multi-process supported
 * Added: support more API for list/subscriber management
 * Changed: new default user permissions
 * Fixed: better handle invalid bounced message
 * Fixed: invalid bounces not showing up correctly
 * Fixed: automation cannot start for certain scenarios

2.2.0-p10 / 2017-02-15
====================

 * Fixed: suppress verbose error message
 * Fixed: do not fork new processes if it is not really needed
 * Fixed: file manager upload limit
 * Fixed: issue with plain text campaign when working with SendGrid
 * Fixed: prevent campaign from being queued more than one time 
 * Fixed: template selection issue on old Firefox browsers
 * Fixed: click-to-open rate not showing up correctly

2.2.0-p9 / 2017-02-10
=====================

 * Fixed: installation issues with exec on restricted OS
 * Added: support Re-captcha
 * Added: include more details in installation error messages
 * Changed: API - return newly created subscriber's UID in the response

2.2.0-p8 / 2017-02-06
=====================
 
 * Added: support advanced background job installation wizard
 * Added: support erasing existing database before initialization
 * Added: support customizing system's default language
 * Fixed: issue with auto login by token

2.2.0-p7 / 2017-01-30
=====================

 * Added: support updating the application configuration without re-installing
 * Added: support sending campaign without using cronjob
 * Fixed: timezone issue while scheduling future campaign

2.2.0-p6* / 2017-01-27
===================

 * Added: support sending campaign to multiple lists / segments
 * Fixed: issue of migrating from v2.0.4
 * Fixed: links sometimes do not work in test email
 * Fixed: intermittent memory issue of PHP 5.6 or below

2.2.0-p5 / 2017-01-23
===================

 * Fixed: sending server's quota 

2.2.0-p4 / 2017-01-20
===================

 * Fixed: file manager URL issue with old browsers
 * Changed: new quota renewal method

2.2.0-p3 / 2017-01-17
===================

 * Fixed: memory limit issue with importing
 * Fixed: MAC OS line-ending issue
 * Fixed: follow-up email is triggered more than one time

2.2.0-p2 / 2017-01-11
===================

 * Fixed: compatibility issues with old PHP versions
 * Fixed: compatibility issues with 
 * Fixed: calendar glitches on some browsers

2.2.0-p1 / 2017-01-08
===================

 * Fixed: mail list export compatibility issue on certain systems
 * Fixed: cannot delete out-dated campaigns
 * Fixed: php-curl compatibility issue
 * Fixed: improve subscribers import performance
 * Fixed: PHP 7.1 compatibility issues
 * Changed: php-xml is now required
 * Changed: refractor of the system jobs
 * Changed: only one cronjob is required
 * Added: support automation/autorespond functionality

2.0.4-p27 & 2.0.4-p28 / 2016-11-09
===================

 * Added: send a test email of campaign
 * Added: better internationalization support: allow creating new language
 * Added: better internationalization support: support custom translation
 * Changed: support running several campaigns at the same time

2.0.4-p26 / 2016-11-08

 * Added: send a test email of campaign
 * Added: better internationalization support: allow creating new language
 * Added: better internationalization support: support custom translation
 * Changed: support running several campaigns at the same time

2.0.4-p25 / 2016-11-01
==================

 * Fixed: certain encoding may cause corrupt links
 * Changed: default user policy change

2.0.4-p24 / 2016-10-28
==================
 
 * Fixed: subscriber import does not work well with async
 * Fixed: runtime-message-id with extra invisible space
 * Fixed: directory permission checking error
 * Fixed: campaign's wrong subscribers count in certain cases
 * Fixed: config cache with invalid values

2.0.4-p23 / 2016-10-23
==================

 * Added: ElasticEmail API/SMTP support
 * Fixed: reduce the delay time when sending email through SMTP
 * Changed: delivery server encryption method is no longer required

2.0.4-p22 / 2016-10-19
==================
 
 * Added: create-user API
 * Added: quick login support
 * Added: copy campaign
 * Fixed: detect more environment dependencies when installing
 * Fixed: layout crashes for old IE browser
 * Fixed: application crashes when mbstring is missing
 * Fixed: chart view issues on MS Edge

2.0.4-p20 / 2016-10-12
==================

 * Fixed: installation wizard compatibility issue
 * Added: drag & drop email builder

2.0.4-p19 / 2016-10-03
==================

 * Fixed: certain types of links are not tracked

2.0.4-p18 / 2016-10-02
==================

 * Fixed: open tracking causes broken image in email content

2.0.4-p17 / 2016-10-02
==================

 * Fixed intermittent issues with bar chart in Safari
 * Changed click-to-open ratio is now based on open count

2.0.4-p16 / 2016-09-30
==================

 * Fixed listing sometimes crashes due to slow internet connection
 * Fixed do not allow users to enter invalid IMAP encryption method
 * Fixed list import intermittent issue for ISO encoded CSV
 * Added pie chart visualization for top countries by open
 * Added pie chart visualization for top countries by click
 * Updated text & hints on the UI
 * Changed dashboard UI now contains more information
 * Changed click-rate is no longer computed based on specific URL

2.0.4-p11 / 2016-09-27
==================

 * Fixed SSL issue for bounce handler
 * Fixed bounce handler does not work correctly for certain type of IMAP servers
 * Changed sending campaign can be deleted
 * Added full support for SendGrid (web API & SMTP)

2.0.4-p8 / 2016-09-20
==================

 * Fixed HTML editor sometimes crashes on MS Edge 
 * Added clean up invalid bytes sequence in email content
 * Added check php-gd library availability in the installation wizard

2.0.4 / 2016-09-13
==================

This is the first publicly released version of Acelle Mail webapp (which was previously Turbo Mail 1.x, a private project at National Information System institute)

 * Fixed better compatibility with MS Edge browser
 * Multi-process support for sending large amounts of email
 * Added Mailgun API/SMTP integration full support
 * Added embeded form customization support
 * Added email extra headers for better RFC compliance
 * Added template gallery & template customization support

2.0.3 / 2016-07-01
==================

 * Added DKIM singing support for out-going message
 * Added better integration with Amazon SES
 * Added template preview support
 * Added bounce logging with more information
 * Changed refractor of quota system
