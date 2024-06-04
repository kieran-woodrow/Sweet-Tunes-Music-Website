 $(function(){


  // var key = 'a69a3f50b7d4072ecf210b19b86b6e87' // LAST FM API

  $.ajax({url: `https://cors-anywhere.herokuapp.com/https://api.deezer.com/radio/37151/tracks`, success: function(result)//this function queries deezer api
    {
        var trending=result;//stors results in a variable
        console.log("Here 1");
        console.log(trending);
       
  
            for(var k = 0; k < 8 ; k++)
            {
                var songName = trending.data[k].title;//gets songname
                //console.log(songName);
                var artist = trending.data[k].artist.name;//gets artist name
                //console.log(artist);
                var rank = trending.data[k].rank;//gets rank
                //console.log(rank);
                //var image=trending.data[i].artist.picture_medium;
                var Albumid=trending.data[k].album.id;
            
                changeStuff(songName, artist, rank, Albumid, k);//puts it all into a function
                document.body.className = "loaded";

            }
    }});


   
    function changeStuff ( songName, artist, rank, Albumid, k)
    {
        $.ajax({url: `https://cors-anywhere.herokuapp.com/https://api.deezer.com/album/`+Albumid, success: function(returned) //this function queries deezer api
        {
          var albumObject=returned;
            //  var Albumactualid=albumObject.id;
              
              
                //console.log(albumObject);
                var genre=albumObject.genres.data[0].name;
                var label=albumObject.label;
                var albumtitle=albumObject.title;
                var releaseDate = albumObject.release_date;
                var imageurl=albumObject.cover_medium;

                changeMoreStuff( songName, artist, rank, genre, label, albumtitle, releaseDate, imageurl  ) ;
                
              
  
      

          console.log("Here 2");

         
  
          
      }});
  }

  let j = 0;  
  let i = 1;


  function changeMoreStuff( songName, artist, rank, genre, label, albumtitle, releaseDate, imageurl ) //this function queries last fm api using query result from deezer to return data 
  {


    $(document).ready(function() 
        {
          $.getJSON("http://ws.audioscrobbler.com/2.0/?method=track.getInfo&api_key=a69a3f50b7d4072ecf210b19b86b6e87&artist="+artist+"&track="+songName+"&format=json", function(object) {
            console.log(object);
            console.log("Here 3");
      
              var duration=object.track.duration;
              var playcount=object.track.playcount;

           
             

              j++;
          
              if(j % 4 === 0)
                {
                  buildCards(songName, artist,  genre,  releaseDate, imageurl, i, playcount, duration);
                  ++i;
                } 
                else 
                  {
                    buildCards(songName, artist, genre,  releaseDate, imageurl, i, playcount, duration);
                  } 
                
  });
  })};

  }); //closing b racket for whole function


 
function buildCards( songName, artist, genreArtist, releaseDate, imgSrc, i, duration, playcount )
{

  // UI var
  let cardHolder = document.querySelector(`.albumContainer${i}`);

  // Create outer div
  const outerCard = document.createElement('div');
  // Add class
  outerCard.className = 'box';
 

  // Create inner div
  const innerCard = document.createElement('div');
  // Add class 
  innerCard.className = `boxinner ${genreArtist}`;
  // Add id containing artist
  innerCard.id = `${songName} ${artist}`;
  // Append inner box to outer box
  outerCard.appendChild(innerCard);
   

  // Create div for front card
  // Create div element
  const cardFront = document.createElement('div');
  // Add class
  cardFront.className = 'boxfront';
  // Create a img element
  const artistImg = document.createElement('img');
  // Add img src
  artistImg.src = imgSrc;
  // Append img to card
  cardFront.appendChild(artistImg);
  // Create a h3 element
  const artistName = document.createElement('h3');
  const artistText = document.createTextNode(artist);
  artistName.appendChild(artistText);

  const sName = document.createElement('h3');
  const sText = document.createTextNode(songName);
  sName.appendChild(sText);
  //  Add class to h3
  artistName.className = 'artist-name';
  cardFront.appendChild(artistName);
  cardFront.appendChild(sName);

  // Append front card to inner card
  innerCard.appendChild(cardFront);

  // Create div for back card
  const cardBack = document.createElement('div');
  // Add class
  cardBack.className = 'boxback';

  // Create 5 p element for track info
  const genre = document.createElement('p');
  const genreText = document.createTextNode("Genre: "+genreArtist);
  genre.appendChild(genreText);

  const duration1 = document.createElement('p');
  const durationText = document.createTextNode("Duration in seconds: "+duration);
  duration1.appendChild(durationText);

  const playCount = document.createElement('p');
  const playcountText = document.createTextNode("Play count: "+playcount);
  playCount.appendChild(playcountText);

  const release = document.createElement('p');
  const releaseText = document.createTextNode("Date: "+releaseDate);
  release.appendChild(releaseText);

  // Append all p tags to back card
  cardBack.appendChild(genre);
  cardBack.appendChild(release);
  cardBack.appendChild(duration1);
  cardBack.appendChild(playCount);

  // Append back card to inner card
  innerCard.appendChild(cardBack);

  // Add to album container div
  cardHolder.appendChild(outerCard);
}



// Get UI variable for search
let searchInput = document.querySelector('#search');

// Search function
function search(e) {
  const text = e.target.value.toLowerCase();

    document.querySelectorAll('.box').forEach(function(task){
        const item = task.firstChild.id;

        if(item.toLowerCase().indexOf(text) != -1)
        {
            task.style.display = 'block';
        } 
          else 
          {
              task.style.display = 'none';
              console.log(item);
          }
    });
}

// // Add event listener for key up to call function
 searchInput.addEventListener('keyup', search);
