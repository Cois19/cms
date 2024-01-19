<?php
include '../../database/connect.php';

$mode = $_GET['mode'];
$queryid = $_GET['queryid'];

if ($mode == 'edittag') {

    $query = "SELECT areacode, subloc, qty, tag_remarks, tperiodque FROM tinventorytag WHERE que = $queryid";
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {

        $areacode = $row['areacode'];
        $subloc = $row['subloc'];
        $qty = $row['qty'];
        $tagremarks = $row['tag_remarks'];
        $tperiodque = $row['tperiodque'];
    }
    ?>

    <input type="text" name="id" id="id" value="<?php echo $queryid ?>" hidden>
    <div class="form-group mb-3" id="areacodeParent">
        <label for="areacode">Area Code</label>
        <select class="form-select" name="areacode" id="areacode">
            <option disabled selected>Select Area Code</option>
            <?php
            $query2 = "SELECT areacode, areaname FROM tarea WHERE tperiodque = $tperiodque";
            $result2 = mysqli_query($conn, $query2);

            while ($row2 = mysqli_fetch_assoc($result2)) {
                $selected = $row2['areacode'] == $areacode ? "selected" : "";
                echo "<option value='" . $row2['areacode'] . "' $selected>" . $row2['areacode'] . ' - ' . $row2['areaname'] . "</option>";
            }
            ?>
        </select>
    </div>
    <div class="form-group mb-3">
        <label for="subloc">Sub Location</label>
        <input type="text" class="form-control" name="subloc" id="subloc" placeholder="Sub Location"
            value="<?php echo $subloc ?>">
    </div>
    <div class="form-group mb-3">
        <label for="qty">Quantity</label>
        <input type="text" class="form-control" name="qty" id="qty" placeholder="Quantity" value="<?php echo $qty ?>">
    </div>
    <div class="form-group mb-3">
        <label for="tagremarks">Remarks</label>
        <textarea type="text" class="form-control" wrap="soft" name="tagremarks" id="tagremarks"
            placeholder="Sub Location"><?php echo $tagremarks ?></textarea>
    </div>
    <script>
        $(document).ready(function () {
            $('#areacode').select2({
                dropdownParent: $('#areacodeParent'),
                width: '100%'
            });
        });
    </script>

    <?php

}
?>