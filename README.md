MeetingMinutes
==============

This extension provides Javascript and CSS helpers to enable a slick method of entering meeting minutes into MediaWiki using Semantic Forms. Additionally, it provides methods to embed references to meeting minutes on pages related to those meeting minutes.

This is in pre-alpha
--------------------

I'm actively pushing broken builds just because I want to back them up. Sorry. Also, this requires Composer to install, but currently isn't on Packagist. To install in MediaWiki, first clone this repository to somewhere on your computer, then in your MediaWiki composer.json file add the following:

```json
	"repositories": [
		{
			"type": "vcs",
			"url": "C:/path/on/your/computer/where/you/put/MeetingMinutes"
		}
	]	
```

Then, from your MediaWiki directory, run:

```bash
composer require "enterprisemediawiki/meeting-minutes" "dev-master"
```