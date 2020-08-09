<?php

    $PAGE_NAME = "Download file";
    require_once __DIR__ . "/../../includes/prereqs.php";
    use Ramsey\Uuid\Uuid;

    $file = get_single_file($db, $_GET['file']);
    $request = get_request($db, $file['ticket']);
    $authorised_users = get_subscribers($db, $request);
    $is_authorised = isAuthorised($_SESSION['uuid'], $authorised_users, $request);

    $local_filename = $_ENV['ATTACHMENTS_PATH']."/".$file['id'];
    $remote_filename = $file['filename'];

    if ($is_authorised == true) {
        if (file_exists($local_filename)) {
            //Get file type and set it as Content Type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            header('Content-Type: ' . finfo_file($finfo, $local_filename));
            finfo_close($finfo);

            //Use Content-Disposition: attachment to specify the filename
            header('Content-Disposition: attachment; filename='.$remote_filename);

            //No cache
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');

            //Define file size
            header('Content-Length: ' . filesize($local_filename));

            ob_clean();
            flush();
            readfile($local_filename);
            $alert = array("success", "File download started.");
        } else {
            $alert = array("danger", "The requested file does not exist.");
        }
    } else {
        $alert = array("danger", "You are not authorised to download that file.");
    }
    $newURL = "/view?rid=" . $request['uuid'];
    echo("<script>window.location = '$newURL'</script>");

?>
