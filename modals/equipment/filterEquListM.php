<div class="modal fade modal-lg" id="filterEquListModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="filterEquListForm">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel"><strong>Filter Equipment</strong></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-2" id="t_ownerParent">
                        <label for="t_owner">Owner</label>
                        <select class="form-select" name="t_owner" id="t_owner">
                            <option value="" id="defaultOwner" selected>Select Owner</option>
                            <?php
                            $sql1 = "SELECT DISTINCT owner FROM equ_master";
                            $owners = mysqli_query($conn, $sql1);
                            while ($owner = mysqli_fetch_array($owners, MYSQLI_ASSOC)) :;
                            ?>
                                <option value="<?php echo $owner['owner']; ?>">
                                    <?php echo $owner['owner']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <!-- <div class="form-group mb-2" id="locationParent">
                        <label for="location">Location</label>
                        <select class="form-select" name="location" id="location">
                            <option value="" id="defaultLocation" selected>Select Location</option>
                            <?php
                            $sql1 = "SELECT code, description FROM mc_location";
                            $locations = mysqli_query($conn, $sql1);
                            while ($location = mysqli_fetch_array($locations, MYSQLI_ASSOC)) :;
                            ?>
                                <option value="<?php echo $location['code']; ?>">
                                    <?php echo $location['code']; ?> -
                                    <?php echo $location['description']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="form-group mb-2 col-6">
                            <label for="startDate">Start Date</label>
                            <input type="date" class="form-control" name="startDate" id="startDate" placeholder="Start Date">
                        </div>
                        <div class="form-group mb-2 col-6">
                            <label for="endDate">End Date</label>
                            <input type="date" class="form-control" name="endDate" id="endDate" placeholder="End Date">
                        </div>
                    </div> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="filterEquListSubmitBtn" type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.modal').on('shown.bs.modal', function() {
            $(this).find('[autofocus]').focus();
        });
    });

    $('#t_owner').select2({
        dropdownParent: $('#t_ownerParent'),
        width: '100%'
    });
</script>