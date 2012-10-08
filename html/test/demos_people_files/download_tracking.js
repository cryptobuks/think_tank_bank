$(document).ready(function(){
    // This code needs to be changed to the code Google Analytics provides for your account
    _uacct = "UA-8845794-1";

    // The slashes are to ensure the period is in the url, the $ is to make sure it is the end of the url, the i is to make it case insensitive.
    filetypes = /\.doc|\.xls|\.exe|\.zip|\.pdf|\.mp3|\.psd/i;
    $("a").each(function(){
        // Track mailto links
        if($(this).attr("href")){
          if ($(this).attr("href").match(/^mailto\:/i)) {
              var url = $(this).attr("href").replace(/^mailto\:/i, "")
              $(this).click(function() {pageTracker._trackPageview("/mailto/" + url);})
          }
          // Track external links
          else if (location.host != this.host.replace(/\:80$/i, "")) {
              var url = $(this).attr("href").replace(/^http\:\/\/(www\.)*/i, "")
              $(this).click(function() {pageTracker._trackPageview("/outgoing/" + url);})
          }
          // Track downloads (links with a given extension)
          else if ($(this).attr("href").match(filetypes)) {
              // The URL needs to be changed for each site this is applied to.
              var url = $(this).attr("href").replace(/^(http\:\/\/)*(www\.)*(demos\.co.uk")*\//i, "")
              $(this).click(function() {pageTracker._trackPageview("/downloads/" + url);})
          }
          else if ($(this).attr("href").match(/\.rss$/i)){
            var url = $(this).attr("href").replace(/^(http\:\/\/)*(www\.)*(demos\.co.uk")*\//i, "")
            $(this).click(function() {pageTracker._trackPageview("/rss_subscriber/" + url);})
          }
        }

    });
});
