<?php
declare(strict_types=1);
require_once __DIR__.'/../includes/bootstrap.php';
if(empty($_SESSION['admin_id'])) redirect('login.php');
$pdo=db(); $error=''; $notice='';
$uploadDir=dirname(__DIR__).'/assets/uploads/portfolio';
$publicPrefix='../assets/uploads/portfolio/';
if(!is_dir($uploadDir) && !mkdir($uploadDir,0755,true) && !is_dir($uploadDir)) $error='Could not create portfolio upload folder.';
function portfolio_upload(array $file,string $kind,string $dir): ?string {
 if(($file['error']??UPLOAD_ERR_NO_FILE)===UPLOAD_ERR_NO_FILE) return null;
 if(($file['error']??UPLOAD_ERR_OK)!==UPLOAD_ERR_OK) throw new RuntimeException('File upload failed. Try a smaller file.');
 if((int)$file['size']>20*1024*1024) throw new RuntimeException('Maximum upload size is 20 MB.');
 $finfo=new finfo(FILEINFO_MIME_TYPE); $mime=$finfo->file($file['tmp_name']);
 $allowed=['image'=>['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','image/gif'=>'gif'],'video'=>['video/mp4'=>'mp4','video/webm'=>'webm'],'audio'=>['audio/mpeg'=>'mp3','audio/wav'=>'wav','audio/x-wav'=>'wav','audio/ogg'=>'ogg','audio/mp4'=>'m4a']];
 if(!isset($allowed[$kind][$mime])) throw new RuntimeException('This file format is not allowed for the selected media type.');
 $name=bin2hex(random_bytes(18)).'.'.$allowed[$kind][$mime];
 if(!move_uploaded_file($file['tmp_name'],$dir.'/'.$name)) throw new RuntimeException('Could not save the uploaded file.');
 return $name;
}
if($_SERVER['REQUEST_METHOD']==='POST'){
 csrf_check(); $action=(string)($_POST['action']??'');
 try {
  if($action==='save'){
   $id=(int)($_POST['id']??0); $title=trim((string)($_POST['title']??'')); $category=trim((string)($_POST['category']??'')); $desc=trim((string)($_POST['description']??'')); $customer=trim((string)($_POST['customer_label']??'')); $date=trim((string)($_POST['project_date']??'')); $kind=(string)($_POST['media_kind']??'image'); $url=trim((string)($_POST['external_url']??'')); $status='draft';
   if($title===''||text_length($title)>160||$category===''||text_length($category)>60) throw new RuntimeException('Title and category are required.');
   if(!in_array($kind,['image','video','audio','link'],true)) throw new RuntimeException('Invalid content setting.');
   if($url!=='' && !filter_var($url,FILTER_VALIDATE_URL)) throw new RuntimeException('Enter a valid external URL.');
   if($kind==='link' && $url==='') throw new RuntimeException('Add a valid project or demo URL.');
   $old=$id?$pdo->prepare('SELECT media_path,status FROM portfolio_items WHERE id=?'):null; if($id){$old->execute([$id]);$oldRow=$old->fetch();if(!$oldRow)throw new RuntimeException('Portfolio item not found.');$oldPath=$oldRow['media_path'];$status=$oldRow['status'];}else{$oldPath=null;$status='draft';}
   $file=portfolio_upload($_FILES['media']??[],$kind,$uploadDir);
   if($kind==='link'){$file=null;} elseif($file===null && !$oldPath) throw new RuntimeException('Choose a media file to upload.');
   $path=$file??$oldPath;
   if($id){$q=$pdo->prepare("UPDATE portfolio_items SET title=?,category=?,description=?,customer_label=?,project_date=?,media_kind=?,media_path=?,external_url=?,status=?,published_at=IF(?='published',COALESCE(published_at,NOW()),NULL) WHERE id=?");$q->execute([$title,$category,$desc,$customer?:null,$date?:null,$kind,$path,$url?:null,$status,$status,$id]);}
   else{$q=$pdo->prepare("INSERT INTO portfolio_items(title,category,description,customer_label,project_date,media_kind,media_path,external_url,status,published_at) VALUES(?,?,?,?,?,?,?,?,?,IF(?='published',NOW(),NULL))");$q->execute([$title,$category,$desc,$customer?:null,$date?:null,$kind,$path,$url?:null,$status,$status]);}
   if($file && $oldPath && $oldPath!==$file && preg_match('/^[a-f0-9]{36}\.(jpg|png|webp|gif|mp4|webm|mp3|wav|ogg|m4a)$/',$oldPath)) @unlink($uploadDir.'/'.$oldPath);
   redirect('portfolio.php?saved=1');
  }
  if($action==='delete'||$action==='publish'||$action==='draft'){
   $id=(int)($_POST['id']??0); if($id<1)throw new RuntimeException('Invalid item.');
   if($action==='delete'){$q=$pdo->prepare('SELECT media_path FROM portfolio_items WHERE id=?');$q->execute([$id]);$p=$q->fetchColumn();$pdo->prepare('DELETE FROM portfolio_items WHERE id=?')->execute([$id]);if($p && preg_match('/^[a-f0-9]{36}\.[a-z0-9]+$/',$p))@unlink($uploadDir.'/'.$p);}
   else{$s=$action==='publish'?'published':'draft';$pdo->prepare("UPDATE portfolio_items SET status=?,published_at=IF(?='published',COALESCE(published_at,NOW()),NULL) WHERE id=?")->execute([$s,$s,$id]);}
   redirect('portfolio.php?updated=1');
  }
 }catch(Throwable $ex){$error=$ex->getMessage();}
}
$edit=null;if(isset($_GET['edit'])){$q=$pdo->prepare('SELECT * FROM portfolio_items WHERE id=?');$q->execute([(int)$_GET['edit']]);$edit=$q->fetch()?:null;}
$items=$pdo->query('SELECT * FROM portfolio_items ORDER BY created_at DESC LIMIT 200')->fetchAll();
?><!doctype html><html lang="en" data-theme="dark"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Portfolio manager — Hammad Graphic</title><link rel="stylesheet" href="../assets/css/style.css"></head><body class="admin-dashboard"><main class="admin-container"><div class="admin-title-row"><div><div class="eyebrow">CONTENT STUDIO</div><h1>Portfolio <em>manager.</em></h1><p>Every new item starts as a draft. Publish only when it is ready.</p></div><a class="button button-outline" href="index.php">Admin dashboard</a></div>
<?php if(isset($_GET['saved'])||isset($_GET['updated'])):?><div class="alert alert-success">Changes saved.</div><?php endif;?><?php if($error):?><div class="alert alert-error"><?=e($error)?></div><?php endif;?>
<section class="admin-panel"><div class="admin-panel-heading"><div><div class="eyebrow"><?= $edit?'EDIT ITEM':'ADD NEW WORK' ?></div><h2><?= $edit?'Update portfolio item':'Create a portfolio draft' ?></h2></div></div>
<form method="post" enctype="multipart/form-data" class="admin-form"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?=(int)($edit['id']??0)?>">
<div class="form-row"><label>Project title<input name="title" maxlength="160" required value="<?=e($edit['title']??'')?>"></label><label>Category<input name="category" maxlength="60" required placeholder="Logo, Social post, Thumbnail…" value="<?=e($edit['category']??'')?>"></label></div>
<label>Description<textarea name="description" rows="4"><?=e($edit['description']??'')?></textarea></label>
<div class="form-row"><label>Customer label (optional)<input name="customer_label" maxlength="120" placeholder="e.g. Business client / permission approved" value="<?=e($edit['customer_label']??'')?>"></label><label>Project date<input type="date" name="project_date" value="<?=e($edit['project_date']??'')?>"></label></div>
<div class="form-row"><label>Media type<select name="media_kind"><?php foreach(['image'=>'Image','video'=>'Video','audio'=>'Audio','link'=>'External demo link'] as $k=>$v):?><option value="<?=$k?>" <?=($edit['media_kind']??'image')===$k?'selected':''?>><?=$v?></option><?php endforeach;?></select></label><label>Upload media <small>Image/video/audio up to 20 MB</small><input type="file" name="media" accept="image/jpeg,image/png,image/webp,image/gif,video/mp4,video/webm,audio/mpeg,audio/wav,audio/ogg,audio/mp4"></label></div>
<label>External video/demo URL (optional)<input type="url" name="external_url" maxlength="500" placeholder="https://…" value="<?=e($edit['external_url']??'')?>"></label>

