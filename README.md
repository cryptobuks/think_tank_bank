Project Structure 
=================

The root contains the public-facing views and 4 script folders, which do the work of gather and processing data.


Text Analysis Scripts
--------------------- 
These analyse Tweets from the Think Tank community to pick out hot topics. Uses word frequency analysis and the Open Calais Keyword Extraction API 
These scripts are part of final production, and are called by a cron job. 


Twitter Scripts
---------------
Aggregate Twitter data from Think Tank community. They are part of product and are called by cron jobs. 


Thinktank Scripts
-----------------
These scripts crawl think tank websites and gather staff names and publications. They are not running on a cron job but did populate the database initially. 


News Mentions Scripts 
---------------------
This is an exploration of mining the news for mentions of Think Tanks. Developed only experimentally, it runs on Bing News. 


