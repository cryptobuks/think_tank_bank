<?php


function word_frequency($content) { 

  //What analysis are we doing
  // (int # of words to analyze per phrase) => (string label for that phrase)
  $analysis = array(
  	1=>"Single-Word Analysis",
  );
	
  //strip out bad charecters, just the words, ma'am
  $content = preg_replace( "/(,|\"|\.|\?|:|!|;| - )/", " ", $content );
  $content = preg_replace( "/http/", " ", $content );
  $content = preg_replace( "/\n/", " ", $content );
  $content = preg_replace( "/\s\s+/", " ", $content );
  $content = preg_replace( "/\|/", " ", $content );

  //split content on words
  $content = explode(" ",$content);

  $results = array();
  
  //loop through each analysis group and run our test
  foreach ($analysis as $id=>$title) {
  	$stats = build_stats($content,$id);
  	$results = array_merge($results,$stats);
  }
  
  $clean_results = array();
  //format results in a similar way to Open Calais
  foreach($results as $key=>$value) { 
      $temp = array('name'=>$key, 'frequency'=>$value);
      $clean_results[] = $temp;
  }

  return $clean_results;
}  

/**
 * Parses text and builds array of phrase statistics
 *
 * @param string $input source text
 * @param int $num number of words in phrase to look for
 * @rerturn array array of phrases and counts
 */
