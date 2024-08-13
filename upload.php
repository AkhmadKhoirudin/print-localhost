<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = 'C:\\xampp\\htdocs\\print-server\\uploads\\';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = basename($_FILES['file']['name']);
    $uploadFile = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        $fileExtension = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

        switch ($fileExtension) {
            case 'pdf':
                $chromePath = '"C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe"';
                $cmd = "$chromePath --headless --print-to-pdf-no-header --print-to-pdf=\"$uploadFile\" \"$uploadFile\"";
                break;
            case 'txt':
            case 'log':
            case 'csv':
                $cmd = "notepad /p \"$uploadFile\"";
                break;
            case 'doc':
            case 'docx':
                $cmd = "start /min winword /q /n /mFilePrintDefault /mFileExit \"$uploadFile\"";
                break;
            case 'ppt':
            case 'pptx':
                $cmd = "start /min powerpnt /P \"$uploadFile\"";
                break;
            case 'xls':
            case 'xlsx':
                $cmd = "start /min excel /q /mFilePrintDefault \"$uploadFile\"";
                break;
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                $cmd = "mspaint /pt \"$uploadFile\"";
                break;
            default:
                $cmd = ''; // Tidak ada perintah cetak untuk file jenis ini
                break;
        }

        if ($cmd !== '') {
            shell_exec($cmd);
            echo "<h3>File '$fileName' berhasil diunggah dan dikirim ke printer.</h3>";
        } else {
            echo "<h3>Tipe file tidak didukung untuk pencetakan otomatis.</h3>";
        }

        echo "<a href='index.html' class='btn btn-primary'>Upload File Lain</a>";
    } else {
        echo "<h3>Terjadi kesalahan saat mengunggah file.</h3>";
        echo "<a href='index.html' class='btn btn-danger'>Coba Lagi</a>";
    }
} else {
    echo "<h3>Metode request tidak didukung.</h3>";
}
?>
