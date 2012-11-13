<html>
  <head>
    <title>Force-Directed Layout</title>
    <script type="text/javascript" src="js/protovis.min.js"></script>
    <script type="text/javascript" src="js/data.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js" type="text/javascript"></script>
    <style type="text/css">

body {
  margin: 0;
}

    </style>
  </head>
  <body>
    <script type="text/javascript+protovis">
    
    $.getJSON('graph_data.php', function(data) { 
        
    
        var w = document.body.clientWidth,
            h = document.body.clientHeight,
            colors = pv.Colors.category19();

        var vis = new pv.Panel()
            .width(w)
            .height(h)
            .fillStyle("white")
            .event("mousedown", pv.Behavior.pan())
            .event("mousewheel", pv.Behavior.zoom());

        var force = vis.add(pv.Layout.Force)
            .nodes(data.nodes)
            .links(data.links);

        force.link.add(pv.Line);

        force.node.add(pv.Dot)
            .size(function(d) (d.linkDegree + 4) * Math.pow(this.scale, -1.5))
            .fillStyle(function(d) d.color)
            .strokeStyle(function() this.fillStyle().darker())
            .lineWidth(1)
            .title(function(d) d.nodeName)
            .event("mousedown", pv.Behavior.drag())
            .event("drag", force);

        force.node.add(pv.Label)
                .textAlign("center")
                .text(function(d) d.nodeName);

        vis.render();
    });

    </script>
  </body>
</html>