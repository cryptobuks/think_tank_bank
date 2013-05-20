<?

function getColour($label) { 
    if($label == 'Con') {
        $return = '#0000ff';
    }
    else if($label == 'Lab') {
        $return = '#ff0000';
    }

    else if($label == 'LibDem') {
        $return = '#FFBB22';
    }

    else if($label == 'Journalist') {
        $return = '#9059ff';
    }
    else { 
        $rnd1 = rand(0,9);
        $rnd2 = rand(0,9);
        $return = "#". $rnd1 ."8d7a" . $rnd2;
    }
    return $return; 
}

?>