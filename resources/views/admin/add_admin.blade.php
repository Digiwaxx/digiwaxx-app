    @extends('admin.admin_dashboard_active_sidebar')
    @section('content')

    <div class="main-content">
            <div class="main-content-inner">
                <!-- #section:basics/content.breadcrumbs -->
                <div class="breadcrumbs" id="breadcrumbs">
                    <script type="text/javascript">
                        try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
                    </script>

                    <ul class="breadcrumb">
                        <li>
                            <i class="ace-icon fa fa-users users-icon"></i>
                            <a href="{{ route('admin_listing') }}">Admins</a>
                        </li>
                        <li class="active">Add admin</li>
                    </ul><!-- /.breadcrumb -->
                </div>

                <!-- /section:basics/content.breadcrumbs -->
                <div class="page-content">
                    
                            <!-- PAGE CONTENT BEGINS -->
                                                           
                                <?php if(isset($alertMessage))
                                {
                                ?>
                                
                                <div class="<?php echo $alertClass; ?>"><?php echo $alertMessage; ?>
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                </div>
                                <?php
                                
                                }
                                ?>
                                
                                <h3 class="header smaller lighter">Add Admin</h3>
                                <form role="form" id="addAdmin" action="{{ route('submit_add_admin') }}" method="post" autocomplete="off" style="color:white;">	
                                @csrf
                                <div class="row">
                                <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label" for="form-field-1">  Name </label>                                    
                                        <input type="text" id="name" placeholder="Name" name="name" class="form-control">
                                    </div>
                                </div>
                                
                                <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label" for="form-field-1"> User Name </label>                                    
                                        <input type="text" id="username" placeholder="User Name" name="username" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label" for="form-field-1"> Email </label>                                    
                                        <input type="email" id="email" placeholder="Email" name="email" class="form-control">
                                    </div>
                                </div>
                                
                                <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label" for="form-field-1"> Password </label>                                    
                                        <input type="password" id="password" placeholder="Password" name="password" class="form-control">
                                    </div>
                                </div>
                                
                                <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label" for="form-field-1"> Confirm Password </label>                                    
                                        <input type="password" id="confirmPassword" placeholder="Confirm Password" name="confirmPassword" class="form-control">
                                    </div>
                                </div>
                                
                                 <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label" for="form-field-1"> Role </label>
                                            <div class="radio">
                                                <label for="admin">
                                                    <input name="role" class="ace" type="radio" value="2" id="admin" checked="checked" onclick="change_role(this.value)" />
                                                    <span class="lbl"> Admin </span>
                                                </label>
                                                
                                                <label for="super_admin">
                                                    <input name="role" class="ace" type="radio" value="1" id="super_admin" onclick="change_role(this.value)" />
                                                    <span class="lbl"> Super Admin </span>
                                                </label>
                                            </div>                                                
                                        </div>
                                    </div>
                                <div class="col-xs-12">
                                <div class="form-group" id="modulesDiv">
                                    <label class="control-label" for="form-field-1"> Modules </label>
                                            <div class="checkbox">
                                                <label for="clients">
                                                    <input name="modules[]" class="ace ace-checkbox-2" type="checkbox" value="1" id="clients" />
                                                    <span class="lbl"> Clients </span>
                                                </label>
                                            </div>	
                                            <div class="checkbox">	
                                                <label for="members">
                                                    <input name="modules[]" class="ace ace-checkbox-2" type="checkbox" value="2" id="members" />
                                                    <span class="lbl"> Members </span>
                                                </label>
                                            </div>	
                                            <div class="checkbox">	
                                                <label for="tracks">
                                                    <input name="modules[]" class="ace ace-checkbox-2" type="checkbox" value="3" id="tracks" />
                                                    <span class="lbl"> Tracks </span>
                                                </label>
                                                
                                            </div>
                                            <div class="checkbox">
                                                <label for="dj_tools">
                                                    <input name="modules[]" class="ace ace-checkbox-2" type="checkbox" value="4" id="dj_tools" />
                                                    <span class="lbl"> DJ Tools </span>
                                                </label>
                                                </div>
                                            <div class="checkbox">
                                                <label for="logos">
                                                    <input name="modules[]" class="ace ace-checkbox-2" type="checkbox" value="5" id="logos" />
                                                    <span class="lbl"> Logos </span>
                                                </label>
                                            </div>	
                                            <div class="checkbox">	
                                                <label for="labels">
                                                    <input name="modules[]" class="ace ace-checkbox-2" type="checkbox" value="6" id="labels" />
                                                    <span class="lbl"> Labels </span>
                                                </label>
                                            </div>	
                                            <div class="checkbox">	
                                                <label for="mails">
                                                    <input name="modules[]" class="ace ace-checkbox-2" type="checkbox" value="7" id="mails" />
                                                    <span class="lbl"> Mails </span>
                                                </label>
                                            </div>
                                            <div class="checkbox">	
                                                <label for="genres">
                                                    <input name="modules[]" class="ace ace-checkbox-2" type="checkbox" value="10" id="genres">
                                                    <span class="lbl"> Genres </span>
                                                </label>
                                            </div>
                                            <div class="checkbox">	
                                                <label for="subscribers">
                                                    <input name="modules[]" class="ace ace-checkbox-2" type="checkbox" value="8" id="subscribers" />
                                                    <span class="lbl"> Subscribers </span>
                                                </label>
                                            </div>
                                            <div class="checkbox">	
                                                <label for="website_pages">
                                                    <input name="modules[]" class="ace ace-checkbox-2" type="checkbox" value="9" id="website_pages">
                                                    <span class="lbl"> Website Pages </span>
                                                </label>
                                            </div>
                                            <div class="checkbox">	
                                                <label for="products">
                                                    <input name="modules[]" class="ace ace-checkbox-2" type="checkbox" value="11" id="products">
                                                    <span class="lbl"> Products </span>
                                                </label>
                                            </div>
                                            <div class="checkbox">	
                                                <label for="countries_states">
                                                    <input name="modules[]" class="ace ace-checkbox-2" type="checkbox" value="12" id="countries_states">
                                                    <span class="lbl"> Countries & States </span>
                                                </label>
                                            </div>
                                            <div class="checkbox">	
                                                <label for="staff_selection">
                                                    <input name="modules[]" class="ace ace-checkbox-2" type="checkbox" value="13" id="staff_selection">
                                                    <span class="lbl"> Staff Selection </span>
                                                </label>
                                            </div>
                                            <div class="checkbox">	
                                                <label for="digicoins">
                                                    <input name="modules[]" class="ace ace-checkbox-2" type="checkbox" value="14" id="digicoins">
                                                    <span class="lbl"> Digicoins </span>
                                                </label>
                                            </div>
                                    </div>
                                </div>	
                                <div class="col-xs-12">
                                    <div class="form-actions text-right">
                                        <button class="btn btn-info btn-sm" type="submit" name="submit_addAdmin">
                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                            Add Admin
                                        </button>

                                        &nbsp;
                                        <button class="btn btn-sm btn-reset" type="reset">
                                            <i class="ace-icon fa fa-undo bigger-110"></i>
                                            Reset
                                        </button>
                                    </div>
                                </div>	
                                
                                
                                
                                </div>
                                
                                </form>
                                    

                        
                            <!-- PAGE CONTENT ENDS -->
                    
                </div><!-- /.page-content -->
                
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
</div>
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
        if('ontouchstart' in document.documentElement) document.write("<script src='{{ asset('assets_admin/assets/js/jquery.mobile.custom.js')}}'>"+"<"+"/script>");
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

    <!-- inline scripts related to this page -->
    <script type="text/javascript">
        jQuery(function($) {
            var oTable1 = 
            $('#sample-table-2')
            //.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
            .dataTable( {
                bAutoWidth: false,
                "aoColumns": [
                    { "bSortable": false },
                    null, null,null, null, null,
                    { "bSortable": false }
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
            } );
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
        
        
            $(document).on('click', 'th input:checkbox' , function(){
                var that = this;
                $(this).closest('table').find('tr > td:first-child input:checkbox')
                .each(function(){
                    this.checked = that.checked;
                    $(this).closest('tr').toggleClass('selected');
                });
            });
        
        
            $('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
            function tooltip_placement(context, source) {
                var $source = $(source);
                var $parent = $source.closest('table')
                var off1 = $parent.offset();
                var w1 = $parent.width();
        
                var off2 = $source.offset();
                //var w2 = $source.width();
        
                if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
                return 'left';
            }
        
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

    function change_role(role)
    {
    if(role==1)
    {
    document.getElementById('modulesDiv').style.display = 'none';
    }
    else
    {
    document.getElementById('modulesDiv').style.display = 'block';
    }

    }

    function goToPage(page,pid)
    {
    window.location = page+"?page="+pid;
    }

    function deleteRecord(page,did,msg)
    {

    if(confirm(msg))
    {

    window.location = page+"?did="+did;
    }

    }

    function deleteRecord1(page,did,msg)
    {

    if(confirm(msg))
    {

    alert(page+"did="+did);
    window.location = page+"did="+did;
    }

    }


    function sortData()
    {

        var records = document.getElementById('numRecords').value;		
        var sortBy = document.getElementById('sortBy').value;		
        var sortOrder = document.getElementById('sortOrder').value;		
        var page = document.getElementById('page').value;		
        
        window.location = page+"?sortBy="+sortBy+"&sortOrder="+sortOrder+"&records="+records;
    }

    function changeNumRecords(page,sortBy,sortOrder,records)
    {
        
        window.location = page+"?sortBy="+sortBy+"&sortOrder="+sortOrder+"&records="+records;
    }

    function searchReset()
    {


        document.getElementById("searchForm").reset();
    }


    </script>





    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.js"></script>
                
                <script>

    // Wait for the DOM to be ready
    $(function() {

    $("#addAdmin").validate();

    $("#name").rules("add", {
        required:true,
        messages: {
            required: "Please enter name."
        }
    });

    $("#username").rules("add", {
        required:true,
        messages: {
            required: "Please enter username"
        }
    });

    $("#email").rules("add", {
        required:true,
        email:true,
        messages: {
            required: "Please enter a valid email id."
        }
    });

    $("#password").rules("add", {
        required:true,
        minlength:6,
        messages: {
            required: "Please enter password"
        }
    });

    $("#confirmPassword").rules("add", {
        required:true,
        minlength:6,
        equalTo: "#password",
        messages: {
            required: "Please enter confirm password"
        }
    });



    });

    </script>


    @endsection       
