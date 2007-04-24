<?php
//
// Definition of eZStaticClass class
//
// Created on: <12-Jan-2005 10:29:21 dr>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file ezstaticcache.php
*/

/*!
  \class eZStaticCache ezstaticcache.php
  \brief Manages the static cache system.

  This class can be used to generate static cache files usable
  by the static cache system.

  Generating static cache is done by instatiating the class and then
  calling generateCache(). For example:
  \code
  $staticCache = new eZStaticCache();
  $staticCache->generateCache();
  \endcode

  To generate the URLs that must always be updated call generateAlwaysUpdatedCache()

*/

include_once( 'lib/ezutils/classes/ezini.php' );

class eZStaticCache
{
    /*!
     Initialises the static cache object with settings from staticcache.ini.
    */
    function eZStaticCache()
    {
        $ini =& eZINI::instance( 'staticcache.ini');
        $this->HostName = $ini->variable( 'CacheSettings', 'HostName' );
        $this->StaticStorageDir = $ini->variable( 'CacheSettings', 'StaticStorageDir' );
        $this->MaxCacheDepth = $ini->variable( 'CacheSettings', 'MaxCacheDepth' );
        $this->CachedURLArray = $ini->variable( 'CacheSettings', 'CachedURLArray' );
        $this->CachedSiteAccesses = $ini->variable( 'CacheSettings', 'CachedSiteAccesses' );
        $this->AlwaysUpdate = $ini->variable( 'CacheSettings', 'AlwaysUpdateArray' );
    }

    /*!
     \return The currently configured host-name.
    */
    function hostName()
    {
        return $this->HostName;
    }

    /*!
     \return The currently configured storage directory for the static cache.
    */
    function storageDirectory()
    {
        return $this->StaticStorageDir;
    }

    /*!
     \return The maximum depth in the url which will be cached.
    */
    function maxCacheDepth()
    {
        return $this->MaxCacheDepth;
    }

    /*!
     \return An array with site-access names that should be cached.
    */
    function cachedSiteAccesses()
    {
        return $this->CachedSiteAccesses;
    }

    /*!
     \return An array with URLs that is to be cached statically, the URLs may contain wildcards.
    */
    function cachedURLArray()
    {
        return $this->CachedURLArray;
    }

    /*!
     \return An array with URLs that is to always be updated.
     \note These URLs are configured with \c AlwaysUpdateArray in \c staticcache.ini.
     \sa generateAlwaysUpdatedCache()
    */
    function alwaysUpdateURLArray()
    {
        return $this->AlwaysUpdateArray;
    }

    /*!
     Generates the caches for all URLs that must always be generated.

     \sa alwaysUpdateURLArray().
    */
    function generateAlwaysUpdatedCache( $quiet = false, $cli = false, $delay = true )
    {
        $hostname = $this->HostName;
        $staticStorageDir = $this->StaticStorageDir;

        foreach ( $this->AlwaysUpdate as $uri )
        {
            if ( !$quiet and $cli )
                $cli->output( "caching: $uri ", false );
            $this->storeCache( $uri, $hostname, $staticStorageDir, array(), false, $delay );
            if ( !$quiet and $cli )
                $cli->output( "done" );
        }
    }

    function generateNodeListCache( $nodeList )
    {
        $hostname = $this->HostName;
        $staticStorageDir = $this->StaticStorageDir;

        foreach ( $nodeList as $uri )
        {
            $this->storeCache( '/' . $uri['path_identification_string'], $hostname, $staticStorageDir, array(), false, true );

            /* Fetch all url aliases with the same node */
            /* 1. request content/view/full/* style url */
            $db =& eZDB::instance();
            $srcURL = $db->escapeString( $uri['path_identification_string'] );
            $destURL = $db->arrayQuery( "SELECT destination_url FROM ezurlalias WHERE source_url = '$srcURL'" );
            /* 2. get all other elements linked to the same destination URL */
            $aliases = $db->arrayQuery( "SELECT source_url FROM ezurlalias WHERE destination_url = '{$destURL[0]['destination_url']}' AND destination_url <> '{$uri['path_identification_string']}'" );
            /* Loop over this result and store the cache for this */
            foreach ( $aliases as $alias )
            {
                $this->storeCache( '/' . $alias['source_url'], $hostname, $staticStorageDir, array(), false, true );
            }
        }
    }

