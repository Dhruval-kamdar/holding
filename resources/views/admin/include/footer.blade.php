    <script src="{{ url('public/admin/assets/plugins/jquery/jquery.min.js') }}" ></script>
    <script src="{{ url('public/admin/assets/plugins/popper/popper.min.js') }}" ></script>
    <script src="{{ url('public/admin/assets/plugins/jquery-blockui/jquery.blockui.min.js') }}" ></script>
    <script src="{{ url('public/admin/assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
    <!-- bootstrap -->
    <script src="{{ url('public/admin/assets/plugins/bootstrap/js/bootstrap.min.js') }}" ></script>
    <script src="{{ url('public/admin/assets/plugins/sparkline/jquery.sparkline.min.js') }}" ></script>
    <script src="{{ url('public/admin/assets/js/pages/sparkline/sparkline-data.js') }}" ></script>
    <!-- Common js-->
    <script src="{{ url('public/admin/assets/js/app.js') }}" ></script>
    <script src="{{ url('public/admin/assets/js/layout.js') }}" ></script>
    <script src="{{ url('public/admin/assets/js/theme-color.js') }}" ></script>
    <!-- material -->
    <script src="{{ url('public/admin/assets/plugins/material/material.min.js') }}"></script>
    <!-- animation -->
    <script src="{{ url('public/admin/assets/js/pages/ui/animations.js') }}" ></script>
    <!-- morris chart -->
    
    <script src="{{ url('public/admin/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" ></script>
    
    
    
    
    <!-- end js include path -->    
    
    @if (!empty($pluginjs)) 
    @foreach ($pluginjs as $value) 
    <script src="{{ url('public/admin/assets/'.$value) }}" type="text/javascript"></script>
    @endforeach
    @endif
    @if (!empty($js)) 
    @foreach ($js as $value) 
    <script src="{{ url('public/admin/assets/js/'.$value) }}" type="text/javascript"></script>
    @endforeach
    @endif
    
    <script src="{{ url('public/admin/assets/js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('public/admin/assets/js/comman_function.js') }}" ></script>
    
    <script>
        jQuery(document).ready(function() {
        @if (!empty($funinit))
                @foreach ($funinit as $value)
        {{  $value }}
        @endforeach
                @endif
        });
    </script>
    
    <div id="deleteModel" class="modal fade" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12"><h3 class="m-t-none m-b">Delete Record</h3>
                            Are You sure want to delete record ?<br/>
                            <form role="form">
                                <div>
                                    <button class="btn btn-sm btn-primary pull-right m-l" style="margin: 10px;"data-dismiss="modal">Cancel</button>
                                    <button class="btn btn-sm btn-danger pull-right yes-sure m-l" style="margin: 10px;"  type="button"><strong><i class="fa fa-trash"></i> Delete </strong></button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>