<?php
    require_once 'includes/rdio.php';
    
    
    $reqtype = htmlspecialchars($_GET["type"]);
    
    if($reqtype === "rdio"){
        //returns information about the last song listened to on rdio
        $url = 'http://api.rdio.com/1/';
        $fields = array(
                        'vanityName' => 'alunatic',
                        'extras' => 'lastSongPlayed,lastSongPlayTime'
                );
        $rdio = new Rdio(array("RDIOKEY", "RDIOKEY"));
        $result = $rdio->call("findUser", $fields);
        $result = get_object_vars($result->result->lastSongPlayed);
        
        $lastAlbumCover = $result['icon'];
        $lastAlbum = $result['album'];
        $lastArtist = $result['albumArtist'];
        $lastName = $result['name'];
        
        $urlArtist = urlencode($lastartist);
        $urlAlbum = urlencode($lastalbum);
        
        $artistUrl = "http://www.amazon.ca/gp/search?search-alias=popular&field-artist=$urlArtist&field-title=$urlAlbum";
        $albumUrl = "http://en.wikipedia.org/wiki/$urlArtist";
        
        $resultsArray = array( songName => $lastName, albumName => $lastAlbum, albumCover => $lastAlbumCover, artist => $lastArtist, artistUrl => $artistUrl, albumUrl => $albumUrl);
        echo json_encode($resultsArray);
    }else if($reqtype === "goodreads"){
        echo "goodreads";
    }else if($reqtype === "twitter"){
        echo "twitter";
    }else if($reqtype === "github"){
        echo "github";
    }


?>