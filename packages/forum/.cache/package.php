<?php
// Automatically created cache file for the package format
// Do not modify this file

$CacheCodeDate = 1061114927;

$Parameters = array( "name" => "forum",
                     "summary" => "Forum site",
                     "description" => false,
                     "vendor" => false,
                     "priority" => false,
                     "type" => false,
                     "extension" => false,
                     "ezpublish" => array( "version" => "3.2.0-1",
                                           "named-version" => "3.2 pre-alpha" ),
                     "maintainers" => array( array( "name" => "Anonymous User",
                                                    "email" => "nospam@ez.no",
                                                    "role" => "lead",
                                                    "modified" => "1061231740" ) ),
                     "packaging" => array( "timestamp" => "1061231740",
                                           "host" => "bf.ez.no",
                                           "packager" => false ),
                     "source" => false,
                     "documents" => array( array( "name" => "README",
                                                  "mime-type" => "text/plain",
                                                  "os" => false,
                                                  "audience" => false,
                                                  "modified" => "1061231740" ) ),
                     "groups" => array(),
                     "changelog" => array( array( "timestamp" => "1061231740",
                                                  "person" => "Anonymous User",
                                                  "email" => "nospam@ez.no",
                                                  "changes" => array( "Creation of package" ),
                                                  "release" => "1",
                                                  "modified" => "1061231740" ) ),
                     "file-list" => array( "default" => array( array( "name" => false,
                                                                      "subdirectory" => false,
                                                                      "type" => "design",
                                                                      "role" => false,
                                                                      "path" => false,
                                                                      "file-type" => false,
                                                                      "design" => "forum",
                                                                      "copy-file" => false,
                                                                      "modified" => "1061231741",
                                                                      "md5" => false ),
                                                               array( "name" => "override.ini.append",
                                                                      "subdirectory" => "settings/siteaccess/forum",
                                                                      "type" => "file",
                                                                      "role" => false,
                                                                      "path" => "settings/siteaccess/forum/override.ini.append",
                                                                      "file-type" => false,
                                                                      "design" => false,
                                                                      "copy-file" => false,
                                                                      "modified" => "1061231741",
                                                                      "md5" => "60a669cb62f6cc9123d267383892391f" ),
                                                               array( "name" => "site.ini.append",
                                                                      "subdirectory" => "settings/siteaccess/forum",
                                                                      "type" => "file",
                                                                      "role" => false,
                                                                      "path" => "settings/siteaccess/forum/site.ini.append",
                                                                      "file-type" => false,
                                                                      "design" => false,
                                                                      "copy-file" => false,
                                                                      "modified" => "1061231742",
                                                                      "md5" => "2d16a620f76915c7f759106dd7200025" ),
                                                               array( "name" => "thumbnail.png",
                                                                      "subdirectory" => false,
                                                                      "type" => "thumbnail",
                                                                      "role" => false,
                                                                      "path" => "forumthumbnail.png",
                                                                      "file-type" => false,
                                                                      "design" => false,
                                                                      "copy-file" => false,
                                                                      "modified" => "1061231744",
                                                                      "md5" => "703fed5d0e4d4300ff566f5bdaeec7ef" ) ) ),
                     "version-number" => "1.0",
                     "release-number" => "1",
                     "release-timestamp" => false,
                     "licence" => "GPL",
                     "state" => "alpha",
                     "dependencies" => array( "provides" => array( array( "type" => "ezfile",
                                                                          "name" => "collection",
                                                                          "value" => "default",
                                                                          "modified" => "1061231741" ) ),
                                              "requires" => array( array( "type" => "ezdb",
                                                                          "name" => "mysql",
                                                                          "value" => false,
                                                                          "modified" => "1061231742" ),
                                                                   array( "type" => "ezdb",
                                                                          "name" => "postgresql",
                                                                          "value" => false,
                                                                          "modified" => "1061231743" ),
                                                                   array( "type" => "ezdb",
                                                                          "name" => "postgresql",
                                                                          "value" => false,
                                                                          "modified" => "1061231744" ) ),
                                              "obsoletes" => array(),
                                              "conflicts" => array() ),
                     "install" => array( array( "collection" => "default",
                                                "type" => "ezfile",
                                                "name" => false,
                                                "modified" => "1061231741",
                                                "os" => false,
                                                "filename" => false,
                                                "sub-directory" => false ),
                                         array( "path" => "packages/forum.sql",
                                                "database-type" => "mysql",
                                                "type" => "sql",
                                                "name" => false,
                                                "modified" => "1061231742",
                                                "os" => false,
                                                "filename" => "forum.sql",
                                                "sub-directory" => false ),
                                         array( "path" => "kernel/sql/postgresql/kernel_schema.sql",
                                                "database-type" => "postgresql",
                                                "type" => "sql",
                                                "name" => false,
                                                "modified" => "1061231743",
                                                "os" => false,
                                                "filename" => "kernel_schema.sql",
                                                "sub-directory" => false ),
                                         array( "path" => "kernel/sql/postgresql/cleandata.sql",
                                                "database-type" => "postgresql",
                                                "type" => "sql",
                                                "name" => false,
                                                "modified" => "1061231744",
                                                "os" => false,
                                                "filename" => "cleandata.sql",
                                                "sub-directory" => false ) ),
                     "uninstall" => array( array( "collection" => "default",
                                                  "type" => "ezfile",
                                                  "name" => false,
                                                  "modified" => "1061231741",
                                                  "os" => false,
                                                  "filename" => false,
                                                  "sub-directory" => false ) ) );
$ModifiedParameters = array( "name" => "1061231740",
                             "version-number" => "1061231740",
                             "release-number" => "1061231740",
                             "licence" => "1061231740",
                             "state" => "1061231740",
                             "summary" => 1061231745 );
$RepositoryPath = "packages";
?>