    /*!
     Generates the static cache from the configured INI settings.

     \param $force If \c true then it will create all static caches even if it is not outdated.
     \param $quiet If \c true then the function will not output anything.
     \param $cli The eZCLI object or \c false if no output can be done.
    */
    function generateCache( $force = false, $quiet = false, $cli = false, $delay = true )
    {
        $staticURLArray = $this->cachedURLArray();
        $db =& eZDB::instance();
        $configSettingCount = count( $staticURLArray );
        $currentSetting = 0;
        foreach ( $staticURLArray as $url )
        {
            $currentSetting++;
            if ( strpos( $url, '*') === false )
            {
                if ( !$quiet and $cli )
                    $cli->output( "caching: $url ", false );
                $this->cacheURL( $url, false, !$force, $delay );
                if ( !$quiet and $cli )
                    $cli->output( "done" );
            }
            else
            {
                if ( !$quiet and $cli )
                    $cli->output( "wildcard cache: $url" );
                $queryURL = ltrim( str_replace( '*', '%', $url ), '/' );
                $queryURL = $db->escapeString( $queryURL );
                $aliasArray = $db->arrayQuery( "SELECT source_url, destination_url FROM ezurlalias WHERE source_url LIKE '$queryURL' AND source_url NOT LIKE '%*' ORDER BY source_url" );
                $urlCount = count( $aliasArray );
                $currentURL = 0;
                foreach ( $aliasArray as $urlAlias )
                {
                    $currentURL++;
                    $url = "/" . $urlAlias['source_url'];
                    preg_match( '/([0-9]+)$/', $urlAlias['destination_url'], $matches );
                    $id = $matches[1];
                    if ( $this->cacheURL( $url, (int) $id, !$force, $delay ) )
                    {
                        if ( !$quiet and $cli )
                        {
                            $cli->output( sprintf("   %5.1f%% CACHE  $url", 100 * ($currentURL / $urlCount)));
                        }
                    }
                    elseif ( !$quiet and $cli )
                    {
                        $cli->output( sprintf("   %5.1f%% SKIP   $url", 100 * ($currentURL / $urlCount)));
                    }
                }

                if ( !$quiet and $cli )
                {
                    $cli->output( sprintf("%5.1f%% done", 100 * ($currentSetting / $configSettingCount)));
                }
            }
        }
    }

    /*!
     \private
     Generates the caches for the url \a $url using the currently configured hostName() and storageDirectory().

     \param $url The URL to cache, e.g \c /news
     \param $nodeID The ID of the node to cache, if supplied it will also cache content/view/full/xxx.
     \param $skipExisting If \c true it will not unlink existing cache files.
    */
    function cacheURL( $url, $nodeID = false, $skipExisting = false, $delay = true )
    {
        // Set default hostname
        $hostname = $this->HostName;
        $staticStorageDir = $this->StaticStorageDir;

        // Check if URL should be cached
        if ( substr_count( $url, "/") >= $this->MaxCacheDepth )
            return false;

        $doCacheURL = false;
        foreach ( $this->CachedURLArray as $cacheURL )
        {
            if ( $url == $cacheURL )
            {
                $doCacheURL = true;
            }
            else if ( strpos( $cacheURL, '*') !== false )
            {
                if ( strpos( $url, str_replace( '*', '', $cacheURL ) ) === 0 )
                {
                    $doCacheURL = true;
                }
            }
        }

        if ( $doCacheURL == false )
        {
            return false;
        }

        $this->storeCache( $url, $hostname, $staticStorageDir, $nodeID ? array( "/content/view/full/$nodeID" ) : array(), $skipExisting, $delay );

        return true;
    }

    /*!
     \private
     Stores the static cache for \a $url and \a $hostname by fetching the web page using
     fopen() and storing the fetched HTML data.

     \param $url The URL to cache, e.g \c /news
     \param $hostname The name of the host which serves web pages dynamically, see hostName().
     \param $staticStorageDir The base directory for storing cache files, see storageDirectory().
     \param $alternativeStaticLocations An array with additional URLs that should also be cached.
     \param $skipUnlink If \c true it will not unlink existing cache files.
    */
    function storeCache( $url, $hostname, $staticStorageDir, $alternativeStaticLocations = array(), $skipUnlink = false, $delay = true )
    {
        if ( is_array( $this->CachedSiteAccesses ) and count ( $this->CachedSiteAccesses ) )
        {
            $dirs = array();
            foreach ( $this->CachedSiteAccesses as $dir )
            {
                $dirs[] = '/' . $dir ;
            }
        }
        else
        {
            $dirs = array ('');
        }

        foreach ( $dirs as $dir )
        {
            $cacheFiles = array();
            if ( !is_dir( $dir ) )
            {
                eZDir::mkdir( $staticStorageDir . $dir, 0777, true );
            }

            $cacheFiles[] = $this->buildCacheFilename( $staticStorageDir, $dir . $url );
            foreach ( $alternativeStaticLocations as $location )
            {
                $cacheFiles[] = $this->buildCacheFilename( $staticStorageDir, $dir . $location );
            }

            /* Store new content */
            $content = false;
            foreach ( $cacheFiles as $file )
            {
                if ( !$skipUnlink || !file_exists( $file ) )
                {
                    $fileName = "http://$hostname$dir$url";
                    if ( $delay )
                    {
                        $this->addAction( 'store', array( $file, $fileName ) );
                    }

                    if ( !$delay )
                    {
                        /* Generate content, if required */
                        if ( $content === false )
                        {
                            $content = @file_get_contents( $fileName );
                        }
                        if ( $content === false )
                        {
                            eZDebug::writeNotice( "Could not grab content (from $fileName), is the hostname correct and Apache running?",
                                                  'Static Cache' );
                        }
                        else
                        {
                            $this->storeCachedFile( $file, $content );
                        }
                    }
                }
            }
        }
    }

