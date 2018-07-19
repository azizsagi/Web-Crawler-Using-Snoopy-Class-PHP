<?php
    set_time_limit(0);	
    include_once("Snoopy.class.php");
    include_once("htmlsql.php");
    
    $wsql = new htmlsql();
  
	
   $URL  ="http://www.videogamesplus.ca/index.php?cPath=646";
   $timestamp = mktime(date("H"), '10', '3', date("m")  , date("d")+1, date("Y"));
	
	// fetch results as object and format as HTML links:
   
	$product_added = 0;
	$a = 0;

  // connect to a URL
	if (!$wsql->connect('url', $URL)){
        print 'Error while connecting main catgory: ' . $wsql->error;
        exit;
    }
	
	$cid = explode("?", $URL);
    $catID = substr($cid[1],6); 
	$catID_U= $catID."_";
	
	
	$wsql->isolate_content('<td align="center">','</table>');
	
	if (!$wsql->query('SELECT href,text  FROM a '))
	{
		print "Query error: " . $wsql->error; 
		exit;
	}
	
	
	
	foreach($wsql->fetch_array() as $row)//category of level one
	{
		
		$row['href'] = str_replace("https","http",$row['href']);
	
		
		//Lets connect with this now
	
		$URL_sub_cat_products = explode("?",$row['href']);
		
		
		
		
	   if ($wsql->connect('url', $URL_sub_cat_products[0]))
	   {
				$wsql->isolate_content('<table width="100%" cellspacing="5">','</td></tr></tbody></table></td>');
			
				if (!$wsql->query('SELECT href,text  FROM a '))
				{
					print "Query error: " . $wsql->error; 
					exit;
				}
				
				foreach($wsql->fetch_array() as $row_prod)//products of that category
	            {
			
					$row_prod['href'] = str_replace("https","http",$row_prod['href']);
					
					if(strpos($row_prod['href'],"buy_now"))
					{
						//do nothing
					}
					else
					{
						echo "URL is ".$row_prod['href'];
						$wsql->connect('url', $row_prod['href']);
						
							$wsql->isolate_content('<td valign="top" style="padding-top: 5px;">','</td>');
	
							if (!$wsql->query('SELECT alt,title,src FROM img'))//get price
							{
								print "Query error: " . $wsql->error; 
								exit;
							}
							
							foreach($wsql->fetch_array() as $prod_info)
							{
							   echo "<br>Title is ".$prod_info['title'];
							   echo "<br><img src='http://www.videogamesplus.ca/".$prod_info['src']."'><br><br>";
								
							}
						
						
						
						
					}//end else
					
					
					
				}
				
				
       }
	   else
	   {
		   print 'Error while connecting main catgory: ' . $wsql->error;
		   exit;
	   }
			
	
			
    }



	
	

?>
