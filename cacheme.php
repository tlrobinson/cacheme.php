<?php
    /*
     * cachme.php
     *
     * Copyright 2010, Thomas Robinson
     *
     * This library is free software; you can redistribute it and/or
     * modify it under the terms of the GNU Lesser General Public
     * License as published by the Free Software Foundation; either
     * version 2.1 of the License, or (at your option) any later version.
     *
     * This library is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
     * Lesser General Public License for more details.
     *
     * You should have received a copy of the GNU Lesser General Public
     * License along with this library; if not, write to the Free Software
     * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
     *
     * Based on techniques described at http://www.snipe.net/2009/03/quick-and-dirty-php-caching/
     */

    if (!isset($cacheme_directory))
        $cacheme_directory = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'cache';
    if (!isset($cacheme_expires))
        $cacheme_expires = 10 * 60; // 10 minutes
    if (!isset($cacheme_debug))
        $cacheme_debug = true;

    $cacheme_cache_file = $cacheme_directory . $_SERVER['SCRIPT_NAME'];

    // check to see if a valid cached file exists
    if (file_exists($cacheme_cache_file) && (time() - $cacheme_expires < filemtime($cacheme_cache_file))) {
        echo file_get_contents($cacheme_cache_file);

        cachme_echo('<!-- Loaded from cache: ' . date('jS F Y H:i', filemtime($cacheme_cache_file)) . ' -->');
        exit;
    }

    // start output buffering
    ob_start();

    register_shutdown_function('cacheme_complete');

    function cacheme_complete() {
        global $cacheme_cache_file;

        // check for and create parent directories of cache file
        $dir = dirname($cacheme_cache_file);
        if (file_exists($dir) || mkdir($dir, 0755, true)) {
            // attempt to open the cache file for writing
            $fp = fopen($cacheme_cache_file, 'w');
            if ($fp) {
                // write the contents of the output buffer
                fwrite($fp, ob_get_contents());
                fclose($fp);
                // flush the output buffer
                ob_end_flush();

                cachme_echo('<!-- Cached at: ' . date('jS F Y H:i', time()) . ' -->');
                return;
            }
        }

        cachme_echo('<!-- Warning: unable to cache -->');
    }

    function cachme_echo($string) {
        global $cacheme_debug;

        if ($cacheme_debug)
            echo $string;
    }

?>
