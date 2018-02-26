<?php

// Copied from Python code pascal_voc_io.py
class xmlVocReadAnnotationsFile
{	
	
	private $_xmlFilePath;
	private $_boxlist = [];
	private $error_loading = false;
		  
    function __construct($xmlFilePath)
	{
	error_log($xmlFilePath."\n",3,'test.log');
        $this->_xmlFilePath = $xmlFilePath;
		$this->_domDoc = new DOMDocument;
		$this->error_loading = false;
		$previous_value = libxml_use_internal_errors(true);
		
		# Load the XML file
		try		
		{
			// $this->error_loading = true;
			if (!$this->_domDoc->load($xmlFilePath))
			{				
				$this->error_loading = true;
			}
		} 
		catch (Exception $e) 
		{			
			$this->error_loading = true;
		}
		
		libxml_clear_errors();
		libxml_use_internal_errors($previous_value);
	}   	 

	public function hasError()
	{
		// print "Error ? ".$this->error_loading."\n";
		
		return $this->error_loading;	
	}
	
	public function addShape($tag, $xmin, $ymin, $xmax, $ymax)
	 {
		$bndbox = ['x'=>$xmin, 'y'=>$ymin, 'width'=>($xmax-$xmin), 'height'=>($ymax-$ymin)];
		$bndbox['tag'] = $tag;
		array_push($this->_boxlist, $bndbox);
	 }	  

	public function getAnnotations()
	{
		return $this->_boxlist;
	}	
		 
	public function parseXML()
	{
		if ($this->error_loading)
		{
			return false;
		}
		
		// echo "parseXML()\n";
		
		# In case of error return content will be empty and return false        
		$success =  true;
		
		try
		{			
			$filename = $this->_domDoc->getElementsByTagName("filename")->item(0)->nodeValue;
			# echo $filename->nodeValue, PHP_EOL;
			error_log($filename."\n",3,'test.log');
			 
			# Get annotations
			$listeObject = $this->_domDoc->getElementsByTagName('object');
			
			foreach($listeObject as $object)
			{
				$tag = $object->getElementsByTagName("name")->item(0);
				$xmin = $object->getElementsByTagName("xmin")->item(0);
				$ymin = $object->getElementsByTagName("ymin")->item(0);
				$xmax = $object->getElementsByTagName("xmax")->item(0);
				$ymax = $object->getElementsByTagName("ymax")->item(0);
				
				/*echo "name ". $tag->nodeValue . PHP_EOL;
				echo "xmin ". $xmin->nodeValue . PHP_EOL;
				echo "ymin ". $ymin->nodeValue . PHP_EOL;
				echo "xmax ". $xmax->nodeValue . PHP_EOL;
				echo "ymax ". $ymax->nodeValue . PHP_EOL;*/
				
				$this->addShape($tag->nodeValue, $xmin->nodeValue, $ymin->nodeValue,
												$xmax->nodeValue, $ymax->nodeValue);
												
				/*echo PHP_EOL;*/
			}
		}
		catch(Exception $e)
		{
			$success = false;
		}
		
	    return $success;
	}
	
} // End of class

/*header('Content-type: application/json');

$ANNOTATION_DIR = "../images/collection/annotations";
$xml = new xmlVocReadAnnotationsFile($ANNOTATION_DIR.DIRECTORY_SEPARATOR."a380-airbus.xml");
$xml->parseXML();*/

?>
