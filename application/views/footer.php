	</div>
</main>


<script src="js/jquery-1.10.2.min.js"></script>
<script src="js/modernizr.custom.28468.js"></script>
<script src="js/chosen.jquery.min.js"></script>
<script src="js/easyNotify.js"></script>
<script src="js/DataTable/jquery.dataTables.min.js"></script>
<script src="js/DataTable/dataTables.buttons.min.js"></script>
<script src="js/DataTable/buttons.flash.min.js"></script>
<script src="js/jquery.simpleFilePreview.js"></script>
<!-- <script src="js/less-1.3.3.min.js"></script> -->
<script>
$(function(){

	$("table.data").DataTable({
		dom: 'Bfrtip',
		pageLength : 25,
		buttons : [
			{
				extend : 'excel',
				text : 'Export to Excel',
				customize: function( xlsx ) {
	                var sheet = xlsx.xl.worksheets['sheet1.xml'];
	 
	                $('row c[r^="C"]', sheet).attr( 's', '2' );
	            }
			}
		]
	});

	$("nav>ul>li>a").mouseup(function(){
		$("ul.submenu").slideUp();
		$("li").removeClass("clicked");
		$(this).parent("li").addClass("clicked");
		$(this).next("ul.submenu").slideDown();
	});


	$(".add-button").mousedown(function(){
		$(".dt-part").last().clone(true).appendTo("tbody");
		$(".dt-part").last().find("input, select").val("");
	});

	$(".delete-button").click(function(e){
		e.preventDefault();
		var x = confirm("Are you sure?");
		if(x == true){
			var targ = $(this).attr("href");
			window.location = targ;
		}
		else{
			return false;
		}
	});


	$('.allcheck').change(function() {
	    var checkboxes = $(this).closest('form').find(':checkbox');
	    if($(this).is(':checked')) {
	        checkboxes.prop('checked', true);
	        checkboxes.closest(".tr").css("background-color","#ccc");
	    } else {
	        checkboxes.prop('checked', false);
	        checkboxes.closest(".tr").css("background-color","#f7f7f7");
	    }
	    toggle_toolbar();
	});

	$(".checkid").change(function(){
		var isi = $(this).is(":checked");
		if(isi){
			$(this).closest(".tr").css("background-color","#ccc");
		}
		else{
			$(this).closest(".tr").css("background-color","#f7f7f7");
		}
	    toggle_toolbar();
	});


	function toggle_toolbar(){
		if($('input[type="checkbox"]:checked').length == 0)
			$(".toolbar").css("visibility","hidden");
		else
			$(".toolbar").css("visibility","visible");
	}


	$(".quick-reply-toggle").click(function(){
		$(".quick-reply").slideToggle();
		if($(this).html() == "Quick Reply")
			$(this).html("Back");
		else
			$(this).html("Quick Reply");
	});



	$(".chosen-select").chosen({
		no_results_text : "Oops, email tidak terdaftar",
		width : "100%"
	});

	$("#file").simpleFilePreview();



});
</script>
</body>
</html>