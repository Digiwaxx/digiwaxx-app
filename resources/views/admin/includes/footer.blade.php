<div class="processing_loader_gif"><img src="{{ asset('public/images/loader.gif') }}"></div>

<div class="footer">

    <div class="footer-inner">

        <!-- #section:basics/footer -->

        <div class="footer-content" style="border-top: none;">

        </div>

        <!-- /section:basics/footer -->

    </div>

</div>

<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">

    <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>

</a>

</div><!-- /.main-container -->

<!-- basic scripts -->

<!--[if !IE]> -->

<script type="text/javascript">

    window.jQuery || document.write("<script src='{{ asset('assets_admin/assets/js/jquery.js')}}'>" + "<" + "/script>");

</script>

<!-- <![endif]-->

<!--[if IE]>

<script type="text/javascript">

 window.jQuery || document.write("<script src='../assets/js/jquery1x.js'>"+"<"+"/script>");

</script>

<![endif]-->

<script type="text/javascript">

    if ('ontouchstart' in document.documentElement) document.write("<script src='{{ asset('assets_admin/assets/js/jquery.mobile.custom.js')}}'>" + "<" + "/script>");

</script>

<script src="{{ asset('assets_admin/assets/js/bootstrap.js')}}"></script>

<!-- page specific plugin scripts -->

<script src="{{ asset('assets_admin/assets/js/jquery.dataTables.js')}}"></script>

<script src="{{ asset('assets_admin/assets/js/jquery.dataTables.bootstrap.js')}}"></script>

<!-- ace scripts -->

<script src="{{ asset('assets_admin/assets/js/ace/elements.scroller.js')}}"></script>

<script src="{{ asset('assets_admin/assets/js/ace/elements.colorpicker.js')}}"></script>

<script src="{{ asset('assets_admin/assets/js/ace/elements.fileinput.js')}}"></script>

<script src="{{ asset('assets_admin/assets/js/ace/elements.typeahead.js')}}"></script>

<script src="{{ asset('assets_admin/assets/js/ace/elements.wysiwyg.js')}}"></script>

<script src="{{ asset('assets_admin/assets/js/ace/elements.spinner.js')}}"></script>

<script src="{{ asset('assets_admin/assets/js/ace/elements.treeview.js')}}"></script>

<script src="{{ asset('assets_admin/assets/js/ace/elements.wizard.js')}}"></script>

<script src="{{ asset('assets_admin/assets/js/ace/elements.aside.js')}}"></script>

<script src="{{ asset('assets_admin/assets/js/ace/ace.js')}}"></script>

<script src="{{ asset('assets_admin/assets/js/ace/ace.ajax-content.js')}}"></script>

<script src="{{ asset('assets_admin/assets/js/ace/ace.touch-drag.js')}}"></script>

<script src="{{ asset('assets_admin/assets/js/ace/ace.sidebar.js')}}"></script>

<script src="{{ asset('assets_admin/assets/js/ace/ace.sidebar-scroll-1.js')}}"></script>

<script src="{{ asset('assets_admin/assets/js/ace/ace.submenu-hover.js')}}"></script>

<script src="{{ asset('assets_admin/assets/js/ace/ace.widget-box.js')}}"></script>

<script src="{{ asset('assets_admin/assets/js/ace/ace.settings.js')}}"></script>

<script src="{{ asset('assets_admin/assets/js/ace/ace.settings-rtl.js')}}"></script>

<script src="{{ asset('assets_admin/assets/js/ace/ace.settings-skin.js')}}"></script>

<script src="{{ asset('assets_admin/assets/js/ace/ace.widget-on-reload.js')}}"></script>

<script src="{{ asset('assets_admin/assets/js/ace/ace.searchbox-autocomplete.js')}}"></script>



		<!--script src="{{ asset('assets_admin/assets/js/ace/ace.searchbox-artist.js')}}"></script>

		<script src="{{ asset('assets_admin/assets/js/ace/ace.searchbox-c_name.js')}}"></script>

		<script src="{{ asset('assets_admin/assets/js/ace/ace.searchbox-c_relation.js')}}"></script>

		<script src="{{ asset('assets_admin/assets/js/ace/ace.searchbox-c_email.js')}}"></script>

		<script src="{{ asset('assets_admin/assets/js/ace/ace.searchbox-c_phone.js')}}"></script-->





<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<!-- inline scripts related to this page -->

