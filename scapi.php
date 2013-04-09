<html>
<head>
<meta http-equiv="refresh" content="5">
</head>
<style>
body {
  color: white;
}
td.RD {
	font-size: 14px;
	color: white;
	text-shadow: 2px 1px red;
}
</style>
<body>

<?php

//THIS IS THE SOURCE Server's URL of the XML file to read;;
    $url = "http://manovani.servemp3.com/stats?sid=1";
        $data = file_get_contents($url);


    //ELEMENTS TO RETRIEVE - YOU MUST KNOW THE TAG NAMES OF EACH ELEMENT - Can be improved using an array (next update):

    $currlisteners='';
    $maxlisteners='';
    $author='';
    $currentTrack='';
    $nextTrack='';
    $streamhits='';


    $cc=10; //max counter
    //Counter to start counting the number of items
    $cz=0;

    //The dataset will be stored inside this variable
    $listernerinfo='';

    $depth = array();

 

    /*everything is triggered here - we parse the xml file and call the startElement & endElement functions, then the characterData function to read all content - the xml_parse line launches the procedure taking care of the arguments*/

    $xml_parser = xml_parser_create();

    xml_set_element_handler($xml_parser, "startElement", "endElement");

    xml_set_character_data_handler($xml_parser, "characterData");


    if (!xml_parse($xml_parser, $data))

    die(sprintf("XML error: %s at line %d",	xml_error_string(xml_get_error_code($xml_parser)),xml_get_current_line_number($xml_parser)));


    xml_parser_free($xml_parser);

    //If this line misses, the last element (Steam Hits) will not be attached to the listenerinfo string.
    $listernerinfo.=$streamhits."</td></tr></table>";

   
                            function startElement($parser, $name, $attrs)

                            {

                                //we use global variables to keep track of each element while parsing the whole document

                                global $cc,$cz,$listernerinfo;

                                global $depth;

                                global $tagname;

                                global $currlisteners,$maxlisteners,$author,$currentTrack,$nextTrack,$streamhits;



                                    if($cz < $cc)

                                    {

                                            /* display only if we find all items to sdisplay - updated here*/

                                            if(strlen(trim($currentTrack)) > 0 )

                                            {

                                                    //at this level we have a NEW ITEM -> we increment to items' counter

                                                    if($cz++ >= 1)

                                                    {

                                                            /*We display the dataset in the order we wish (see below to apply a different design)*/

                                                            //$listernerinfo.=$currlisteners.$maxlisteners.$author.$currentTrack.$nextTrack.$streamhits;



$listernerinfo.= "<table><tr><td class='RD'>".
		 "Radio Title:</td><td>".$author.

                 "</td></tr><tr><td class='RD'>Now Playing:</td><td>".$currentTrack.

                 "</td></tr><tr><td class='RD'>Coming up:</td><td>".$nextTrack.

                 "</td></tr><tr><td class='RD'>Listeners:</td><td>".$currlisteners." (of ".$maxlisteners.")".

                 "</td></tr><tr><td class='RD'>Stream Hits:</td><td>";

                                                            

                                                                $currlisteners='';

                                                                $maxlisteners='';

                                                                $author='';

                                                                $currentTrack='';

                                                                $nextTrack='';

                                                                $streamhits='';

                                                    }

                                            }



                                            $tagname=$name;

                                    }



                                    //one level deeper

                                    $depth[$parser]++;

                            }





                            

                            function endElement($parser, $name)

                            {

                                global $depth;

                                global $tagname;

                                    $tagname='';



                                $depth[$parser]--;

                            }





        function characterData($parser, $ddata)

        {

                global $tagname;

                global $cc,$cz,$listernerinfo;

                global $currlisteners,$maxlisteners,$author,$currentTrack,$nextTrack,$streamhits;



                /*For each tag name we receive we only extract the wanted ones

                You can apply different layout to the data                */

                if($cz < $cc)

                {

                        switch($tagname)

                        {

                                case 'CURRENTLISTENERS':if(strlen(trim($ddata)) > 0) $currlisteners= utf8_encode($ddata);break;

                                case 'MAXLISTENERS':if(strlen(trim($ddata)) > 0) $maxlisteners= utf8_encode($ddata);break;

                                case 'SERVERTITLE':if(strlen(trim($ddata)) > 0) $author= utf8_encode($ddata);break;

                                case 'SONGTITLE':if(strlen(trim($ddata)) > 0) $currentTrack= utf8_encode($ddata);break;

                                case 'NEXTTITLE':if(strlen(trim($ddata)) > 0) $nextTrack= utf8_encode($ddata);break;

                                case 'STREAMHITS':if(strlen(trim($ddata)) > 0) $streamhits= utf8_encode($ddata);break;

                                default: break;

                        }

                }

        }


echo $listernerinfo;
?>
</body>
</html>