function build_stats($input,$num) {
    
    $banned_words_string = "&amp report eu bad good great & > company policy day week govt care 0 good uk tax time today piece politics party national joins growing calls //t a A I The &amp; RT people able about above abroad according accordingly across actually adj after afterwards again against ago ahead ain't all allow allows almost alone along alongside already also although always am amid amidst among amongst an and another any anybody anyhow anyone anything anyway anyways anywhere apart appear appreciate appropriate are aren't around as a's aside ask asking associated at available away awfully back backward backwards be became because become becomes becoming been before beforehand begin behind being believe below beside besides best better between beyond both brief but by came can cannot cant can't caption cause causes certain certainly changes clearly c'mon co co. com come comes concerning consequently consider considering contain containing contains corresponding could couldn't course c's currently dare daren't definitely described despite did didn't different directly do does doesn't doing done don't down downwards during each edu eg eight eighty either else elsewhere end ending enough entirely especially et etc even ever evermore every everybody everyone everything everywhere ex exactly example except fairly far farther few fewer fifth first five followed following follows for forever former formerly forth forward found four from further furthermore get gets getting given gives go goes going gone got gotten greetings had hadn't half happens hardly has hasn't have haven't having he he'd he'll hello help hence her here hereafter hereby herein here's hereupon hers herself he's hi him himself his hither hopefully how howbeit however hundred i'd ie if ignored i'll i'm immediate in inasmuch inc inc. indeed indicate indicated indicates inner inside insofar instead into inward is isn't it it'd it'll its it's itself i've just k keep keeps kept know known knows last lately later latter latterly least less lest let let's like liked likely likewise little look looking looks low lower ltd made mainly make makes many may maybe mayn't me mean meantime meanwhile merely might mightn't mine minus miss more moreover most mostly mr mrs much must mustn't my myself name namely nd near nearly necessary need needn't needs neither never neverf neverless nevertheless new next nine ninety no nobody non none nonetheless noone no-one nor normally not nothing notwithstanding novel now nowhere obviously of off often oh ok okay old on once one ones one's only onto opposite or other others otherwise ought oughtn't our ours ourselves out outside over overall own particular particularly past per perhaps placed please plus possible presumably probably provided provides que quite qv rather rd re really reasonably recent recently regarding regardless regards relatively respectively right round said same saw say saying says second secondly see seeing seem seemed seeming seems seen self selves sensible sent serious seriously seven several shall shan't she she'd she'll she's should shouldn't since six so some somebody someday somehow someone something sometime sometimes somewhat somewhere soon sorry specified specify specifying still sub such sup sure take taken taking tell tends th than thank thanks thanx that that'll thats that's that've the their theirs them themselves then thence there thereafter thereby there'd therefore therein there'll there're theres there's thereupon there've these they they'd they'll they're they've thing things think third thirty this thorough thoroughly those though three through throughout thru thus till to together too took toward towards tried tries truly try trying t's twice two un under underneath undoing unfortunately unless unlike unlikely until unto up upon upwards us use used useful uses using usually v value various versus very via viz vs want wants was wasn't way we we'd welcome well we'll went were we're weren't we've what whatever what'll what's what've when whence whenever where whereafter whereas whereby wherein where's whereupon wherever whether which whichever while whilst whither who who'd whoever whole who'll whom whomever who's whose why will willing wish with within without wonder won't would wouldn't yes yet you you'd you'll your you're yours yourself yourselves you've zero";
    $banned_words = explode(" ", $banned_words_string);
    $stopwords = array("a", '0 ', "about", "above", "above", "across", "after", "afterwards", "again", "against", "all", "almost", "alone", "along", "already", "also","although","always","am","among", "amongst", "amoungst", "amount",  "an", "and", "another", "any","anyhow","anyone","anything","anyway", "anywhere", "are", "around", "as",  "at", "back","be","became", "because","become","becomes", "becoming", "been", "before", "beforehand", "behind", "being", "below", "beside", "besides", "between", "beyond", "bill", "both", "bottom","but", "by", "call", "can", "cannot", "cant", "co", "con", "could", "couldnt", "cry", "de", "describe", "detail", "do", "done", "down", "due", "during", "each", "eg", "eight", "either", "eleven","else", "elsewhere", "empty", "enough", "etc", "even", "ever", "every", "everyone", "everything", "everywhere", "except", "few", "fifteen", "fify", "fill", "find", "fire", "first", "five", "for", "former", "formerly", "forty", "found", "four", "from", "front", "full", "further", "get", "give", "go", "had", "has", "hasnt", "have", "he", "hence", "her", "here", "hereafter", "hereby", "herein", "hereupon", "hers", "herself", "him", "himself", "his", "how", "however", "hundred", "ie", "if", "in", "inc", "indeed", "interest", "into", "is", "it", "its", "itself", "keep", "last", "latter", "latterly", "least", "less", "ltd", "made", "many", "may", "me", "meanwhile", "might", "mill", "mine", "more", "moreover", "most", "mostly", "move", "much", "must", "my", "myself", "name", "namely", "neither", "never", "nevertheless", "next", "nine", "no", "nobody", "none", "noone", "nor", "not", "nothing", "now", "nowhere", "of", "off", "often", "on", "once", "one", "only", "onto", "or", "other", "others", "otherwise", "our", "ours", "ourselves", "out", "over", "own","part", "per", "perhaps", "please", "put", "rather", "re", "same", "see", "seem", "seemed", "seeming", "seems", "serious", "several", "she", "should", "show", "side", "since", "sincere", "six", "sixty", "so", 
    "some", "somehow", "someone", "something", "sometime", "sometimes", "somewhere");
    $banned_words = array_merge($banned_words, $stopwords);
    
	//init array
	$results = array();
	
	//loop through words
	foreach ($input as $key=>$word) {
	    
	    if(!array_search($word, $banned_words) && $word[0] ='#') { 
	      
    		$phrase = '';
		
    		//look for every n-word pattern and tally counts in array
    		for ($i=0;$i<$num;$i++) {
    			if ($i!=0) $phrase .= ' ';
    			    $phrase .= strtolower($input[$key+$i]);
    		}
    		if ( !isset( $results[$phrase] ) ) { 
    			$results[$phrase] = 1;
    		}	
    		else {
    			$results[$phrase] += ($num * 2);
    	    }
    	}		
	}
	
	if ($num < 3) {

		
		foreach ($banned_words as $banned) { 
		    unset($results[$banned]);
		}
		
		
        
	}
	
	//sort, clean, return
	array_multisort($results, SORT_DESC);
	unset($results[""]);
	return $results;
}




?>
