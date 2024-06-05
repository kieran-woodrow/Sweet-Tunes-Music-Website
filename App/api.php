<?php
 //session_start();
include_once 'php/config.php';
header('Content-Type: application/json');
header('Allow-Access-Control-Origin: *');
class API 
{
    private static $apiInstance = null;//CREATE AN INSTANCE VARIABLE
    protected static $type;
    protected static $page;
    protected static $return;
    protected static $title;
    protected static $artist;
    protected static $song;
    protected static $year;
    protected static $genre;
    protected static $album;
    protected static $duration;
    protected static $releaseDate;
    protected static $rank;
   

    public function __construct(  ) //CONSTRUCTOR TO SET PREMADE VARIABLES
    { 
        if(isset($_POST['type']))//IF THE VARIABLE HAS BEEN CREATED
            self::$type=$_POST['type'];//SET IT TO THE ONE IN OUR CLASS( COPY CONSTRUCTOR )
            

        if(isset($_POST["page"]))
            self::$page=$_POST["page"];

        if(isset($_POST["return"]))
            self::$return=$_POST["return"];

        if(isset($_POST["title"]))
            self::$title=$_POST["title"];

        if(isset($_POST["artist"]))
            self::$artist=$_POST["artist"];

        if(isset($_POST["song"]))
            self::$song=$_POST["song"];

        if(isset($_POST["year"]))
            self::$year=$_POST["year"];

        if(isset($_POST["genre"]))
            self::$genre=$_POST["genre"];

        if(isset($_POST["album"]))
            self::$album=$_POST["album"];

        if(isset($_POST["duration"]))
            self::$duration=$_POST["duration"];

        if(isset($_POST["releaseDate"]))
            self::$releaseDate=$_POST["releaseDate"];

        if(isset($_POST["rank"]))
            self::$rank=$_POST["rank"];

        if(isset($_POST["imageUrl"]))
            self::$page=$_POST["imageUrl"];

    }


    public static function getInstance()//FUNCTION TO CREATE OBJECT
    {
        if (self::$apiInstance == null)
        {
            self::$apiInstance = new API();//MAKE A NEW OBJECT
        }
        return self::$apiInstance;
    }




