<?php

define('TITLE', "My Kyc");
include '../assets/layouts/header.php';
include './includes/nationality.php';
check_verified();

//XSS filter for session variables
function xss_filter($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>


<div class="container">
    <div class="row">
        <div class="col-lg-7">
            <form class="form-auth" action="includes/profile-edit.inc.php" method="post" enctype="multipart/form-data" autocomplete="off">

                <?php insert_csrf_token(); ?>
                <h6 class="h3 mt-3 mb-3 font-weight-normal text-muted text-center">My Kyc</h6>    

                <?php 
                    if ($_SESSION['kyc_message'] == "")
                    {
                        echo "<h5 class=\"h3 mt-3 mb-3 text-center text-red\">Not Verified</h5>";    
                    } 
                    else if ($_SESSION['kyc_message'] == "checking")
                    {
                        echo "<h5 class=\"h3 mt-3 mb-3 text-center text-red\">Checking your documents</h5>";    
                    } else if ($_SESSION['kyc_message'] == "verified")
                    {
                        echo "<h5 class=\"h3 mt-3 mb-3 text-center text-green\">Verified (Expires at ".xss_filter($_SESSION['kyc_expires_at']).")</h5>";    
                    } else if ($_SESSION['kyc_message'] == "expired")
                    {
                        echo "<h5 class=\"h3 mt-3 mb-3 text-center text-orange\">Expired plz upload new documents</h5>";    
                    } 
                    else {
                        echo "<h5 class=\"h3 mt-3 mb-3 text-center text-orange\">".xss_filter($_SESSION['kyc_message'])."</h5>";    
                    }
                ?>    
               
               
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" class="form-control" placeholder="First Name" value="<?php echo xss_filter($_SESSION['first_name']); ?>">
                    <sub class="text-danger">
                        <?php
                            if (isset($_SESSION['ERRORS']['first_name']))
                                echo $_SESSION['ERRORS']['first_name'];

                        ?>
                    </sub>
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Last Name" value="<?php echo xss_filter($_SESSION['last_name']); ?>">
                    <sub class="text-danger">
                        <?php
                            if (isset($_SESSION['ERRORS']['last_name']))
                                echo $_SESSION['ERRORS']['last_name'];

                        ?>
                    </sub>
                </div>

                <div class="form-group mt-4">
                    <label for="birthday">Date of Birth</label>
                    <input type="date" id="birthday" name="birthday" class="form-control" placeholder="Birthday" value="<?php echo xss_filter($_SESSION['birthday']); ?>">
                    <sub class="text-danger">
                        <?php
                            if (isset($_SESSION['ERRORS']['birthday']))
                                echo $_SESSION['ERRORS']['birthday'];

                        ?>
                    </sub>
                </div>

                <div class="form-group mt-4">
                    <label for="nationality">Nationality</label><br>
                    <?php echo nationalityDropdown(); ?>
                    <sub class="text-danger">
                        <?php
                            if (isset($_SESSION['ERRORS']['nationality']))
                                echo $_SESSION['ERRORS']['nationality'];

                        ?>
                    </sub>
                </div>

                <div class="form-group mt-4">
                    <label for="iddoc">Proof of Identity (passport,driver license..)</label><br>
                    <div class="picCard text-center">
                        <div class="avatar-upload">
                            <div class="avatar-preview text-center">
                                <div id="idDocPreview" style="background-image: url( ../assets/uploads/users/<?php echo $_SESSION['id_doc_image'] ?> );">
                                </div>
                            </div>
                            <div class="avatar-edit">
                                <input name='idDocAvatar' id="idDocAvatar" class="fas fa-pencil" type='file' />
                                <label for="idDocAvatar">+</label>    
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <sub class="text-danger">
                            <?php
                                if (isset($_SESSION['ERRORS']['idDocError']))
                                    echo $_SESSION['ERRORS']['idDocError'];

                            ?>
                        </sub>
                    </div>
                    <div class="text-center">
                        <small class="text-success font-weight-bold">
                            <?php
                                if (isset($_SESSION['STATUS']['editstatus']))
                                    echo $_SESSION['STATUS']['editstatus'];
                            ?>
                        </small>
                    </div>
                 </div>

                 <div class="form-group mt-4">
                   <label for="addrdoc">Proof of Address (gas bill,telephone bill...)</label><br>
                    <div class="picCard text-center">
                        <div class="avatar-upload">
                            <div class="avatar-preview text-center">
                                <div id="proAddrPreview" style="background-image: url( ../assets/uploads/users/<?php echo $_SESSION['proof_addr_image'] ?> );">
                                </div>
                            </div>
                            <div class="avatar-edit">
                                <input name='proAddrAvatar' id="proAddrAvatar" class="fas fa-pencil" type='file' />
                                <label for="proAddrAvatar">+</label>    
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <sub class="text-danger">
                            <?php
                                if (isset($_SESSION['ERRORS']['proAddrError']))
                                    echo $_SESSION['ERRORS']['proAddrError'];

                            ?>
                        </sub>
                    </div>
                    <div class="text-center">
                        <small class="text-success font-weight-bold">
                            <?php
                                if (isset($_SESSION['STATUS']['editstatus']))
                                    echo $_SESSION['STATUS']['editstatus'];
                            ?>
                        </small>
                    </div>
                 </div>

                 <div class="form-group mt-4">
                   <label for="addrdoc">Upload a video of your face and a paper with antiphishing code</label> <label class="text-red"> <?php echo $_SESSION['antiphishing']?></label><br>
                    <div class="picCard text-center">
                        <div class="avatar-upload">
                            <div class="avatar-preview text-center">
                                <div id="videoPreview" style="background-image: url( ../assets/uploads/users/<?php echo $_SESSION['proof_addr_image'] ?> );">
                                </div>
                            </div>
                            <div class="avatar-edit">
                                <input name='videoAvatar' id="videoAvatar" class="fas fa-pencil" type='file' />
                                <label for="videoAvatar">+</label>    
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <sub class="text-danger">
                            <?php
                                if (isset($_SESSION['ERRORS']['videoError']))
                                    echo $_SESSION['ERRORS']['videoError'];

                            ?>
                        </sub>
                    </div>
                    <div class="text-center">
                        <small class="text-success font-weight-bold">
                            <?php
                                if (isset($_SESSION['STATUS']['editstatus']))
                                    echo $_SESSION['STATUS']['editstatus'];
                            ?>
                        </small>
                    </div>
                 </div>
                 <input class="btn btn-lg btn-primary btn-block mb-5 <?php if ($_SESSION['kyc_message'] == "verified") {echo "disabled";} ?>" type="submit" name='update-profile' value= 'Request Validation'/>
                
            </form>

        </div>
        <div class="col-md-4">

        </div>
    </div>
</div>

<?php

include '../assets/layouts/footer.php';

?>

<script type="text/javascript">
    function readURL(input,input2) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                input2.css('background-image', 'url(' + e.target.result + ')');
                input2.hide();
                input2.fadeIn(650);

            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#idDocAvatar").change(function() {
        console.log("here");
        readURL(this,$('#idDocPreview'));
    });
    $("#proAddrAvatar").change(function() {
        console.log("here");
        readURL(this,$('#proAddrPreview'));
    });
    $("#videoAvatar").change(function() {
        console.log("here");
        readURL(this,$('#videoPreview'));
    });
</script>
