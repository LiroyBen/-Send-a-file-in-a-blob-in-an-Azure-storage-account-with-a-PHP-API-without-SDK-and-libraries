<!DOCTYPE html>
<html>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
<body>
<form action="" method="post" enctype="multipart/form-data">
    <h1 style="text-align: center" for="customFile">Envoyer un document</h1>
    <br>
    <!--<input style="width: 450px; height: 36px; margin:0px auto; display:block;" type="text" class="form-control" name="namedoc" placeholder="Nom du document" required/>-->
    <input style="width: 450px; height: 36px; margin:0px auto; display:block;" type="file" class="form-control" name="fileToUpload" id="customFile" required/>
    <br>
    <p style="text-align: center" for="customFile">Note : Le patient sera notifié par email</p>
    <input type="submit" style="margin:0px auto; display:block;" class="btn btn-primary" value="Envoyer" name="submit">
</form>
</body>
</html>
<?php


// name of your container on your Storage Account
$containerName = 'documents';
$storageAccount = 'sthsdoctosup';

if (isset($_POST["submit"])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadpass = 1;
    $FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


    if(false == false) {
        //echo "File is an image - " . $check["mime"] . ".";
        $uploadpass = 1;
    } else {
        echo "<p style='text-align: center; color: red; font-weight: bolder;'>Erreur : Merci de choisir une image</p>";
        $uploadpass = 0;
        exit();
    }

    if ($_FILES["fileToUpload"]["size"] > 2000000) {
        echo "Désolé, votre fichier dépasse 20 Mo";
        $uploadpass = 0;
    }

    //check if the upload file is a image or a pdf
    if($FileType != "jpg" && $FileType != "png" && $FileType != "jpeg" && $FileType != "pdf") {
        echo "Désolé, seulement les photos JPG, JPEG, PNG sont autorisées.";
        $uploadpass = 0;
    } else {
        // If you to upload another file you need to change the Content-Type (find it on internet)
        if($FileType=="pdf"){
            $typefile = 'application/pdf';
        } else {
            $typefile = 'image/png';
        }
    }

    if ($uploadpass == 0) {
        echo "Oups, une erreur est survenue ";
    } else {
        $file = $_FILES["fileToUpload"]["tmp_name"];
        // Give a unique name for a file
        $name = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $blobName = $name . '.' . $FileType;

        uploadBlob($file, $blobName, $containerName, $storageAccount, $typefile);
        echo "Votre document à bien été envoyé";
        echo "<script type='text/javascript'> setTimeout(() => {  console.log(window.close();); }, 2000);</script>";
        exit();
    }
}

function uploadBlob($file, $blobName, $containerName, $storageAccount, $typefile) {
    //get your key on the portal azure
    $accesskey = "cXXX/ouXX7I/U1dpB3XXXXXXXXXXXXun/IVyXX3MiaXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXNZkQ==";
    $filetoUpload = $file;


    $destinationURL = "https://$storageAccount.blob.core.windows.net/$containerName/$blobName";


    $currentDate = gmdate("D, d M Y H:i:s T", time());
    $handle = fopen($filetoUpload, "r");
    $fileLen = filesize($filetoUpload);

    $headerResource = "x-ms-blob-cache-control:max-age=3600\nx-ms-blob-type:BlockBlob\nx-ms-date:$currentDate\nx-ms-version:2015-12-11";
    $urlResource = "/$storageAccount/$containerName/$blobName";

    $arraysign = array();
    $arraysign[] = 'PUT';
    $arraysign[] = '';
    $arraysign[] = '';
    $arraysign[] = $fileLen;
    $arraysign[] = '';
    $arraysign[] = $typefile;
    $arraysign[] = '';
    $arraysign[] = '';
    $arraysign[] = '';
    $arraysign[] = '';
    $arraysign[] = '';
    $arraysign[] = '';
    $arraysign[] = $headerResource;
    $arraysign[] = $urlResource;

    $str2sign = implode("\n", $arraysign);

    $sig = base64_encode(hash_hmac('sha256', urldecode(utf8_encode($str2sign)), base64_decode($accesskey), true));
    $authHeader = "SharedKey $storageAccount:$sig";

    $headers = [
        'Authorization: ' . $authHeader,
        'x-ms-blob-cache-control: max-age=3600',
        'x-ms-blob-type: BlockBlob',
        'x-ms-date: ' . $currentDate,
        'x-ms-version: 2015-12-11',
        'Content-Type: '. $typefile,
        'Content-Length: ' . $fileLen
    ];

    $ch = curl_init($destinationURL);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_INFILE, $handle);
    curl_setopt($ch, CURLOPT_INFILESIZE, $fileLen);
    curl_setopt($ch, CURLOPT_UPLOAD, true);
    $result = curl_exec($ch);

    curl_close($ch);
}