<p class="admin-footnote">Do not publish private receipts, transaction IDs, customer contact details or work without permission. For a new item, Draft is selected by default.</p><button class="button button-primary">Save portfolio item</button></form></section>
<section class="admin-panel"><div class="admin-panel-heading"><div><div class="eyebrow">CONTENT LIBRARY</div><h2>Saved work</h2></div></div><div class="table-scroll"><table class="admin-table"><thead><tr><th>Title</th><th>Type</th><th>Status</th><th>Created</th><th>Actions</th></tr></thead><tbody><?php foreach($items as $item):?><tr><td><?=e($item['title'])?></td><td><?=e($item['media_kind'])?></td><td><span class="status status-<?=e($item['status'])?>"><?=e($item['status'])?></span></td><td><?=e(date('d M Y',strtotime($item['created_at'])))?></td><td><a class="table-action" href="?edit=<?=(int)$item['id']?>">Edit</a> <form method="post" style="display:inline"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><input type="hidden" name="id" value="<?=(int)$item['id']?>"><button class="logout-button" name="action" value="<?=$item['status']==='published'?'draft':'publish'?>"><?=$item['status']==='published'?'Unpublish':'Publish'?></button> <button class="logout-button" name="action" value="delete" onclick="return confirm('Delete this portfolio item?')">Delete</button></form></td></tr><?php endforeach;?><?php if(!$items):?><tr><td colspan="5" class="table-empty">No portfolio items yet.</td></tr><?php endif;?></tbody></table></div></section></main></body></html>