<?

function getColour($label) { 
    if($label == 'Con') {
        $return = '#6CB4CD';
    }
    else if($label == 'Lab') {
        $return = '#C95A49';
    }

    else if($label == 'LibDem') {
        $return = '#F1C274';
    }

    else if($label == 'Journalist') {
        $return = '#222222';
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