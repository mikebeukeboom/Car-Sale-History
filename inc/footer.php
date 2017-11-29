
<hr />
&copy; <?php
$fromYear = 2016;
$thisYear = 2017;
echo $fromYear . (($fromYear != $thisYear) ? '-' . $thisYear : '');?> Mike Beukeboom.
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/jquery.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script>
    $('#autos').DataTable({
        pageLength: 100
    });
</script>
</body>