<? 
include('ini.php');

$db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);

?>
<!DOCTYPE html>

    <head>
        <meta charset="utf-8">
        
        <title>Graph</title>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>

        <script src='/js/springy.js' ></script>
        <script src='/js/springyui.js' ></script>
        
        <script>

        $(document).ready(function(){ 
            graph = new Graph();
            nodes       = []; 
            vertices    = [];
            
            $.getJSON('graph_data.php', function(data){ 
                
                console.log(data);
                
                $.each(data.nodes, function(key, val) { 
                   nodes[val] = graph.newNode({label: val});
                });
                
                $.each(data.vertices, function(key, val) {
                    console.log(val[1], val[2]);
                    graph.newEdge(nodes[val[1]], nodes[val[2]]);
                });
                           

                var springy = $('#springydemo').springy({graph: graph});
                
            })
            
        });
        </script>        

    </head>
    <body>
        <?
            $get_body_class = $_SERVER["PHP_SELF"];  
            $get_body_class = explode("/", $get_body_class);
        ?>
        
    <div id='container' class='container_12'   >   
        <canvas id="springydemo" width="960" height="1000" ></canvas>
</div>

    </body>
</html>

<? 
    include('footer.php');

?>