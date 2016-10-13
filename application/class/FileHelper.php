<?php
class FileHelper
{
    /*
	 * create random unique string
	 */
	public static function unique_string($length=32, $pool="")
	{
		// set pool of possible char
		if($pool == "")
		{
			$pool = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
			$pool .= "abcdefghijklmnopqrstuvwxyz";
			$pool .= "0123456789";
		}

		mt_srand ((double) microtime() * 1000000);
		$unique_id = "";
		for ($index = 0; $index < $length; $index++)
		{
			$unique_id .= substr($pool, (mt_rand()%(strlen($pool))), 1);
		}

		return strtoupper(date('Ymdhis').sha1($unique_id));
	}

	/*
	 * just get file upload and move to $path, with generated file name
	 */
	public static function checkFileForFlashUpload($fileObj,$path,$file_name = null)
    {
        $mime_type = $fileObj['type'];
        $temp_path = $fileObj['tmp_name'];
        $ext = $fileObj['ext'];

        //not file uploaded via HTTP_POST
        if (!@is_uploaded_file($temp_path)) return false;

        //if no given filename, generate it
        if (is_null($file_name))
            $new_file_name = FileHelper::unique_string().".".$ext;
        else
            $new_file_name = $file_name;

        $new_path = $path.$new_file_name;

		//if no directory then create
		if(!is_dir($path))
		{
			if(mkdir($path) === FALSE) return false;
		}

		if (!@move_uploaded_file($temp_path,$new_path))
            return false;

        return $new_file_name;
    }

	/**
     *
     * @param <type> $fileObj array file object
     * @param <type> $path to save
     * @param <type> $file_name file name
     * @return <type> 0,1,2 or string
     */

	public static function checkFile($fileObj,$path,$file_name = null)
    {
        $mime_type = $fileObj['type'];
        $temp_path = $fileObj['tmp_name'];
        $ext = $fileObj['ext'];
        $size = $fileObj['size'];

        //not file uploaded via HTTP_POST
        if (!@is_uploaded_file($temp_path)) return 0;

        if ($size > (1024*1024)) return 1; //allow 1Mb

        //check MIME type, allow JPG or PNG
        $mimeType = array('image/jpeg' => 'jpg','image/pjpeg' => 'jpg');

        if (!array_key_exists($mime_type,$mimeType))
           return 2;

        //get extension
        $ext = $mimeType[$mime_type];

        //if no given filename, generate it
        if (is_null($file_name))
            $new_file_name = FileHelper::unique_string().".".$ext;
        else
            $new_file_name = $file_name;

        $new_path = $path.$new_file_name;

        if (!@move_uploaded_file($temp_path,$new_path))
            return 3;

        return strval($new_file_name);
    }

}