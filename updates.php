<?php
    require_once 'includes/rdio.php';
    require_once 'includes/tmhOAuth.php';
    ini_set('error_reporting', E_ALL);

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
        /**
         * Gets information about the book currently being read on goodreads
         * Only works if one book is being read, at the moment.
         **/
        $api = "API";
        $usr = "USRID";
        $resultsArray = array();
        
        //get currently reading books
        $current_books = "http://www.goodreads.com/review/list/".$usr.".xml?key=".$api."&v=2&shelf=currently-reading&sort=date_read&order=d&page=1&per_page=1";
        $reading = simplexml_load_file($current_books);
        
        //get read status
        $current_status = "http://www.goodreads.com/user/show/".$usr.".xml?key=".$api;
        $status = simplexml_load_file($current_status);
        $resultsArray['completionStatus'] = (string)($status->user->user_statuses->user_status->percent);
        $resultsArray['bookTitle'] = (string)($reading->reviews->review[0]->book->title);
        $resultsArray['bookCover'] = (string)($reading->reviews->review[0]->book->image_url);
        foreach($reading->reviews->review[0]->book->authors->author as $author){
            $resultsArray['author'] = (string)($author->name);
        }
        
        echo json_encode($resultsArray);
   
    }else if($reqtype === "twitter"){
        /**
         * Current twitter API guidelines make this a PITA and would violate twitter's UX guidelines
         *
         * For now, no tweets.
         *
         ***/
    }else if($reqtype === "github"){
        /**
         *This is easily done with javascript and jquery, as follows an example:
         *
         *function githubUpdate(){
                $.ajax({
                    type: "GET",
                    url: "https://api.github.com/repositories/19235975/commits"
                })
                .done(function( msg ) {
                    console.log(msg[0]['commit']);
                    var content = "";
                    var date = new Date(msg[0]['commit']['author']['date']);
                    content += "Latest Commit: " + msg[0]['commit']['message'] +" - " + date.toLocaleDateString() + " " + date.toLocaleTimeString(); 
                    $('.update').html(content);
                });
            };
         *
         * as such, this isn't required in the server side script
         **/
    }


?>