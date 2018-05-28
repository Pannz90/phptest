
</div>
<!-- /container (starts at header)-->

<!--  SCRIPTS  -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>

<script type='text/javascript'>
    window.onload = function() {
        initCheckBehavior();
    }
    function initCheckBehavior() {
        var i, a;
        for (i = 0; i < document.links.length; ++i) {
            a = document.links[i];
            if (a.id.indexOf('UncheckAll_') != -1) {
                a.onclick = doCheckBehavior;
                a._CBNAME_ = a.id.substr(11) + '[]';
                a._CBCHECKED_ = false;
            }
            else if (a.id.indexOf('CheckAll_') != -1) {
                a.onclick = doCheckBehavior;
                a._CBNAME_ = a.id.substr(9) + '[]';
                a._CBCHECKED_ = true;
            }
        }
    }
    function doCheckBehavior() {
        var i, cb = document.getElementsByName(this._CBNAME_);
        for (i = 0; i < cb.length; ++i) {
            cb[i].checked = this._CBCHECKED_;
        }
        return false;
    }
</script>

<?php if( $tokens[0] == "product" ): ?>
    <script type="text/javascript">
        $(document).ready(function($) {
            // If type change
            $('#type').change(function(){
                var $selected = $(this).val();
                console.log($selected);
                if( $selected == "" ){
                    $('#noType').removeClass("d-none");
                    $('.attribute-type-input').addClass("d-none");
                    $('.attribute-type-input input').prop('disabled', true);
                } else {
                    $('.attribute-type-input').addClass("d-none");
                    $('.attribute-type-input input').prop('disabled', true);
                    $('.type-'+$selected).removeClass("d-none");
                    $('.type-'+$selected+' input').prop('disabled', false);
                    $('#noType').addClass("d-none");
                }
            });

            // Submit product form
            $('#saveButton').click( function (e) {
                e.preventDefault();
                $('#productForm').submit();
            });
        });
    </script>
<?php endif; ?>

</body>
</html>
