cacheme.php
===========

cachme is a *very* simple caching system for PHP pages, based on the technique described at http://www.snipe.net/2009/03/quick-and-dirty-php-caching/ but packaged in a reusable script.

Instructions
------------

To use cachme, simple include it at the *very top* of the script you wish to cache:

    <?php include('cacheme.php'); ?>

Also make sure the cache directory ("cache" in DOCUMENT_ROOT by default) exists and is writable by the webserver.

You can change the expiration time per file by setting `$cacheme_expires` (number of seconds) before including "cacheme.php".

Caveats
-------

Custom headers such as content types aren't cached, etc.