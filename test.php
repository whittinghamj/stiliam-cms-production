<?php

// $source = 'http://epg.streamstv.me/epg/guide-usa.xml.gz';
$source = 'http://www.xmltvepg.nl/rytecUK_SportMovies.xz';
$file_ext = pathinfo( $source, PATHINFO_EXTENSION );
if( $file_ext == 'gz' ) {
	// download and extract source file
	$content = shell_exec( "wget -qO- ".$source." | gunzip" );
} elseif( $file_ext == 'xz' ) {
	// download and extract source file
	$content = shell_exec( "wget -qO- ".$source." | unxz -c" );
}

echo $content;