<script type="text/javascript">

    jQuery(function($) {

        var oTable1 =

            $('#sample-table-2')

            //.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)

            .dataTable({

                bAutoWidth: false,

                "aoColumns": [{

                        "bSortable": false

                    },

                    null, null, null, null, null,

                    {

                        "bSortable": false

                    }

                ],

                "aaSorting": [],

                //,

                //"sScrollY": "200px",

                //"bPaginate": false,

                //"sScrollX": "100%",

                //"sScrollXInner": "120%",

                //"bScrollCollapse": true,

                //Note: if you are applying horizontal scrolling (sScrollX) on a ".table-bordered"

                //you may want to wrap the table inside a "div.dataTables_borderWrap" element

                //"iDisplayLength": 50

            });

        /**

				var tableTools = new $.fn.dataTable.TableTools( oTable1, {

					"sSwfPath": "../../copy_csv_xls_pdf.swf",

			        "buttons": [

			            "copy",

			            "csv",

			            "xls",

						"pdf",

			            "print"

			        ]

			    } );

			    $( tableTools.fnContainer() ).insertBefore('#sample-table-2');

				*/

        //oTable1.fnAdjustColumnSizing();

        $(document).on('click', '.news-reset-btn', function() { 

				$('.remove-subscriber').trigger('click');

		});

        $(document).on('click', 'th input:checkbox', function() {

            var that = this;

            $(this).closest('table').find('tr > td:first-child input:checkbox')

                .each(function() {

                    this.checked = that.checked;

                    $(this).closest('tr').toggleClass('selected');

                });

        });

        $('[data-rel="tooltip"]').tooltip({

            placement: tooltip_placement

        });



        function tooltip_placement(context, source) {

            var $source = $(source);

            var $parent = $source.closest('table')

            var off1 = $parent.offset();

            var w1 = $parent.width();

            var off2 = $source.offset();

            //var w2 = $source.width();

            if (parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2)) return 'right';

            return 'left';

        }

		

		setTimeout(function() { 

			  $('.page-content .alert').hide(); 

		}, 4000);

    })

</script>

<!-- the following scripts are used in demo only for onpage help and you don't need them -->

<!--<link rel="stylesheet" href="../assets/css/ace.onpage-help.css" />

		<link rel="stylesheet" href="../docs/assets/js/themes/sunburst.css" />

		<script type="text/javascript"> ace.vars['base'] = '..'; </script>

		<script src="../assets/js/ace/elements.onpage-help.js"></script>

		<script src="../assets/js/ace/ace.onpage-help.js"></script>

		<script src="../docs/assets/js/rainbow.js"></script>

		<script src="../docs/assets/js/language/generic.js"></script>

		<script src="../docs/assets/js/language/html.js"></script>

		<script src="../docs/assets/js/language/css.js"></script>

		<script src="../docs/assets/js/language/javascript.js"></script>-->

</body>



</html>

<script>

    function goToPage1(page, pid) {

        window.location = page + "page=" + pid;

    }



    function goToPage(page, pid) {

        let searchParams = new URLSearchParams(window.location.search);

        if(searchParams.has('numRecords')){

            var num = searchParams.get('numRecords');

        }

        if (num==undefined){

            num= jQuery("#numRecords").val();

           

        }

       

        window.location = page + "?numRecords=" + num + "&page=" + pid;

    }



    function deleteRecord(page, did, msg) {

        if (confirm(msg)) {

            window.location = page + "?did=" + did;

        }

    }

    

    

    function deleteSelectedRecord()

    {   

        var delete_ids='';

        var ccount=0;

        $("table tr input[type=checkbox]").each(function(){

            if($(this).is(':checked')){

                // delete_ids.push($(this).val());

                if(ccount==0){

                    delete_ids=$(this).val()+delete_ids;

                }else{

                    delete_ids=$(this).val()+','+delete_ids;

                }

                

                ccount++;

            }

        });

        if(ccount==0){

            alert("Please mark members to delete");

        }else{

            window.location = "members?delete_selected_ids="+delete_ids;

            console.log('members'+"?delete_selected_ids="+delete_ids);

        }

        

    }





    function deleteRecord1(page, did, msg) {

        if (confirm(msg)) {

            window.location = page + "did=" + did;

        }

    }



    function sortData() {

        var records = document.getElementById('numRecords').value;

        var sortBy = document.getElementById('sortBy').value;

        var sortOrder = document.getElementById('sortOrder').value;

        var page = document.getElementById('page').value;

        window.location = page + "?sortBy=" + sortBy + "&sortOrder=" + sortOrder + "&records=" + records;

    }



    function changeNumRecords(page, sortBy, sortOrder, records) {

        window.location = page + "?sortBy=" + sortBy + "&sortOrder=" + sortOrder + "&records=" + records;

    }



    function searchReset() {

        $('input[type="checkbox"]').each(function() {

            $(this).removeAttr('checked');

        });

        

        if($('#client').length) {

            $('#client').val(0).trigger('change');

        }

        

        document.getElementById("searchForm").reset();

    }

    

    

    jQuery( document ).ready(function() {

          jQuery("audio").bind("play",function (){

            jQuery("audio").not(this).each(function (index, audio) {

              audio.pause();

            });

         });



   

    });

    

    

    

</script>