<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title></title>
  <!-- EXTRA METATAGS -->

  <!-- BOOTSTRAP CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">

</head>
<body>

<header class="page-header py-2 mb-2">
    <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="h2"><?php echo $page_title ?></h1>
                </div>
                <?php if( isset($actionsBlock) && $actionsBlock != "") {?>
                    <div class="col-sm-6 text-right">
                        <?php echo $actionsBlock; ?>
                    </div>
                <?php } ?>
            </div>
            <hr class="my-2">
            <?php if( isset($alerts) && !empty($alerts) ): ?>
                <div class="alerts">
                    <?php foreach ($alerts as $alert): ?>
                        <div class="alert alert-<?php echo $alert["status"]; ?>" role="alert">
                            <?php echo $alert["message"]; ?>
                        </div>
                    <?php endforeach; ?>
                    <?php $_SESSION["alerts"] = ""; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>
