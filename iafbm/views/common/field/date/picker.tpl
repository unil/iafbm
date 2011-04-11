<input type="text" id="<?php echo $d['name'] ?>" name="<?php echo $d['name'] ?>" value="<?php echo $d['value'] ?>"/>

<script>
var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
var dayShort = 2;
window.addEvent('load', function() {
    var id = '<?php echo $d['name'] ?>';
    var picker = new DatePicker('#'+id, {
        timePicker: true,
        format: 'd.m.Y H:i',
        inputOutputFormat: 'd.m.Y H:i',
        allowEmpty: true,
        pickerClass: 'datepicker_vista',
        positionOffset: {
            x: 0, y: 5
        }
    });
});
</script>