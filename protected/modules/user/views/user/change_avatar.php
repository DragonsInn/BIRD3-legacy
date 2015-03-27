<h2>Change your profile picture</h2>
<p>
    You can upload an image to this site and it will become your profile picture.
    It's important to note that the maximum dimensions for a profile picture
    are 150 by 150 pixels. You will be getting the chance to crop your image
    before the upload is initialized.
</p>
<hr>
<form name="avatar_upload" method="post" enctype="multipart/form-data" id="change_avatar">
    <input type="file" class="hidden" name="image" id="image_field"/>
    <div class="row">
        <div class="col-md-2 col-xs-6">
            <?=CHtml::image(
                Yii::app()->cdn->baseUrl."/images/Avatar_Generic_150x150.png",
                "User avatar",
                [
                    "class"=>"thumbnail",
                    "style"=>"max-height:150px; max-width:150px;"
                ]
            )?>
        </div>
        <div class="col-md-2 col-xs-6" style="max-height: 150px;">
            <div class="row">
                <button type="button" class="m-btn rnd blue" id="upl_trigger">
                    Upload image <i class="m-icon-swapright m-icon-white" aria-hidden="true"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <input type="text" value="0" id="progress_bar" data-skin="tron">
                </div>
            </div>
        </div>
        <div class="col-md-8 col-xs-12" id="upload_info"></div>
    </div>
</form>

<script>
    jQuery(function($){
        $("#progress_bar").knob({
            min: 0, max: 100,
            readOnly: true,
            thickness: .15,
            width: 100,
            fgColor: "#C0ffff",
            bgColor: "transparent",
            draw: function() {
                // "tron" case. From: http://anthonyterrien.com/knob/
                if(this.$.data('skin') == 'tron') {
                    var a = this.angle(this.cv)         // Angle
                       , sa = this.startAngle           // Previous start angle
                       , sat = this.startAngle          // Start angle
                       , ea                             // Previous end angle
                       , eat = sat + a                  // End angle
                       , r = true;

                    this.g.lineWidth = this.lineWidth;

                    this.o.cursor
                       && (sat = eat - 0.3)
                       && (eat = eat + 0.3);

                    if (this.o.displayPrevious) {
                       ea = this.startAngle + this.angle(this.value);
                       this.o.cursor
                           && (sa = ea - 0.3)
                           && (ea = ea + 0.3);
                       this.g.beginPath();
                       this.g.strokeStyle = this.previousColor;
                       this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
                       this.g.stroke();
                    }

                    this.g.beginPath();
                    this.g.strokeStyle = r ? this.o.fgColor : this.fgColor ;
                    this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
                    this.g.stroke();

                    this.g.lineWidth = 2;
                    this.g.beginPath();
                    this.g.strokeStyle = this.o.fgColor;
                    this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
                    this.g.stroke();

                    return false;
                }
            }
        });

        $("#upl_trigger").click(function(e){
            $("#image_field").click();
        });

        $("#change_avatar").fileupload({
            url: "<?=Yii::app()->request->url?>",
            dataType: "json",
            fileInput: $("#image_field"),
        });
    });
</script>
