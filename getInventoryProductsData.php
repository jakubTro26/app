<?php

    $idArray=['868'];

    // 716
    //,'868'

    $ids = get_products_ids("https://api.baselinker.com/connector.php",$idArray);



  //  var_dump($productIdsArray);

    $productData = get_products_data("https://api.baselinker.com/connector.php",$productIdsArray);


   

    
   
    // var_dump(key($json['products']));
    
     

 function get_products_ids($url,$ids){

    global $productIdsArray;

    $productIdsArray = array();

    foreach($ids as $id)
        {


           
            
            
                $methodParams = '{
                    "inventory_id":'. $id .'
                
                }';
                $apiParams = [
                    "method" => "getInventoryProductsList",
                    "parameters" => $methodParams
                ];

                $options = array(
                    CURLOPT_RETURNTRANSFER => true,   // return web page
                    CURLOPT_HEADER         => false,  // don't return headers
                    CURLOPT_FOLLOWLOCATION => true,   // follow redirects
                    CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
                    CURLOPT_ENCODING       => "",     // handle compressed
                    CURLOPT_USERAGENT      => "test", // name of client
                    CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
                    CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
                    CURLOPT_TIMEOUT        => 120,    // time-out on response
                    CURLOPT_POST           => 1,
                    CURLOPT_HTTPHEADER     => ["X-BLToken: 2001325-2004269-W4ZO31ZQNSLN0NI8ITHJ3Q1R71L479QBKOGVABB9YBXJXF6BZZQPFOLMN7IT5BJV"],
                    CURLOPT_POSTFIELDS     => http_build_query($apiParams),

                ); 


                $ch = curl_init($url);
                curl_setopt_array($ch, $options);

                $content  = curl_exec($ch);

                $json=json_decode($content);

                $productIdsArray['category' . $id] = array();


               $categoryString = 'category'. $id;

                foreach ($json->products as $product){

                    array_push($productIdsArray[$categoryString],$product->id);
                }

                curl_close($ch);
        
        }


        
    return $content;

 }



 function get_products_data($url,$ids) {

        $keys =   array_keys($ids);
    
        $dom = new DOMDocument('1.0','UTF-8');
        
        $contentDTD='<!DOCTYPE inline_dtd[
            <!ENTITY nbsp "&#160;">
            ]>';


        $dom->formatOutput = true;

        $root = $dom->createElement('items');
        $dom->appendChild($root);

        

        for($i=0;$i<count($ids);$i++){
            

       
            
            $array1 = array_values($ids)[$i];

            for($o=0;$o<count(array_values($ids)[$i]);$o++){
            
           
             $category= str_replace('category', '', $keys[$i]);
     
               
                        $methodParams = '{
                            "inventory_id":' .$category.',
                            "products": [
                                '.$array1[$o] .'
                            ]
                        }';
                        $apiParams = [
                            "method" => "getInventoryProductsData",
                            "parameters" => $methodParams
                        ];


                        $options = array(
                            CURLOPT_RETURNTRANSFER => true,   // return web page
                            CURLOPT_HEADER         => false,  // don't return headers
                            CURLOPT_FOLLOWLOCATION => true,   // follow redirects
                            CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
                            CURLOPT_ENCODING       => "",     // handle compressed
                            CURLOPT_USERAGENT      => "test", // name of client
                            CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
                            CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
                            CURLOPT_TIMEOUT        => 120,    // time-out on response
                            CURLOPT_POST           => 1,
                            CURLOPT_HTTPHEADER     => ["X-BLToken: 2001325-2004269-W4ZO31ZQNSLN0NI8ITHJ3Q1R71L479QBKOGVABB9YBXJXF6BZZQPFOLMN7IT5BJV"],
                            CURLOPT_POSTFIELDS     => http_build_query($apiParams),

                        ); 

                        $ch = curl_init($url);
                        curl_setopt_array($ch, $options);

                        $content  = curl_exec($ch);

                        $PHPcontent = json_decode($content);

                        $property=$array1[$o];

                        


                       // var_dump(array_keys(get_object_vars($PHPcontent->products->$property->variants)));


                    //    foreach($PHPcontent->products->$property->variants as $variant){

                         
                    //     var_dump($variant);
                    //    }

                        //foreach($PHPcontent->products->$property as $pro){

                        
                      ///          var_dump($PHPcontent->products->$property);
                        
                       // }

                      

                       // var_dump($PHPcontent->products->$property->text_fields->description_extra1);

                            $description = $PHPcontent->products->$property->text_fields->description;
                            $description_extra1 = $PHPcontent->products->$property->text_fields->description_extra1;




                            
                          //  var_dump($PHPcontent->products);
                           

                            // foreach($PHPcontent->products->$property->variants as $variant)
                            // {

                            //    // var_dump(array_keys(get_object_vars($variant)));
                            //    //var_dump($PHPcontent->products->$property->variants);
                            //    var_dump(array_keys(get_object_vars($PHPcontent->products->$property->variants)));

                            //     $product = $dom->createElement('product');
                            //     $root->appendChild($product);
                            //     $product->appendChild( $dom->createElement('name', $variant->name) );


                            //     if(strlen($description_extra1)>0)
                            //         {
                            //              $product->appendChild( $dom->createElement('desc_extra_1', $description_extra1) );
                            //         }
                            // }

                 // var_dump(get_object_vars($PHPcontent->products->$property->variants));
                            $variants=get_object_vars($PHPcontent->products->$property->variants);


                          //  var_dump(get_object_vars($PHPcontent->products->$property));
                          

                            $variants_ids = array_keys(get_object_vars($PHPcontent->products->$property->variants));

                          // var_dump($variants_ids);

                       for($k=0;$k<count(get_object_vars($PHPcontent->products->$property->variants));$k++){

                           // var_dump($variants[$k]);
                          // var_dump($variants[$variants_ids[$k]]);
                          $product = $dom->createElement('item');
                          $root->appendChild($product);

                          $varid=$variants_ids[$k];
                          $varid=$varid+5;
                          $product->appendChild( $dom->createElement('id', $varid) );
                          $product->appendChild( $dom->createElement('item_group_id', $property) );
                         $node= $product->appendChild( $dom->createElement('title', $variants[$variants_ids[$k]]->name) );

                        
                          //  var_dump($variants[$variants_ids[$k]]->name);

                           
                          
                         
                         
                            if(strlen($PHPcontent->products->$property->text_fields->{'name|de'})>0){
                                $product->appendChild( $dom->createElement('titleDE', $PHPcontent->products->$property->text_fields->{'name|de'}) );
                            }

                            if(strlen($PHPcontent->products->$property->text_fields->{'name|en'})>0){
                                $product->appendChild( $dom->createElement('titleEN', $PHPcontent->products->$property->text_fields->{'name|en'}) );
                            }


                             if(strlen($description)>0)
                              {
                                         $product->appendChild( $dom->createElement('description', $description) );
                              }
                            if(strlen($description_extra1)>0)
                              {
                                         $product->appendChild( $dom->createElement('description_extra_1', $description_extra1) );
                              }
                       }

                       // $array1 = array_keys(get_object_vars($PHPcontent->products));
                       
                        curl_close($ch);
            }
        }

        //echo '<xmp>'. $dom->saveXML() .'</xmp>';
        $written = $dom->save('/home/master/applications/ancccjahdh/public_html/result.xml') or die('XML Create Error');

        var_dump($written);

        $filepathname = "../result.xml";
        $target = "1";
        $newline = $contentDTD;
        
        $stats = file($filepathname, FILE_IGNORE_NEW_LINES);   
        $offset = array_search($target,$stats) +1;
        array_splice($stats, $offset, 0, $newline);   
        file_put_contents($filepathname, join("\n", $stats));

    return $content;
}

?>