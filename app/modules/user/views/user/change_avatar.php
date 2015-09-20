<h2>Change your profile picture</h2>
<hr>
<div class="row">
    <div class="col-md-2">
        <div class="row">
            <div class="col-md-12 col-md-offset-2">
                <?=CHtml::image(
                    User::avatarUrl(),
                    "User avatar",
                    [
                        "id"=>"avvie_thumb",
                        "class"=>"thumbnail",
                        "style"=>"max-height:150px; max-width:150px;"
                    ]
                )?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" id="filedrop">
                <button type="button"
                    class="col-md-offset-3 ladda-button"
                    data-style="contract"
                    data-color="info"
                id="upload_trigger">
                    <div class="ladda-label" id="ladda_label">
                        Upload
                    </div>
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-10">
        <p>
            You can upload an image to this site and it will become your profile picture.
            It's important to note that the maximum dimensions for a profile picture
            are 150 by 150 pixels. You will be getting the chance to crop your image
            before the upload is initialized.
        </p>
        <p>
            Please note: Your avatar is <b>always</b> visible! Even if you have disabled
            public access to your acount, your avatar is still visible.
        </p>
        <p>
            Please also avoid using explicit content for your avatar, as no content settings
            are applied to avatars either.
        </p>
        <p>
            To upload, click the <b>Browse</b> button and select a file.
        </p>
        <p>
            Alternatively, you can also drag a file onto it.
        </p>
    </div>
</div>
<script>BIRD3.load("upload");</script>
