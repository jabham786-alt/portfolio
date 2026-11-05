<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';
if($_SERVER['REQUEST_METHOD']!=='POST')redirect('order.php');
csrf_check();
if(!empty($_POST['website'])){flash('order_error','Please leave the hidden website field empty and submit the order form again.');redirect('order.php');}
if(!empty($_SESSION['last_order_submit'])&&time()-(int)$_SESSION['last_order_submit']<30){flash('order_error','Please wait a moment before sending another request.');redirect('order.php');}
$name=trim((string)($_POST['customer_name']??''));$email=trim((string)($_POST['email']??''));$phone=trim((string)($_POST['whatsapp']??''));$service=trim((string)($_POST['service']??''));$budget=trim((string)($_POST['budget']??''));$deadline=trim((string)($_POST['deadline']??''));$brief=trim((string)($_POST['brief']??''));
$allowed=['Brand identity','Social media design','Print & business stationery','YouTube thumbnails','Custom creative design'];
if($name===''||text_length($name)>100||!filter_var($email,FILTER_VALIDATE_EMAIL)||text_length($email)>190||$phone===''||text_length($phone)>30||!in_array($service,$allowed,true)||$brief===''||text_length($brief)>5000){flash('order_error','Please check the required fields and try again.');redirect('order.php');}
if($budget!==''&&text_length($budget)>60){flash('order_error','Please shorten the budget field.');redirect('order.php');}
if($deadline!==''){$date=DateTime::createFromFormat('Y-m-d',$deadline);if(!$date||$date->format('Y-m-d')!==$deadline||$date<new DateTime('today')){flash('order_error','Please choose a valid future deadline.');redirect('order.php');}}else{$deadline=null;}
$storedFile=null;
if(isset($_FILES['attachment'])&&(int)($_FILES['attachment']['error']??UPLOAD_ERR_NO_FILE)!==UPLOAD_ERR_NO_FILE){
 $file=$_FILES['attachment'];if((int)$file['error']!==UPLOAD_ERR_OK||(int)$file['size']<1||(int)$file['size']>MAX_UPLOAD_BYTES){flash('order_error','The attachment could not be uploaded. Please use a file smaller than 4 MB.');redirect('order.php');}
 $mime=(new finfo(FILEINFO_MIME_TYPE))->file((string)$file['tmp_name']);$types=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','application/pdf'=>'pdf'];
 if(!isset($types[$mime])){flash('order_error','Only JPG, PNG, WEBP and PDF files are accepted.');redirect('order.php');}
 if(!is_dir(UPLOAD_DIRECTORY)&&!mkdir(UPLOAD_DIRECTORY,0750,true)&&!is_dir(UPLOAD_DIRECTORY)){flash('order_error','File storage is unavailable. Please submit without an attachment or contact us on WhatsApp.');redirect('order.php');}
 $storedFile=bin2hex(random_bytes(18)).'.'.$types[$mime];if(!move_uploaded_file((string)$file['tmp_name'],UPLOAD_DIRECTORY.'/'.$storedFile)){flash('order_error','The attachment could not be saved. Please try again without it.');redirect('order.php');}
}
try{$pdo=db();$code='HG-'.date('ymd').'-'.strtoupper(bin2hex(random_bytes(3)));$stmt=$pdo->prepare('INSERT INTO orders (order_code,customer_name,email,whatsapp,service,budget,deadline,brief,attachment,status,created_at,updated_at) VALUES (:code,:name,:email,:phone,:service,:budget,:deadline,:brief,:attachment,:status,NOW(),NOW())');$stmt->execute([':code'=>$code,':name'=>$name,':email'=>$email,':phone'=>$phone,':service'=>$service,':budget'=>$budget?:null,':deadline'=>$deadline,':brief'=>$brief,':attachment'=>$storedFile,':status'=>'pending']);$_SESSION['last_order_submit']=time();redirect('order.php?success=1&ref='.rawurlencode($code));}
catch(PDOException $exception){if($storedFile&&is_file(UPLOAD_DIRECTORY.'/'.$storedFile))@unlink(UPLOAD_DIRECTORY.'/'.$storedFile);error_log('Hammad Graphic database insert error: '.$exception->getMessage());flash('order_error','Your order was NOT saved. The database connection or orders table failed. The website owner should run the private check at /admin/diagnose.php. Please do not assume the request was received.');redirect('order.php');}
catch(Throwable $exception){if($storedFile&&is_file(UPLOAD_DIRECTORY.'/'.$storedFile))@unlink(UPLOAD_DIRECTORY.'/'.$storedFile);error_log('Hammad Graphic order processing error: '.$exception->getMessage());flash('order_error','Your order was NOT saved because the website setup is incomplete. The website owner should run the private check at /admin/diagnose.php. Please do not assume the request was received.');redirect('order.php');}