    public function handleRequest()
{
    $info=["status" => "success", "timestamp" => time(), "data"=>[]]; 
     if(isset($_POST["type"]))
    { 
        if(self::$type==$_POST["type"])
        {
           // switch (self::$page)
           // {
                //case "trending":

                    
                    $mainArray=array();//CREATE AN ARRAY
                    error_reporting(E_ALL);//TURNS ON ALL ERRORS
                    ini_set("display_errors", 1); //DISPLAYS THE ERRORS
                    $curl=curl_init(); //$CURL IS GOING TO BE OF DATATYPE CURL..PRETTY MUCH MAKING AN INSTANCE OF THE CURL CLASS    
                    curl_setopt($curl, CURLOPT_URL, 'https://api.deezer.com/radio/42042/tracks/&output=json');
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1 );//DOESN'T SHOW ON SCREEN
                    curl_setopt($curl, CURLOPT_HEADER, 0);
                    curl_setopt($curl, CURLOPT_PROXY, "phugeet.cs.up.ac.za:3128");
                    $resultBeforeJson=curl_exec($curl);//STORE THE INFO INTO AN OBJECT
                    curl_close($curl);//CLOSE THE CURL LINK
                    $resultAfterJson=json_decode($resultBeforeJson);
                
                        for($i=0; $i<20; $i++)
                        {
                            $songname=$resultAfterJson->data[$i]->title;
                            $artist = $resultAfterJson->data[$i]->artist->name;//gets artist name
                            $rank = $resultAfterJson->data[$i]->rank;//gets rank
                            $albumId=$resultAfterJson->data[$i]->album->id;
                            
                            $myobj=self::getMoreInfoWithAlbumId($songname, $artist, $rank, $albumId );
                    
                            if(isset($myobj))
                                array_push($mainArray, $myobj);  
                        }

                    array_push($info["data"], $mainArray);
                   //echo json_encode($info);
               // break;//END OF TRENDING PHP

                //case "newreleases":

                    $newReleasesArray=array();//CREATE AN ARRAY

                    error_reporting(E_ALL);//TURNS ON ALL ERRORS
                    ini_set("display_errors", 1); //DISPLAYS THE ERRORS
                    $curl=curl_init(); //$CURL IS GOING TO BE OF DATATYPE CURL..PRETTY MUCH MAKING AN INSTANCE OF THE CURL CLASS    
                    curl_setopt($curl, CURLOPT_URL, 'https://api.deezer.com/radio/31061/tracks&output=json');
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1 );//DOESN'T SHOW ON SCREEN
                    curl_setopt($curl, CURLOPT_HEADER, 0);
                    curl_setopt($curl, CURLOPT_PROXY, "phugeet.cs.up.ac.za:3128");
                    $resultBeforeJson=curl_exec($curl);//STORE THE INFO INTO AN OBJECT
                    curl_close($curl);//CLOSE THE CURL LINK
                    $resultAfterJson=json_decode($resultBeforeJson);

                        for($i=0; $i<20; $i++)
                        {
                            $songname=$resultAfterJson->data[$i]->title;
                            $artist = $resultAfterJson->data[$i]->artist->name;//gets artist name
                            $rank = $resultAfterJson->data[$i]->rank;//gets rank
                            $albumId=$resultAfterJson->data[$i]->album->id;

                            $myobj=self::getMoreInfoWithAlbumId($songname, $artist, $rank, $albumId );

                            if(isset($myobj))
                                array_push($newReleasesArray, $myobj);
                        }

                        array_push($info["data"], $newReleasesArray);
                        //echo json_encode($info); 
                // //break;//END OF NEW RELEASES PHP

                // //case "toprated":

                        $topRatedArray=array();//CREATE AN ARRAY
    
                        error_reporting(E_ALL);//TURNS ON ALL ERRORS
                        ini_set("display_errors", 1); //DISPLAYS THE ERRORS
                        $curl=curl_init(); //$CURL IS GOING TO BE OF DATATYPE CURL..PRETTY MUCH MAKING AN INSTANCE OF THE CURL CLASS    
                        curl_setopt($curl, CURLOPT_URL, 'https://api.deezer.com/chart&output=json');
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1 );//DOESN'T SHOW ON SCREEN
                        curl_setopt($curl, CURLOPT_HEADER, 0);
                        curl_setopt($curl, CURLOPT_PROXY, "phugeet.cs.up.ac.za:3128");
                        $resultBeforeJson=curl_exec($curl);//STORE THE INFO INTO AN OBJECT
                        curl_close($curl);//CLOSE THE CURL LINK
                        $resultAfterJson=json_decode($resultBeforeJson);

                            for($i=0; $i<10; $i++)
                            {
                                $songname=$resultAfterJson->tracks->data[$i]->title;
                                $artist = $resultAfterJson->tracks->data[$i]->artist->name;//gets artist name
                                $rank = $resultAfterJson->tracks->data[$i]->position;//gets rank
                                $albumId=$resultAfterJson->tracks->data[$i]->album->id;

                                $myobj=self::getMoreInfoWithAlbumId($songname, $artist, $rank, $albumId );

                                if(isset($myobj))
                                    array_push($topRatedArray, $myobj);
                            }

                        array_push($info["data"], $topRatedArray);
                        // echo json_encode($info);
                // //break; //END OF TOP RATED PHP

                // //case "featured":

                        $featuredArray=array();//CREATE AN ARRAY

