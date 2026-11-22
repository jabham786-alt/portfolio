<?php
require_once __DIR__.'/../includes/bootstrap.php';
if(empty($_SESSION['admin_id']))redirect('login.php');
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);if(!$id){http_response_code(404);exit('File not found.');}
$q=db()->prepare('SELECT attachment FROM orders WHERE id=?');$q->execute([$id]);$row=$q->fetch();if(!$row||!$row['attachment']){http_response_code(404);exit('File not found.');}
$base=realpath(UPLOAD_DIRECTORY);$path=realpath(UPLOAD_DIRECTORY.'/'.basename((string)$row['attachment']));
if(!$base||!$path||!str_starts_with($path,$base.DIRECTORY_SEPARATOR)||!is_file($path)){http_response_code(404);exit('File not found.');}
$mime=(new finfo(FILEINFO_MIME_TYPE))->file($path)?:'application/octet-stream';if(!in_array($mime,['image/jpeg','image/png','image/webp','application/pdf'],true)){http_response_code(403);exit('File type not allowed.');}
header('Content-Type: '.$mime);header('Content-Length: '.filesize($path));header('Content-Disposition: attachment; filename="customer-reference-'.$id.'.'.pathinfo($path,PATHINFO_EXTENSION).'"');header('Cache-Control: private, no-store');header('X-Content-Type-Options: nosniff');readfile($path);exit;
