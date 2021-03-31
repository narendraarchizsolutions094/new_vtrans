<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

require FCPATH.'third_party/aws/vendor/autoload.php';

use Aws\S3\S3Client;




class Aws {



   	public $bucketName = 'v-trans';
	
	public $client;
	
	public $folder = "document/";
	
	public function __construct(){
		
		$this->client =  new S3Client([
								'version' => 'latest',
								'region' => 'ap-south-1',
								'credentials' => [
									'key'    => 'AKIAINIUUJMBJMU3T7NA',
									'secret' => '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'
								]
		]);
	
	}
	
	public function upload($folder, $file_Path){
		
		$folder = (empty($folder)) ? $this->folder : $folder;
		$key = basename($file_Path);
		try {

				$result = $this->client->putObject([

					'Bucket' => $this->bucketName,

					'Key'    => $folder."/".$key,

					'Body'   => fopen($file_Path, 'r'),

					'ACL'    => 'public-read',

				]);

				$result->get('ObjectURL');
			return true;	
			} catch (Aws\S3\Exception\S3Exception $e) {

				 "There was an error uploading the file.\n";

				$e->getMessage();
			return false;
			}
		
	}
	public function uploadinfolder($folder, $file_Path){
		
		$key = basename($file_Path);
		try {

				$result = $this->client->putObject([

					'Bucket' => $this->bucketName,

					'Key'    => $folder."/".$key,

					'Body'   => fopen($file_Path, 'r'),

					'ACL'    => 'public-read',

				]);

				$result->get('ObjectURL');
			return true;	
			} catch (Aws\S3\Exception\S3Exception $e) {

				 "There was an error uploading the file.\n";

				$e->getMessage();
			return false;
			}
		
	}
	
	public function makeurl($file){
		
		
		return "https://phpcrm.s3.ap-south-1.amazonaws.com/file_uplode/".$file;
	}
	
	public function getfile($key, $filename){
		
		
		$this->download($key, $filename);
	}
	
	function generatename ($name = ""){
		
		return strtotime(date("y-h-d h:i:s"))."_".$name;
		
	}
	
	function removefile($path){
		
		if(unlink($path)){
			return true;
		}else{
			return false;
		}
	}
	
	function download($object_key, $file_name = '') {
		
		 if ( empty($file_name) ) {
			$file_name = basename($file_name);
		  }
		  $cmd = $this->client->getCommand('GetObject', [
			'Bucket'                        => $this->bucketName,
			'Key'                           => $object_key,
			'ResponseContentDisposition'    => "attachment; filename=\"{$file_name}\"",
		  ]);
		  $signed_url = $this->client->createPresignedRequest($cmd, '+240 minutes') // \GuzzleHttp\Psr7\Request
						->getUri() // \GuzzleHttp\Psr7\Uri
						->__toString();
		return $signed_url; 					
		 // header("Location: {$signed_url}");
	}
	
	/*	function create_bucket($client, $bucketName){
		try {
			$result = $client->createBucket([
				'Bucket' => $bucketName, 
				'ACL'    => 'public-read',
			]);
			
			echo "<pre>";
				print_r($result);
			echo "</pre>";
			
		} catch (Aws\S3\Exception\S3Exception $e) {
			
			$xml = new SimpleXMLElement($e->getMessage());
			
		}
	} */
/*	function getbucket($client){
	
	try {
		$result = $client->getObject([
			'Bucket' => $bucketName,
			'Key'    => "file_uplode/DSC_0798.JPG"
		]);

		 header('Content-Description: File Transfer');
		
			header('Content-Type: ' . $result->ContentType);
			header('Content-Disposition: attachment; filename=DSC_0798.JPG');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');

			
			echo $result['body'];

		}catch (Aws\S3\Exception\S3Exception $e) {
				
				echo "Erroers";
				echo "<pre>";
					print_r($e->getMessage());
				echo "</pre>";	
			} 
		} */
	
	
}
?>