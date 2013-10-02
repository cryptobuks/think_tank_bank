<?

function getColour($label) { 
    if($label == 'Con') {
        $return = '#607ED6';
    }
    else if($label == 'Lab') {
        $return = '#D15A5A';
    }

    else if($label == 'LibDem') {
        $return = '#E6E21C';
    }

    else if($label == 'Journalist') {
        $return = '#000000';
    }
    else { 
        $rnd1 = dechex(rand(170,230));
        $rnd2 = rand(0,9);
        $rnd3 = rand(0,9);        
        $return = "#". $rnd1 . $rnd1 . $rnd1;
    }
    return $return; 
}

?>