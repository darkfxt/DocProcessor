<html>
<body>
    <pre>    
<?php
require_once('inc/DocxConversor.php');
require_once('inc/mySqlHelper.php');

$server = 'localhost';
$user = 'root';
$pass = '';
$db = 'dbMandinga';

$target_dir = "uploads/";
$upFile = basename( $_FILES["fileToUpload"]["name"]);
$target_file = $target_dir . $upFile;
$path_parts = pathinfo($upFile);
$conv_file = 'converted/' . $path_parts['filename'] . '.txt';
$fileConverted  = fopen($conv_file, 'w');

$ConversorCls = new DocxConversion($target_file);
$mySqlConn = new mySqlHelper($server,$user,$pass,$db);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
/*if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}*/
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
/*if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}*/
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        
        echo "El archivo ". $upFile . " fue subido exitosamente.";
        echo $conv_file;
        $texto = $ConversorCls->convertToText();
        //echo $texto;
        searchAndUpload($texto, $mySqlConn);
        fwrite($fileConverted, $texto);
        fclose($fileConverted);
    } else {
        echo "Lo sentimos, hubo un error intentando subir el archivo.";
    }
}

function docx2text($filename) {
   return readZippedXML($filename, "word/document.xml");
 }

function readZippedXML($archiveFile, $dataFile) {
// Create new ZIP archive
$zip = new ZipArchive;

// Open received archive file
if (true === $zip->open($archiveFile)) {
    // If done, search for the data file in the archive
    if (($index = $zip->locateName($dataFile)) !== false) {
        // If found, read it to the string
        $data = $zip->getFromIndex($index);
        // Close archive file
        $zip->close();
        // Load XML from a string
        // Skip errors and warnings
        $xml = new DOMDocument();
    $xml->loadXML($data);//, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
        // Return data without XML formatting tags
        return strip_tags($xml->saveXML());
    }
    $zip->close();
}

// In case of failure return empty string
return "";
}

echo docx2text("test.docx"); // Save this contents to file

function searchAndUpload ($inTexto, $MySqlConn){
    $separator = "\r\n";
    $line = strtok($inTexto, $separator);
    $i = 0;

    while ($line !== false) {
        if ($i>=4 && $i <=263) {
            $separado = explode('|', $line);
            $salida['time'] = $separado[0];
            $salida['char'] = $separado[1];
            $salida['text'] = $separado[2];
            $query = 'INSERT INTO mdgGuiones VALUES (1,';
            $query .=  $i - 3 . ",'$separado[0]', '$separado[1]', '$separado[2]')";
            //$query = $query1 . $query2;
            echo PHP_EOL . $query . PHP_EOL;
            $MySqlConn->insertLine($query);
        }
        # do something with $line
        $line = strtok( $separator );
        $i++;
    }
}
?>
    </pre>
</body>
</html>