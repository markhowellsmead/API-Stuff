<!DOCTYPE html>
<html class="no-js">
<head>
	<script></script>
	<meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>500px JS SDK</title>
    <link rel="stylesheet" href="https://raw.githubusercontent.com/mhmli/css-reset/master/css-reset.css" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
    <script src="sdk.js"></script>
    
    <style>
        
        html, body {
            margin: 0;
            padding: 0;
        }
        
        .grid-500px {
            display: flex;
            flex-wrap: wrap;
        }
        
        .grid-500px .figure {
            margin: 0;
            padding: 0;
            flex: 1 0 33.33333333%;
            align-items: flex-start;
            
        }
        
        .grid-500px .image {
            display: block;
            width: 100%;
        }
        
        .grid-500px::after {
          content: '';
          flex-grow: 1000000000;
        }
        
        @media screen and (max-width: 767px){
            .grid-500px .figure:nth-child(16){
                display: none;
            }
        }
        
        @media screen and (min-width:768px){
            .grid-500px .figure {
                flex-basis: 25%;
            }
        }
        
        @media screen and (min-width: 769px) and (max-width: 1024px){
            .grid-500px .figure:nth-child(16){
                display: none;
            }
        }


        @media screen and (min-width:1024px){
            .grid-500px .figure {
                flex-basis: 20%;
            }
        }

        @media screen and (min-width:1200px){
            .grid-500px .figure {
                flex-basis: 12.5%;
            }
        }
        
        
    </style>
    
</head>
<body>
<div id="grid" class="grid-500px"></div>

<script>
(function($, undefined){

    $(function() {
      _500px.init({
          sdk_key: '<?=getenv('SDK_KEY')?>'
      });

      // Get my user id
      _500px.api('/users', function(response) {
          var me = "permanenttourist";
          var siteurl = "https://500px.com/photo/"

          // Get my favorites
          _500px.api('/photos', {
              feature: 'user',
              username: me,
              rpp: 16,
              image_size: 200
          }, function(response) {
              if (response.data.photos.length == 0) {
                  alert('Nothing found! Please refresh...');
              } else {
                  $.each(response.data.photos, function() {
                      
                      console.log(this);
                      
                      $('#grid').append('<figure class="figure"><a href="' + siteurl + this.id + '" target="_blank"><img class="image" alt="' + this.name + '" src="' + this.image_url + '"></a></figure>');
                  });
              }
          });
      });
  });

})(jQuery);

</script>


</body>
</html>