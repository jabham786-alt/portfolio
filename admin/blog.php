<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/bootstrap.php';
if (empty($_SESSION['admin_id'])) redirect('login.php');
$pdo = db(); $error = ''; $notice = '';
$uploadDir = dirname(__DIR__) . '/assets/uploads/blog';
if (!is_dir($uploadDir)) @mkdir($uploadDir,0755,true);
function blog_slug(string $title): string {
    $base = strtolower(trim(preg_replace('/[^a-z0-9]+/i','-',iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$title) ?: $title),'-'));
    return substr($base ?: 'article',0,150) . '-' . bin2hex(random_bytes(3));
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check(); $action=(string)($_POST['action']??'');
    try {
        if ($action === 'save') {
            $id=(int)($_POST['id']??0); $title=trim((string)($_POST['title']??'')); $excerpt=trim((string)($_POST['excerpt']??'')); $body=trim((string)($_POST['body']??''));
            if ($title==='' || text_length($title)>180 || $body==='') throw new RuntimeException('Title and article content are required.');
            $existing=null;
            if ($id) { $q=$pdo->prepare('SELECT cover_image,slug,status,published_at FROM blog_posts WHERE id=?'); $q->execute([$id]); $existing=$q->fetch(); if(!$existing) throw new RuntimeException('Article not found.'); }
            $image=$existing['cover_image']??null;
            if (!empty($_FILES['cover']['name'])) {
                if (($_FILES['cover']['error']??UPLOAD_ERR_OK)!==UPLOAD_ERR_OK || (int)$_FILES['cover']['size']>5*1024*1024) throw new RuntimeException('Cover upload failed or exceeds 5 MB.');
                $mime=(new finfo(FILEINFO_MIME_TYPE))->file($_FILES['cover']['tmp_name']);
                $ext=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','image/gif'=>'gif'][$mime]??null;
                if(!$ext) throw new RuntimeException('Cover must be JPG, PNG, WEBP or GIF.');
                $image=bin2hex(random_bytes(18)).'.'.$ext;
                if(!move_uploaded_file($_FILES['cover']['tmp_name'],$uploadDir.'/'.$image)) throw new RuntimeException('Could not save cover image.');
            }
            if ($id) {
                $q=$pdo->prepare('UPDATE blog_posts SET title=?,excerpt=?,body=?,cover_image=? WHERE id=?');
                $q->execute([$title,$excerpt?:null,$body,$image,$id]);
            } else {
                $q=$pdo->prepare("INSERT INTO blog_posts(title,slug,excerpt,body,cover_image,status) VALUES(?,?,?,?,?,'draft')");
                $q->execute([$title,blog_slug($title),$excerpt?:null,$body,$image]);
            }
            redirect('blog.php?saved=1');
        }
        if (in_array($action,['publish','draft','delete'],true)) {
            $id=(int)($_POST['id']??0); if($id<1) throw new RuntimeException('Invalid article.');
            if($action==='delete') { $q=$pdo->prepare('SELECT cover_image FROM blog_posts WHERE id=?');$q->execute([$id]);$img=$q->fetchColumn();$pdo->prepare('DELETE FROM blog_posts WHERE id=?')->execute([$id]);if($img && preg_match('/^[a-f0-9]{36}\.(jpg|png|webp|gif)$/',$img)) @unlink($uploadDir.'/'.$img); }
            else { $status=$action==='publish'?'published':'draft';$pdo->prepare("UPDATE blog_posts SET status=?,published_at=IF(?='published',COALESCE(published_at,NOW()),NULL) WHERE id=?")->execute([$status,$status,$id]); }
            redirect('blog.php?updated=1');
        }
    } catch(Throwable $e) { $error=$e->getMessage(); }
}
$edit=null;if(isset($_GET['edit'])){$q=$pdo->prepare('SELECT * FROM blog_posts WHERE id=?');$q->execute([(int)$_GET['edit']]);$edit=$q->fetch()?:null;}
$posts=$pdo->query('SELECT id,title,status,created_at FROM blog_posts ORDER BY created_at DESC LIMIT 200')->fetchAll();
?><!doctype html><html lang="en" data-theme="dark"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Blog manager — Hammad Graphic</title><link rel="stylesheet" href="../assets/css/style.css"></head><body class="admin-dashboard"><main class="admin-container"><div class="admin-title-row"><div><div class="eyebrow">CONTENT STUDIO</div><h1>Design <em>journal.</em></h1><p>Create drafts, edit articles and publish them to the customer app.</p></div><a class="button button-outline" href="index.php">Admin dashboard</a></div><?php if(isset($_GET['saved'])||isset($_GET['updated'])):?><div class="alert alert-success">Changes saved.</div><?php endif;?><?php if($error):?><div class="alert alert-error"><?=e($error)?></div><?php endif; ?><section class="admin-panel"><div class="admin-panel-heading"><div class="eyebrow"><?= $edit?'EDIT ARTICLE':'NEW ARTICLE' ?></div><h2><?= $edit?'Update article':'Create article draft' ?></h2></div><form method="post" enctype="multipart/form-data" class="admin-form"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?=(int)($edit['id']??0)?>"><label>Article title<input name="title" maxlength="180" required value="<?=e($edit['title']??'')?>"></label><label>Short summary<textarea name="excerpt" maxlength="500" rows="2"><?=e($edit['excerpt']??'')?></textarea></label><label>Article content<textarea name="body" rows="12" required><?=e($edit['body']??'')?></textarea></label><label>Cover image <small>JPG, PNG, WEBP or GIF · max 5 MB</small><input type="file" name="cover" accept="image/jpeg,image/png,image/webp,image/gif"></label><button class="button button-primary">Save draft ↗</button></form></section><section class="admin-panel"><div class="admin-panel-heading"><h2>All articles</h2><span><?=count($posts)?> records</span></div><div class="table-scroll"><table class="admin-table"><thead><tr><th>Title</th><th>Status</th><th>Created</th><th>Actions</th></tr></thead><tbody><?php foreach($posts as $p):?><tr><td><?=e($p['title'])?></td><td><?=e($p['status'])?></td><td><?=e(date('d M Y',strtotime($p['created_at'])))?></td><td><a class="table-action" href="?edit=<?=(int)$p['id']?>">Edit</a> <form method="post" style="display:inline"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><input type="hidden" name="id" value="<?=(int)$p['id']?>"><button class="logout-button" name="action" value="<?=$p['status']==='published'?'draft':'publish'?>"><?=$p['status']==='published'?'Unpublish':'Publish'?></button> <button class="logout-button" name="action" value="delete" onclick="return confirm('Delete this article?')">Delete</button></form></td></tr><?php endforeach;?><?php if(!$posts):?><tr><td colspan="4" class="table-empty">No articles yet.</td></tr><?php endif;?></tbody></table></div></section></main></body></html>