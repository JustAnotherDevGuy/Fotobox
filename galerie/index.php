<?php
session_start();
$users=['admin'=>['password'=>'admin123!','role'=>'admin'],'user'=>['password'=>'user123!','role'=>'user']];
if(isset($_POST['username'])&&isset($_POST['password'])){
    $username=$_POST['username'];
    $password=$_POST['password'];
    if(isset($users[$username])&&$users[$username]['password']===$password){
        $_SESSION['loggedin']=true;
        $_SESSION['username']=$username;
        $_SESSION['role']=$users[$username]['role'];
        header('Location: index.php');
        exit;
    }else{
        $error="Ungültiger Benutzername oder Passwort.";
    }
}
if(!isset($_SESSION['loggedin'])){
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body{display:flex;justify-content:center;align-items:center;height:100vh;background-color:#f0f0f0;margin:0;font-family:Arial,sans-serif}
        .login-container{background-color:#fff;padding:20px;border-radius:10px;box-shadow:0 0 10px rgba(0,0,0,0.1)}
        .login-container h2{margin-bottom:20px}
        .login-container input{width:100%;padding:10px;margin:10px 0;border:1px solid #ddd;border-radius:5px}
        .login-container button{width:100%;padding:10px;background-color:#007BFF;border:none;border-radius:5px;color:#fff;font-size:16px;cursor:pointer}
        .login-container button:hover{background-color:#0056b3}
        .error{color:red;margin-bottom:10px}
        @media(max-width:600px){
            .login-container{padding:15px}
            .login-container h2{font-size:18px}
            .login-container input,.login-container button{padding:8px;font-size:14px}
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if(isset($error)){echo '<p class="error">'.$error.'</p>';}?>
        <form method="post" action="">
            <input type="text" name="username" placeholder="Benutzername" required>
            <input type="password" name="password" placeholder="Passwort" required>
            <button type="submit">Anmelden</button>
        </form>
    </div>
</body>
</html>
<?php
    exit;
}
$captions=[];
if(file_exists('captions.json')){
    $captions=json_decode(file_get_contents('captions.json'),true);
}
if(isset($_POST['image'])&&isset($_POST['caption'])){
    $image=$_POST['image'];
    $caption=$_POST['caption'];
    $captions[$image]=$caption;
    file_put_contents('captions.json',json_encode($captions));
    header('Location: index.php');
    exit;
}
if(isset($_POST['delete_image'])&&$_SESSION['role']==='admin'){
    $imageToDelete=$_POST['delete_image'];
    if(file_exists($imageToDelete)){
        unlink($imageToDelete);
        unset($captions[$imageToDelete]);
        file_put_contents('captions.json',json_encode($captions));
    }
    header('Location: index.php');
    exit;
}
if(isset($_FILES['image_upload'])&&$_FILES['image_upload']['error']===UPLOAD_ERR_OK){
    $uploadFile=basename($_FILES['image_upload']['name']);
    if(move_uploaded_file($_FILES['image_upload']['tmp_name'],$uploadFile)){
        header('Location: index.php');
        exit;
    }else{
        $uploadError="Fehler beim Hochladen der Datei.";
    }
}
$searchQuery='';
if(isset($_GET['search'])){
    $searchQuery=strtolower(trim($_GET['search']));
}
$imagesPerPage=6;
$page=isset($_GET['page'])?(int)$_GET['page']:1;
$startIndex=($page-1)*$imagesPerPage;
$images=glob("*.{jpg,jpeg,png,gif}",GLOB_BRACE);
if($searchQuery!==''){
    $images=array_filter($images,function($image)use($captions,$searchQuery){
        $imageName=basename($image);
        $caption=isset($captions[$imageName])?strtolower($captions[$imageName]):'';
        return strpos($caption,$searchQuery)!==false;
    });
}
$totalImages=count($images);
$totalPages=ceil($totalImages/$imagesPerPage);
$images=array_slice($images,$startIndex,$imagesPerPage);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fotogalerie</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
    <style>
        body{font-family:Arial,sans-serif;background-color:#f0f0f0;margin:0;padding:0}
        .gallery{display:flex;flex-wrap:wrap;justify-content:center;padding:10px}
        .gallery-item{margin:10px;border:2px solid #ddd;border-radius:5px;overflow:hidden;max-width:200px;text-align:center}
        .gallery img{width:100%;transition:transform 0.2s;cursor:pointer}
        .gallery img:hover{transform:scale(1.1);border-color:#333}
        .download-link{display:block;text-align:center;margin-top:10px;color:#007BFF;text-decoration:none;font-size:16px}
        .download-link:hover{text-decoration:underline}
        .caption-form{margin-top:10px}
        .caption-form input[type="text"]{width:80%;padding:5px;margin:5px 0;border:1px solid #ddd;border-radius:5px}
        .caption-form button{padding:5px 10px;background-color:#007BFF;border:none;border-radius:5px;color:#fff;cursor:pointer}
        .caption-form button:hover{background-color:#0056b3}
        .delete-form{margin-top:10px}
        .delete-form button{padding:5px 10px;background-color:#FF0000;border:none;border-radius:5px;color:#fff;cursor:pointer}
        .delete-form button:hover{background-color:#cc0000}
        .search-container{text-align:center;margin:20px 0}
        .search-container input[type="text"]{padding:10px;width:80%;max-width:400px;border:1px solid #ddd;border-radius:5px}
        .search-container button{padding:10px 20px;background-color:#007BFF;border:none;border-radius:5px;color:#fff;cursor:pointer}
        .search-container button:hover{background-color:#0056b3}
        .pagination{text-align:center;margin:20px 0}
        .pagination a{margin:0 5px;padding:10px 15px;text-decoration:none;color:#007BFF;border:1px solid #ddd;border-radius:5px}
        .pagination a:hover{background-color:#007BFF;color:#fff}
        .pagination .active{background-color:#007BFF;color:#fff;border:1px solid #007BFF}
        .upload-container{text-align:center;margin:20px 0}
        .upload-container input[type="file"]{padding:10px;border:1px solid #ddd;border-radius:5px}
        .upload-container button{padding:10px 20px;background-color:#007BFF;border:none;border-radius:5px;color:#fff;cursor:pointer}
        .upload-container button:hover{background-color:#0056b3}
        @media(max-width:600px){
            .gallery-item{max-width:100%;margin:5px}
            .gallery img{max-width:100%}
            .caption-form input[type="text"]{width:100%}
            .search-container input[type="text"]{width:100%}
        }
    </style>
</head>
<body>
    <h1 style="text-align:center;">Fotogalerie</h1>
    <div class="search-container">
        <form method="get" action="">
            <input type="text" name="search" placeholder="Suche nach Bildunterschriften" value="<?php echo htmlspecialchars($searchQuery);?>">
            <button type="submit">Suchen</button>
        </form>
    </div>
    <div class="upload-container">
        <form method="post" enctype="multipart/form-data" action="">
            <input type="file" name="image_upload" required>
            <button type="submit">Hochladen</button>
        </form>
        <?php if(isset($uploadError)){echo '<p class="error">'.$uploadError.'</p>';}?>
    </div>
    <div class="gallery">
        <?php
        foreach($images as $image){
            $imageName=basename($image);
            $caption=isset($captions[$imageName])?$captions[$imageName]:'';
            echo '<div class="gallery-item">';
            echo '<a href="'.$imageName.'" data-lightbox="gallery" data-title="'.htmlspecialchars($caption).'">';
            echo '<img src="'.$imageName.'" alt="Bild" loading="lazy">';
            echo '</a>';
            echo '<a href="'.$imageName.'" download class="download-link">Download</a>';
            echo '<form class="caption-form" method="post" action="">';
            echo '<input type="hidden" name="image" value="'.$imageName.'">';
            echo '<input type="text" name="caption" placeholder="Bildunterschrift" value="'.htmlspecialchars($caption).'">';
            echo '<button type="submit">Speichern</button>';
            echo '</form>';
            if($_SESSION['role']==='admin'){
                echo '<form class="delete-form" method="post" action="">';
                echo '<input type="hidden" name="delete_image" value="'.$imageName.'">';
                echo '<button type="submit">Löschen</button>';
            }
            echo '</div>';
        }
        ?>
    </div>
    <div class="pagination">
        <?php
        for($i=1;$i<=$totalPages;$i++){
            $activeClass=($i==$page)?'class="active"':'';
            echo '<a href="?page='.$i.'&search='.urlencode($searchQuery).'" '.$activeClass.'>'.$i.'</a>';
        }
        ?>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox-plus-jquery.min.js"></script>
    <script>
        lightbox.option({
            'resizeDuration':200,
            'wrapAround':true,
            'disableScrolling':true,
            'alwaysShowNavOnTouchDevices':true,
            'fadeDuration':200,
            'imageFadeDuration':200,
            'positionFromTop':50,
            'showImageNumberLabel':false,
            'albumLabel':"Bild %1 von %2",
            'disableScrolling':true,
            'fitImagesInViewport':true,
            'maxWidth':800,
            'maxHeight':600,
            'wrapAround':true,
            'closeOnClick':true
        });
    </script>
</body>
</html>