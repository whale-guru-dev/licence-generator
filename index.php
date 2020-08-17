<?php
require_once 'auth.php';

// HTML authentication
authHTML();

include 'templates/header.php';

//$conn = mysqli_connect("localhost", "root", "", "lc-gen");
$conn = mysqli_connect("localhost", "lcgen", "SyEAY-d-2m", "lcgen");
$result = mysqli_query($conn, "SELECT * FROM licence WHERE uid='" . $_SESSION['uid'] . "'");
$count = mysqli_num_rows($result);

if ($count == 0) {
    $licence = null;
    $expired = false;
    mysqli_close($conn);
} else {
    $licence = mysqli_fetch_row($result);
    mysqli_close($conn);
    $now = date('Y-m-d');

    if (strtotime($licence[5]) - time() < 0) {
        $expired = true;
    } else {
        $expired = false;
    }
}

?>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Licence Generator</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>

<div class="container">

    <div class="starter-template">
        <h1>Licence Genertor</h1>
        <p class="lead">Please Check/Choose your plans.</p>
    </div>

    <?php
    if (!$licence || ($licence && $expired)) {
        ?>
        <div class="row">
            <div class="row row-for-cards">
                <div class="col-md-4 col-sm-12">
                    <div class="card">
                        <!-- <div class="row"> -->
                        <div class="col-12 text-center card-header">
                            <span>Free Trial</span>
                        </div>
                        <hr>
                        <div class="col-12">
                            <ul>
                                <li>Description: This is a free trial.</li>
                                <!--                            <li>Price: $0</li>-->
                            </ul>

                        </div>

                        <div class="col-12 card-footer">
                            <button class="btn btn-warning btn-block" id="purchaseBtn" data-ptype="free">Purchase
                            </button>
                        </div>
                    </div>
                    <!-- </div> -->
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="card">
                        <!-- <div class="row"> -->
                        <div class="col-12 text-center card-header">
                            <span>30 days license</span>
                        </div>
                        <hr>
                        <div class="col-12">
                            <ul>
                                <li>Description: This is a 30 days license.</li>
                                <!--                            <li>Price: $10</li>-->
                            </ul>

                        </div>

                        <div class="col-12 card-footer">
                            <button class="btn btn-info btn-block " id="purchaseBtn" data-ptype="month">Purchase
                            </button>
                        </div>
                    </div>
                    <!-- </div> -->
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="card">
                        <!-- <div class="row"> -->
                        <div class="col-12 text-center card-header">
                            <span>365 days license</span>
                        </div>
                        <hr>
                        <div class="col-12">
                            <ul>
                                <li>Description: This is a 365 days license.</li>
                                <!--                            <li>Price: $100</li>-->
                            </ul>

                        </div>

                        <div class="col-12 card-footer">
                            <button class="btn btn-success btn-block" id="purchaseBtn" data-ptype="year">Purchase
                            </button>
                        </div>
                    </div>
                    <!-- </div> -->
                </div>
            </div>

        </div><!-- /.container -->

        <form style="display: none;" id="purchaseForm" action="subscription.php" method="POST">
            <input type="hidden" name="pType" value="" id="pType">
            <input type="hidden" name="type" value="purchase">
        </form>

    <?php } else { ?>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <div class="card">
                    <!-- <div class="row"> -->
                    <div class="col-12 text-center card-header">
                        <span>Your Subscription Details</span>
                    </div>
                    <hr>
                    <div class="col-12">
                        <ul>
                            <li>Description: <?= $licence[3] ?>.</li>
                            <li>Licence Key: <?= $licence[2] ?></li>
                            <!--                        <li>Price: $100</li>-->
                            <li>Purchased: <?= date("Y-m-d", strtotime($licence[4])) ?></li>
                            <?php if ($licence[3] != 'free') { ?>
                                <li>Expired: <?= date("Y-m-d", strtotime($licence[5])) ?></li> <?php } ?>
                            <li>Status: <?= $expired ? 'Expired' : 'Live' ?></li>

                        </ul>
                    </div>

                    <div class="col-12 card-footer">
                        <button class="btn btn-primary btn-block" id="repurchaseBtn">Re-purchase</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4"></div>
        </div>

        <form style="display: none;" id="repurchaseForm" action="subscription.php" method="POST">
            <input type="hidden" name="type" value="repurchase"/>
        </form>


    <?php } ?>

    <?php
    include 'templates/footer.php';

    if (!$licence || ($licence && $expired)) {
        ?>

        <script>
            $(document).on("click", "#purchaseBtn", function () {
                var ptype = $(this).data('ptype');
                $("#pType").val(ptype);
                $("#purchaseForm").submit();
            });
        </script>

    <?php } else { ?>
        <script>
            $(document).on("click", "#repurchaseBtn", function () {
                $("#repurchaseForm").submit();
            });
        </script>
    <?php } ?>