                        error_reporting(E_ALL);//TURNS ON ALL ERRORS
                        ini_set("display_errors", 1); //DISPLAYS THE ERRORS
                        $curl=curl_init(); //$CURL IS GOING TO BE OF DATATYPE CURL..PRETTY MUCH MAKING AN INSTANCE OF THE CURL CLASS    
                        curl_setopt($curl, CURLOPT_URL, 'https://api.deezer.com/radio/37151/tracks&output=json');
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1 );//DOESN'T SHOW ON SCREEN
                        curl_setopt($curl, CURLOPT_HEADER, 0);
                        curl_setopt($curl, CURLOPT_PROXY, "phugeet.cs.up.ac.za:3128");
                        $resultBeforeJson=curl_exec($curl);//STORE THE INFO INTO AN OBJECT
                        curl_close($curl);//CLOSE THE CURL LINK
                        $resultAfterJson=json_decode($resultBeforeJson);

                            for($i=0; $i<20; $i++)
                            {
                                $songname=$resultAfterJson->data[$i]->title;
                                $artist = $resultAfterJson->data[$i]->artist->name;//gets artist name
                                $rank = $resultAfterJson->data[$i]->rank;//gets rank
                                $albumId=$resultAfterJson->data[$i]->album->id;
                                
                                $myobj=self::getMoreInfoFromLastfm($songname, $artist, $rank, $albumId );

                                if(isset($myobj))
                                    array_push($featuredArray, $myobj);
                            }

                        array_push($info["data"], $featuredArray);
                      // echo json_encode($info);

                       if(isset(self::$return))
                       {
                        if(self::$return[0]=='*')
                        {
                            echo json_encode($info);

                        }
                        else
                        {
                            
                             self::apiRequests($info);
                        }

                       }

                      
              //  break;// END OF FEATURED PHP

              //  default:


            //}
        }    
    }
} 

public function getMoreInfoWithAlbumId($songname, $artist, $rank, $albumId )
{
    error_reporting(E_ALL);//TURNS ON ALL ERRORS
    ini_set("display_errors", 1); //DISPLAYS THE ERRORS
    $curl=curl_init(); //$CURL IS GOING TO BE OF DATATYPE CURL..PRETTY MUCH MAKING AN INSTANCE OF THE CURL CLASS    
    curl_setopt($curl, CURLOPT_URL, 'https://api.deezer.com/album/'.$albumId);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1 );//DOESN'T SHOW ON SCREEN
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_PROXY, "phugeet.cs.up.ac.za:3128");
    $resultBeforeJson=curl_exec($curl);//STORE THE INFO INTO AN OBJECT
    curl_close($curl);//CLOSE THE CURL LINK
    $albumObject=json_decode($resultBeforeJson);

  if(isset($albumObject))
   {
       if(isset($albumObject->genres->data[0]->name) && isset($albumObject->cover_medium))
       
       {
            //console.log(albumObject);
            $genre=$albumObject->genres->data[0]->name;
            $label=$albumObject->label;
            $albumtitle=$albumObject->title;
            $releaseDate=$albumObject->release_date;
            $imageurl=$albumObject->cover_medium;
        
            $myobj=array();

                $myobj["songname"]=$songname;
                $myobj["artist"]=$artist;
                $myobj["rank"]=$rank;
                $myobj["albumid"]=$albumId;
                $myobj["genre"]=$genre;
                $myobj["label"]=$label;
                $myobj["albumtitle"]=$albumtitle;
                $myobj["releasedate"]=$releaseDate;
                $myobj["imageurl"]=$imageurl;

            return $myobj;

       }  
    }
}