    /*!
     \private
     \param $staticStorageDir The storage for cache files.
     \param $url The URL for the current item, e.g \c /news
     \return The full path to the cache file (index.html) based on the input parameters.
    */
    function buildCacheFilename( $staticStorageDir, $url )
    {
        $file = "{$staticStorageDir}{$url}/index.html";
        $file = preg_replace( '#//+#', '/', $file );
        return $file;
    }

    /*!
     \private
     \static
     Stores the cache file \a $file with contents \a $content.
     Takes care of setting proper permissions on the new file.
    */
    function storeCachedFile( $file, $content )
    {
        $dir = dirname( $file );
        if ( !is_dir( $dir ) )
        {
            eZDir::mkdir( $dir, 0777, true );
        }

        $oldumask = umask( 0 );

        $tmpFileName = $file . '.' . md5( $file. uniqid( "ezp". getmypid(), true ) );

        /* Remove files, this might be necessary for Windows */
        @unlink( $tmpFileName );

        /* Write the new cache file with the data attached */
        $fp = fopen( $tmpFileName, 'w' );
        if ( $fp )
        {
            fwrite( $fp, $content . '<!-- Generated: '. date( 'Y-m-d H:i:s' ). " -->\n\n" );
            fclose( $fp );
            include_once( 'lib/ezfile/classes/ezfile.php' );
            eZFile::rename( $tmpFileName, $file );
        }

        umask( $oldumask );
    }

    /*!
     Removes the static cache file (index.html) and its directory if it exists.
     The directory path is based upon the URL \a $url and the configured static storage dir.
     \param $url The URL for the curren item, e.g \c /news
    */
    function removeURL( $url )
    {
        if ( $url == "/" )
        {
            $dir = $this->StaticStorageDir . $url;
        }
        else
        {
            $dir = $this->StaticStorageDir . $url . "/";
        }

        @unlink( $dir . "/index.html" );
        @rmdir( $dir );
    }

    /*!
     \private
     This function adds an action to the list that is used at the end of the
     request to remove and regenerate static cache files.
    */
    function addAction( $action, $parameters )
    {
        if (! isset( $GLOBALS['eZStaticCache-ActionList'] ) ) {
            $GLOBALS['eZStaticCache-ActionList'] = array();
        }
        $GLOBALS['eZStaticCache-ActionList'][] = array( $action, $parameters );
    }

    /*!
     \static
     This function goes over the list of recorded actions and excecutes them.
    */
    function executeActions()
    {
        if (! isset( $GLOBALS['eZStaticCache-ActionList'] ) ) {
            return;
        }

        $fileContentCache = array();

        foreach ( $GLOBALS['eZStaticCache-ActionList'] as $action )
        {
            list( $action, $parameters ) = $action;

            switch( $action ) {
                case 'store':
                    list( $destination, $source ) = $parameters;
                    if ( ! isset( $fileContentCache[$source] ) )
                    {
                        $fileContentCache[$source] = @file_get_contents( $source );
                    }
                    if ( $fileContentCache[$source] === false )
                    {
                        eZDebug::writeNotice( 'Could not grab content, is the hostname correct and Apache running?', 'Static Cache' );
                    }
                    else
                    {
                        eZStaticCache::storeCachedFile( $destination, $fileContentCache[$source] );
                    }
                    break;
            }
        }
        $GLOBALS['eZStaticCache-ActionList'] = array();
    }

    /// \privatesection
    /// The name of the host to fetch HTML data from.
    var $HostName;
    /// The base path for the directory where static files are placed.
    var $StaticStorage;
    /// The maximum depth of URLs that will be cached.
    var $MaxCacheDepth;
    /// Array of URLs to cache.
    var $CachedURLArray;
}

?>
