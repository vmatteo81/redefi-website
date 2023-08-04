<?php

define('TITLE', "Home");
include '../assets/layouts/header.php';
check_verified();

?>


<main role="main" class="container">

    <div class="row">
        <div class="col-sm-9">

            <div class="d-flex align-items-center p-3 mt-5 mb-3 text-white-50 bg-purple rounded box-shadow">
                <img class="mr-3" src="../assets/images/logo.png" alt="" width="48" height="48">
                <div class="lh-100">
                    <h6 class="mb-0 text-white lh-100">Hey there, <?php echo $_SESSION['username']; ?></h6>
                    <small>Last logged in at <?php echo date("m-d-Y", strtotime($_SESSION['last_login_at'])); ?></small>
                </div>
            </div>

            <div class="my-3 p-3 bg-white rounded box-shadow">
                <h6 class="mb-0">Current Account Balance</h6>
                <sub class="text-muted border-bottom border-gray pb-2 mb-0">€ 1500 </sub>

                <div class="media text-muted pt-3">
                    <img data-src="holder.js/32x32?theme=thumb&bg=007bff&fg=007bff&size=1" alt="" class="mr-2 rounded">
                    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                        <strong class="d-block text-gray-dark">@deposited € 1000 </strong> <i class="fa-solid fa-plus fa-bounce" style="color: #7f2bfd;"></i>
                    </p>
                </div>
                <div class="media text-muted pt-3">
                    <img data-src="holder.js/32x32?theme=thumb&bg=007bff&fg=007bff&size=1" alt="" class="mr-2 rounded">
                    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                        <strong class="d-block text-gray-dark">@invested € 400</strong>
                        <i class="fa-solid fa-plus fa-bounce" style="color: #7f2bfd;"></i>
                        <div class="custom-control custom-checkbox mr-sm-2">
                        <input type="checkbox" class="custom-control-input" id="automatic" name="automatic">
                        <label class="custom-control-label" for="automatic">Automatic</label>
                    </div>
                    </p>
                </div>

                <div class="media text-muted pt-3">
                    <img data-src="holder.js/32x32?theme=thumb&bg=007bff&fg=007bff&size=1" alt="" class="mr-2 rounded">
                    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                        <strong class="d-block text-gray-dark">@profit € 100</strong>
                    </p>
                </div>
                
                <small class="d-block text-right mt-3">
                    <a href="#">All updates</a>
                </small>
            </div>

        </div>
    </div>
</main>




    <?php

    include '../assets/layouts/footer.php'

    ?>