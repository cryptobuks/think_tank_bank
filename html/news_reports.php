<html>
    <head>
        <title>PHP Bing</title>
    </head>
    <body>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
            Type in a search:

            <input type="text" id="searchText" name="searchText"
                value="<?php
                        if (isset($_POST['searchText']))

                                   {
                            echo($_POST['searchText']);
                        }
                        else
                        {
                            echo('sushi');
                        }
                       ?>"
            />

            <input type="submit" value="Search!" name="submit" id="searchButton" />
            <?php
            
            ini_set('display_errors',1); 
             error_reporting(E_ALL);
             
             ini_set('Open_basedir', '/Users/jimmytidey/projects/think_tank_bank'); 
             
             ini_set('safe_mode', true);
             print_r(ini_get_all());

                if (isset($_POST['submit']))
                {
                    
                    echo "asfasdfsdas";

                $credentials = "Thinktank:h1w583Sm2BWleVp3jGdBdP8VTIje88OlDbd8xTnOfEg=";

                $url= "https://api.datamarket.azure.com/Bing/SearchWeb/Web?Query=%27{keyword}%27";        
                $url=str_replace('{keyword}', urlencode($_POST["searchText"]), $url);
                $ch = curl_init();

            $headers = array(
                    "Authorization: Basic " . base64_encode($credentials)
                );

                $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
             curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
                curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
                curl_setopt($ch, CURLOPT_FAILONERROR, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_AUTOREFERER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
           

                $rs = curl_exec($ch);
                print_r($rs);
                curl_close($ch);
                return $rs;

        }

            ?>
        </form>
    </body>
</html>