public function getMoreInfoFromLastfm($songname, $artist, $rank, $albumId )
{
    error_reporting(E_ALL);//TURNS ON ALL ERRORS
    ini_set("display_errors", 1); //DISPLAYS THE ERRORS
    $curl=curl_init(); //$CURL IS GOING TO BE OF DATATYPE CURL..PRETTY MUCH MAKING AN INSTANCE OF THE CURL CLASS    
    curl_setopt($curl, CURLOPT_URL, 'https://api.deezer.com/album/'.$albumId);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1 );//DOESN'T SHOW ON SCREEN
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_PROXY, "phugeet.cs.up.ac.za:3128");
    $resultBeforeJson=curl_exec($curl);//STORE THE INFO INTO AN OBJECT
    curl_close($curl);//CLOSE THE CURL LINK
    $albumObject=json_decode($resultBeforeJson);

    error_reporting(E_ALL);//TURNS ON ALL ERRORS
    ini_set("display_errors", 1); //DISPLAYS THE ERRORS
    $curl2=curl_init(); //$CURL IS GOING TO BE OF DATATYPE CURL..PRETTY MUCH MAKING AN INSTANCE OF THE CURL CLASS    
    curl_setopt($curl2, CURLOPT_URL, "http://ws.audioscrobbler.com/2.0/?method=track.getInfo&api_key=a69a3f50b7d4072ecf210b19b86b6e87&artist=".$artist."&track=".$songname."&format=json");
    curl_setopt($curl2, CURLOPT_RETURNTRANSFER, 1 );//DOESN'T SHOW ON SCREEN
    curl_setopt($curl2, CURLOPT_HEADER, 0);
    curl_setopt($curl2, CURLOPT_PROXY, "phugeet.cs.up.ac.za:3128");
    $resultBeforeJson2=curl_exec($curl2);//STORE THE INFO INTO AN OBJECT
    curl_close($curl2);//CLOSE THE CURL LINK
    $albumObject2=json_decode($resultBeforeJson2);

  if(isset($albumObject) && isset($albumObject2))
   {
       if(isset($albumObject->genres->data[0]->name) && isset($albumObject->cover_medium) && isset($albumObject2->track->duration) && isset($albumObject2->track->playcount))
    
       {
            //console.log(albumObject);
            $genre=$albumObject->genres->data[0]->name;
            $label=$albumObject->label;
            $albumtitle=$albumObject->title;
            $releaseDate=$albumObject->release_date;
            $imageurl=$albumObject->cover_medium;
            $duration=$albumObject2->track->duration;
            $playcount=$albumObject2->track->playcount;
            
            $myobj=array();

                $myobj["songname"]=$songname;
                $myobj["artist"]=$artist;
                $myobj["rank"]=$rank;
                $myobj["albumid"]=$albumId;
                $myobj["genre"]=$genre;
                $myobj["label"]=$label;
                $myobj["albumtitle"]=$albumtitle;
                $myobj["releasedate"]=$releaseDate;
                $myobj["imageurl"]=$imageurl;
                $myobj["duration"]=$duration;
                $myobj["playcount"]=$playcount;

            return $myobj;
       }  
    }
}





 public function apiRequests($info)
{
   
        $info1=$info;
    {
        if( isset(self::$return) )

        {

            $karray=["status" => "success", "timestamp" => time(), "data"=>[]]; 
        
            $returnedArrayLength=count($return);

            $foundObject=array();
        
              for($a=0; $a<$returnedArrayLength; $a++)
                 {
        
                         switch(self::$return[$a])
                         {
                                case "title":
                                     for($b=0; $b<4; $b++)
                                {
                                       
                                         for($c=0; $c<count($info1->data[$b]); $c++)
                                         {
                                                if(isset(self::$title))
                                                {
                                                    if(self::$title==$info1->data[$b][$c]->title)
                                                   {
                                                         $titleFound=$info1->data[$b][$c]->title;
                
                                                       $foundobject["title"]=$titleFound;
                        
                                                  }
                                                }
                                                else
                                                {
                                                    $titleFound=$info1->data[$b][$c]->title;
                                                    $foundobject["title"]=$titleFound;
                        
                                                }
                                             
                                         }
                                     }
                                 break;

                            //      case "":
                            //         for($b=0; $b<4; $b++)
                            //    {
                                      
                            //             for($c=0; $c<count($info->data[$b]); $c++)
                            //             {
                            //                    if(isset(self::$title))
                            //                    {
                            //                        if(self::$title==$info->data[$b][$c]->title)
                            //                       {
                            //                             $titleFound=$info->data[$b][$c]->title;
               
                            //                           $foundobject["title"]=$titleFound;
                       
                            //                      }
                            //                    }
                            //                    else
                            //                    {
                            //                        $titleFound=$info->data[$b][$c]->title;
                            //                        $foundobject["title"]=$titleFound;
                       
                            //                    }
                                            
                            //             }
                            //         }

    
        
        
        
    //                     }
        
        
    //             }
                 array_push($karray["data"], $foundObject );
        
        
                 echo json_encode($karray);
    //              //echo json_encode($info->data[0]);
             }

     }
  
  

} //END OF API CLASS



}
}
}

$apiInstance=API::getInstance(); //SINGLETON OBJECT

$apiInstance->handleRequest();








?>