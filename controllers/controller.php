<?php

$action = $_GET["action"] ?? "display";

switch ($action) {

  case 'register':
    include "../models/UserManager.php";
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['passwordRetype'])) {
      $errorMsg = NULL;
      if (!IsNicknameFree($_POST['username'])) {
        $errorMsg = "Nickname already used.";
      } else if ($_POST['password'] != $_POST['passwordRetype']) {
        $errorMsg = "Passwords are not the same.";
      } else if (strlen(trim($_POST['password'])) < 8) {
        $errorMsg = "Your password should have at least 8 characters.";
      } else if (strlen(trim($_POST['username'])) < 4) {
        $errorMsg = "Your nickame should have at least 4 characters.";
      }
      if ($errorMsg) {
        include "../views/RegisterForm.php";
      } else {
        $userId = CreateNewUser($_POST['username'], $_POST['password']);
        $_SESSION['userId'] = $userId;
        header('Location: ?action=display');
      }
    } else {
      include "../views/RegisterForm.php";
    }
    break;

  case 'logout':
    if (isset($_SESSION['userId'])) {
      unset($_SESSION['userId']);
    }
    header('Location: ?action=display');
    break;

  case 'login':
    include "../models/UserManager.php";
    if (isset($_POST['username']) && isset($_POST['password'])) {
      $userId = GetUserIdFromUserAndPassword($_POST['username'], $_POST['password']);
      if ($userId > 0) {
        $_SESSION['userId'] = $userId;
        header('Location: ?action=display');
      } else {
        $errorMsg = "Wrong login and/or password.";
        include "../views/LoginForm.php";
      }
    } else {
      include "../views/LoginForm.php";
    }
    break;

  case 'newMsg':
    include "../models/PostManager.php";
    if (isset($_SESSION['userId']) && isset($_POST['msg'])) {
      CreateNewPost($_SESSION['userId'], $_POST['msg']);
    }
    header('Location: ?action=display');
    break;
  case 'newComment':
    // code...
    break;

  case 'display':
  default:
    include "../models/PostManager.php";
    if (isset($_GET['search'])) {
      $posts = SearchInPosts($_GET['search']);
    } else {
      $posts = GetAllPosts();
    }

    include "../models/CommentManager.php";

    foreach ($posts as $key => $post) {
      # code...
      $idPost = $post['id'];
      $commentsForOnePost = GetAllCommentsFromPostId($idPost);
      $comments[$idPost] = $commentsForOnePost;
    }

    include "../views/DisplayPosts.php";
    break;
}
