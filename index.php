<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
    table,th,td {
        border:1px solid #ccc;
    }
    </style>
</head>
<body>
    <div class="container">
    <?php
    function sortByGenre($a, $b)
    {
        $a = $a['genre'];
        $b = $b['genre'];
    
        if ($a == $b) return 0;
        return ($a < $b) ? -1 : 1;
    }


    $xml = simplexml_load_file('test.xml') or die('Cant Find xml File');
    // Turn XML object to JSON so we can make it into an associative array
    $json = json_encode( $xml );
    $xml_array = json_decode( $json, true );
    $books_array = $xml_array['book'];

    // want to sort by genre and title - so need arrays for the 'keys' we want to sort by
    $genre = array();
    $title = array();
    for ($i = 0; $i < count($books_array); $i++) {
        $gen[] = $books_array[$i]['genre'];
        $tit[] = $books_array[$i]['title'];  
    }
// now apply sort
array_multisort($gen, SORT_ASC, SORT_STRING, 
        $tit, SORT_ASC,
        $books_array );

    //usort($books_array, 'sortByGenre');
   // array_multisort( array_column($books_array, "genre"), SORT_ASC, $books_array );

    //print_r($books_array);

    $books = array();
    $i = 0;
    foreach( $books_array as $book ){
        $newDate = date("d-m-Y", strtotime($book['publish_date']));
        $books[$i]['genre'] = $book['genre'];
        $books[$i]['title'] =  $book['title'];
        $books[$i]['author'] =  $book['author'];
        $books[$i]['publish_date'] =  $newDate;
        $books[$i]['description'] =  $book['description'];
        $books[$i]['price'] =  $book['price'];
        $i++;
    }

    foreach($books as $key=>$val ){
       // print "$key = $val<br>";
    }

   // print_r($books);
    ?>
    <table style="width:100%;">
        <tr>
            <th>Genre</th>
            <th>Title</th>
            <th>Author</th>
            <th>Publish Date</th>
            <th>Description</th>
            <th>Price</th>
            <th>Sum</th>
        </tr>
    <?php
    $current_genre = null;
    $total_genre = 0;
    $total_sum = 0;

   foreach( $books_array as $book ){
    $newDate = date("D, d M Y", strtotime($book['publish_date']));
    $total_sum += $book['price'];
    //limit description to 50 characters respecting words
    $description = strpos($book['description'], ' ', 50);
    
    if ($current_genre == $book['genre'] || $current_genre == null){
        $total_genre = $total_genre + $book['price'];
    }
    else
    {
        $total_genre = 0;
        $total_genre = $book['price'];
    }

    $current_genre = $book['genre'];
    
        echo '<tr>';
        echo '<td>'.$book['genre'].'</td>';
        echo '<td>'.$book['title'].'</td>';
        echo '<td>'.$book['author'].'</td>';
        echo '<td>'.$newDate.'</td>';
        echo '<td>'.substr($book['description'],0,$description ) .'</td>';
        echo '<td>'.$book['price'].'</td>';
        echo '<td>'.$total_genre.'</td>';
        echo '</tr>';
    }
    ?>
    <tr>
    <th>Total Sum</th>
    <th><?PHP echo $total_sum; ?></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    </tr>
    </table>
    </div>
</body>
</html>