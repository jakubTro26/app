<?php

    $idArray=['868','801','712','716','741'];
//kubaaa
//,'712','716','741','715','886','714','813','880','713','867','742','796','868','788','72','1184'
//  801 - Kocot Kids Nowy
//  712 - Comad
//  716 - Wróbel
//  741 - Akord
//  715 - Piaski Skrzyniowe
//  886 - Piaski Tapicerka
//  714 - Platforma Mebli
//  813 - Laski Skrzyniowe
//  880 - Laski Tapicerka
//  713 - Furnival
//  867 - Presti\u017c
//  742 - Vivaldi
//  796 - Lemur
//  868 - Marpol
//  788 - Allegro
//  72 - Katalog domy\u015blny
//  1184 - Akord hurtownia




    $categoryArray=[];
    $producentArray=[];
   

    $ids = get_products_ids("https://api.baselinker.com/connector.php",$idArray);

    getInventoryCategories();

    getProducents();

    
  

   // getTime();

  //  var_dump($productIdsArray);

    $productData = get_products_data("https://api.baselinker.com/connector.php",$productIdsArray);


     

    //var_dump($categoryArray);
   
    // var_dump(key($json['products']));
    
     function getTime(){

       
$methodParams = 'null';
$apiParams = [
    "method" => "getOrderTransactionDetails",
    "parameters" => $methodParams
];

$curl = curl_init("https://api.baselinker.com/connector.php");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_HTTPHEADER, ["X-BLToken: 2001325-2004269-W4ZO31ZQNSLN0NI8ITHJ3Q1R71L479QBKOGVABB9YBXJXF6BZZQPFOLMN7IT5BJV"]);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($apiParams));
$response = curl_exec($curl);
     }

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

  function getProducents(){

    global $producentArray;
    $methodParams = '{}';
    $apiParams = [
        "method" => "getInventoryManufacturers",
        "parameters" => $methodParams
    ];
    
    $curl = curl_init("https://api.baselinker.com/connector.php");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ["X-BLToken: 2001325-2004269-W4ZO31ZQNSLN0NI8ITHJ3Q1R71L479QBKOGVABB9YBXJXF6BZZQPFOLMN7IT5BJV"]);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($apiParams));
    $response = curl_exec($curl);

    $value = json_decode($response,true);

     //var_dump($value);

      foreach($value['manufacturers'] as $manu){
          $producentArray[$manu['name']]=$manu['manufacturer_id'];
     }
  }

 function getInventoryCategories(){

    global$categoryArray;
    $methodParams = '{
        "storage_id": "bl_1"
    }';
    $apiParams = [
        "method" => "getCategories",
        "parameters" => $methodParams
    ];
    
    $curl = curl_init("https://api.baselinker.com/connector.php");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ["X-BLToken: 2001325-2004269-W4ZO31ZQNSLN0NI8ITHJ3Q1R71L479QBKOGVABB9YBXJXF6BZZQPFOLMN7IT5BJV"]);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($apiParams));
    $response = curl_exec($curl);

     $value = json_decode($response,true);

   //  var_dump($value['categories'][0]);

     foreach($value['categories'] as $category){
         $categoryArray[$category['name']]=$category['category_id'];
     }
 }


 function get_products_data($url,$ids) {


        global $producentArray;
        global $categoryArray;
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

                        
                              
                        
                       // }

                      

                       // var_dump($PHPcontent->products->$property->text_fields->description_extra1);

                            $description = $PHPcontent->products->$property->text_fields->description;
                            $description_extra1 = $PHPcontent->products->$property->text_fields->description_extra1;




                            
                           //var_dump($PHPcontent->products);
                           

                            // foreach($PHPcontent->products->$property->variants as $variant)
                            // {

                            //    // var_dump(array_keys(get_object_vars($variant)));
                            //var_dump($PHPcontent->products->$property->variants);
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

                            $weight=$PHPcontent->products->$property->weight;
                            
                           // var_dump($weight);

                           // var_dump(get_object_vars($PHPcontent->products));
                          

                            $variants_ids = array_keys(get_object_vars($PHPcontent->products->$property->variants));

                          // var_dump($variants_ids);

                       for($k=0;$k<count(get_object_vars($PHPcontent->products->$property->variants));$k++){
                        
                           // var_dump($variants[$k]);
                          // var_dump($variants[$variants_ids[$k]]);
                          $product = $dom->createElement('item');
                          $root->appendChild($product);
                          $product->appendChild( $dom->createElement('product_id', $variants_ids[$k]) );
                          $product->appendChild( $dom->createElement('item_group_id', $property) );
                          $node= $product->appendChild( $dom->createElement('title', $variants[$variants_ids[$k]]->name) );
                          if(strlen($PHPcontent->products->$property->text_fields->{'name|de'})>0){
                            $product->appendChild( $dom->createElement('titleDE', $PHPcontent->products->$property->text_fields->{'name|de'}) );
                        }

                        if(strlen($PHPcontent->products->$property->text_fields->{'name|en'})>0){
                            $product->appendChild( $dom->createElement('titleEN', $PHPcontent->products->$property->text_fields->{'name|en'}) );
                        }
                          $name= array_search($PHPcontent->products->$property->category_id, $categoryArray);

                          $product->appendChild( $dom->createElement('category', $name));

                          $producent= array_search($PHPcontent->products->$property->manufacturer_id, $producentArray);

                          //var_dump($producent);

                          $product->appendChild( $dom->createElement('producent', $producent));
                          $product->appendChild( $dom->createElement('sku', $variants[$variants_ids[$k]]->sku) );
                          $product->appendChild( $dom->createElement('ean', $variants[$variants_ids[$k]]->ean) );
                          $product->appendChild( $dom->createElement('weight', $weight) );
                          $product->appendChild( $dom->createElement('height',$PHPcontent->products->$property->height ) );
                          $product->appendChild( $dom->createElement('width',$PHPcontent->products->$property->width ) );
                          $product->appendChild( $dom->createElement('length',$PHPcontent->products->$property->length ) );
                          $product->appendChild( $dom->createElement('nr_of_packages',$PHPcontent->products->$property->text_fields->extra_field_246 ) );
                          $product->appendChild( $dom->createElement('handling_time',$PHPcontent->products->$property->text_fields->extra_field_295 ) );
                          $product->appendChild( $dom->createElement('priceDE',$PHPcontent->products->$property->prices->{'73'} ) );
                          $product->appendChild( $dom->createElement('priceFR',$PHPcontent->products->$property->prices->{'74'} ) );
                          $product->appendChild( $dom->createElement('priceIT',$PHPcontent->products->$property->prices->{'75'} ) );
                          $product->appendChild( $dom->createElement('priceES',$PHPcontent->products->$property->prices->{'76'} ) );
                          $product->appendChild( $dom->createElement('pricePL',$PHPcontent->products->$property->prices->{'78'} ) );
                          $product->appendChild( $dom->createElement('priceUK',$PHPcontent->products->$property->prices->{'79'} ) );
                          $product->appendChild( $dom->createElement('description',$PHPcontent->products->$property->text_fields->description ) );
                          $product->appendChild( $dom->createElement('description_extra1',$PHPcontent->products->$property->text_fields->description_extra1 ) );
                          $product->appendChild( $dom->createElement('description_extra2',$PHPcontent->products->$property->text_fields->description_extra2 ) );
                          $product->appendChild( $dom->createElement('description_extra3',$PHPcontent->products->$property->text_fields->description_extra3 ) );
                          $product->appendChild( $dom->createElement('description_extra4',$PHPcontent->products->$property->text_fields->description_extra4 ) );
                          $product->appendChild( $dom->createElement('bp5',$PHPcontent->products->$property->text_fields->extra_field_11 ) );
                          $product->appendChild( $dom->createElement('searchterms',$PHPcontent->products->$property->text_fields->extra_field_278 ) );
                          $product->appendChild( $dom->createElement('image1',$PHPcontent->products->$property->images->{'1'} ) );
                          $product->appendChild( $dom->createElement('image2',$PHPcontent->products->$property->images->{'2'} ) );
                          $product->appendChild( $dom->createElement('image3',$PHPcontent->products->$property->images->{'3'} ) );
                         //   var_dump($variants_ids[$k]);

                         
                         
                         
                         





                             if(strlen($description)>0)
                              {
                                         
                              }
                            if(strlen($description_extra1)>0)
                              {
                                         
                              }


                              

                             

                            
                       }

                       // $array1 = array_keys(get_object_vars($PHPcontent->products));
                       
                        curl_close($ch);
            }
        }

        //echo '<xmp>'. $dom->saveXML() .'</xmp>';
        $written = $dom->save('/home/master/applications/ancccjahdh/public_html/result.xml') or die('XML Create Error');

        

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
