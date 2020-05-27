<?php

$action = $_GET["action"] ?? "display";

switch ($action) {

  case 'register':
    // code...
    break;

  case 'logout':
    // code...
    break;

  case 'login':
    // code...
    break;

  case 'newMsg':
    // code...
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
